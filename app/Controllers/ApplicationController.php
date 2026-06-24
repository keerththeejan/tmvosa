<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\App;
use App\Core\Auth;
use App\Core\Security;
use App\Core\Database;
use App\Core\Session;
use App\Models\Member;
use App\Models\Application;
use App\Models\Document;
use App\Models\AuditLog;
use App\Helpers\NumberGenerator;
use App\Helpers\FileUploader;
use App\Helpers\Mailer;
use App\Helpers\DateParser;
use App\Helpers\ApplicationValidation;
use App\Models\Setting;

class ApplicationController extends Controller
{
    public function form(): void
    {
        $countries = Database::fetchAll("SELECT * FROM countries WHERE is_active = 1 ORDER BY name");
        $membershipTypes = Database::fetchAll("SELECT * FROM membership_types WHERE is_active = 1");
        $validationConfig = [
            'blockDuplicateMobile' => Setting::get('block_duplicate_mobile', '0') === '1',
            'blockDuplicateEmail' => Setting::get('block_duplicate_email', '0') === '1',
        ];
        $this->view('applications/form', compact('countries', 'membershipTypes', 'validationConfig') + ['pageScript' => 'application-wizard.js']);
    }

    public function validateField(): void
    {
        $field = Security::sanitize($this->input('field', ''));
        $value = $this->input('value', '');
        $allowed = ['nic_number', 'mobile', 'email'];

        if (!in_array($field, $allowed, true)) {
            $this->json(['success' => false, 'message' => 'Invalid field.'], 422);
        }

        $result = ApplicationValidation::checkField($field, $value);
        $this->json(['success' => true] + $result);
    }

    public function submit(): void
    {
        if (!$this->validateCsrf()) {
            $this->rejectCsrf('application_submit');
        }

        $rawDob = trim($this->input('date_of_birth', ''));
        $data = $this->collectApplicationData();
        $data['date_of_birth'] = DateParser::parseDob($rawDob);
        $errors = $this->validateApplicationData($data, $rawDob);
        $errors = array_merge($errors, $this->validateRequiredDocuments());
        $errors = array_merge($errors, $this->validateUniqueness($data));
        if ($errors) {
            $this->json(['success' => false, 'message' => implode(' ', $errors)], 422);
        }

        $data['status'] = 'pending';
        $data['ip_address'] = Security::getClientIp();
        $data['user_agent'] = $_SERVER['HTTP_USER_AGENT'] ?? '';

        Database::beginTransaction();
        try {
            $data['application_number'] = NumberGenerator::applicationNumber();
            $appId = Application::create($data);
            if ($appId <= 0) {
                throw new \RuntimeException('Application insert returned no ID.');
            }
            $this->handleDocumentUploads($appId);
            Database::commit();
        } catch (\PDOException $e) {
            if (Database::getInstance()->inTransaction()) {
                Database::rollback();
            }
            error_log('Application submit database error: ' . $e->getMessage());
            if ((int) $e->getCode() === 23000 || str_contains($e->getMessage(), 'uq_member_applications_nic_number')) {
                $this->json([
                    'success' => false,
                    'message' => 'இந்த தேசிய அடையாள அட்டை இலக்கத்துடன் ஏற்கனவே ஒரு விண்ணப்பம் பதிவு செய்யப்பட்டுள்ளது. Application already exists for this NIC number.',
                ], 422);
            }
            $this->json([
                'success' => false,
                'message' => 'Could not save your application. Please try again or contact the secretary.',
            ], 500);
        } catch (\Throwable $e) {
            if (Database::getInstance()->inTransaction()) {
                Database::rollback();
            }
            error_log('Application submit failed: ' . $e->getMessage());
            $this->json([
                'success' => false,
                'message' => 'Could not save your application. Please try again or contact the secretary.',
            ], 500);
        }

        $this->sendSubmissionEmails($data);

        Session::set('application_submitted', [
            'application_number' => $data['application_number'],
            'full_name' => $data['full_name_english'],
            'submitted_at' => date('Y-m-d H:i:s'),
        ]);

        $successUrl = App::routeUrl('apply/success');
        $payload = [
            'success' => true,
            'message' => 'Application submitted successfully!',
            'application_number' => $data['application_number'],
            'redirect' => $successUrl,
        ];

        if ($this->wantsJson()) {
            $this->json($payload);
        }

        $this->redirect($successUrl);
    }

    public function success(): void
    {
        $submission = Session::get('application_submitted');
        if (!$submission || empty($submission['application_number'])) {
            $this->redirect(App::routeUrl(''));
            return;
        }

        Session::remove('application_submitted');
        $this->view('applications/success', [
            'applicationNumber' => $submission['application_number'],
            'fullName' => $submission['full_name'] ?? '',
            'submittedAt' => $submission['submitted_at'] ?? '',
        ]);
    }

    private function sendSubmissionEmails(array $data): void
    {
        try {
            if (!empty($data['email'])) {
                $sent = Mailer::sendTemplate($data['email'], 'application_received', [
                    'full_name' => $data['full_name_english'],
                    'application_number' => $data['application_number'],
                ], $data['full_name_english']);
                if (!$sent) {
                    error_log('Application received email failed: ' . (Mailer::getLastError() ?? 'unknown'));
                }
            }

            $notified = Mailer::notifyAdmin('admin_notification', [
                'application_number' => $data['application_number'],
                'full_name' => $data['full_name_english'],
                'mobile' => $data['mobile'],
                'email' => $data['email'] ?? 'N/A',
            ]);
            if (!$notified) {
                error_log('Admin notification email failed: ' . (Mailer::getLastError() ?? 'unknown'));
            }
        } catch (\Throwable $e) {
            error_log('Application submit email error: ' . $e->getMessage());
        }
    }

    public function index(): void
    {
        $status = $this->input('status', '');
        $page = (int) $this->input('page', 1);
        $applications = Application::getAll($status, $page);
        $this->view('applications/index', [
            'applications' => $applications,
            'currentStatus' => $status,
            'pageScript' => 'applications-index.js',
        ]);
    }

    public function show(string $id): void
    {
        $application = Application::findById((int) $id);
        if (!$application) {
            http_response_code(404);
            return;
        }
        $documents = Document::getByApplication((int) $id);
        $documentsByType = [];
        foreach ($documents as $doc) {
            $documentsByType[$doc['document_type']] = $doc;
        }
        $duplicates = ApplicationValidation::findDuplicatesForNic($application['nic_number'] ?? '');
        $this->view('applications/show', compact('application', 'documents', 'documentsByType', 'duplicates') + [
            'pageScript' => 'application-show.js',
        ]);
    }

    public function duplicates(): void
    {
        if (!Auth::hasRole('super_admin')) {
            http_response_code(403);
            echo 'Unauthorized';
            return;
        }

        $summary = ApplicationValidation::getDuplicateNicSummary();
        $details = [];
        foreach ($summary as $row) {
            $details[$row['nic_key']] = ApplicationValidation::findDuplicatesForNic($row['nic_key']);
        }

        $this->view('applications/duplicates', compact('summary', 'details'));
    }

    public function uploadDocument(string $id): void
    {
        if (!$this->validateCsrf()) {
            $this->json(['success' => false, 'message' => 'Invalid request.'], 403);
        }

        $application = Application::findById((int) $id);
        if (!$application) {
            $this->json(['success' => false, 'message' => 'Application not found.'], 404);
        }

        $type = $this->input('document_type', '');
        $allowed = ['payment_slip', 'nic_copy', 'passport_photo'];
        if (!in_array($type, $allowed, true)) {
            $this->json(['success' => false, 'message' => 'Invalid document type.'], 422);
        }

        if (empty($_FILES['document']['name']) || ($_FILES['document']['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            $this->json(['success' => false, 'message' => 'Please select a file to upload.'], 422);
        }

        $result = FileUploader::upload($_FILES['document'], 'documents');
        if (isset($result['errors'])) {
            $this->json(['success' => false, 'message' => implode(' ', $result['errors'])], 422);
        }

        Database::query(
            "DELETE FROM member_documents WHERE application_id = ? AND document_type = ?",
            [(int) $id, $type]
        );

        Document::create([
            'application_id' => (int) $id,
            'document_type' => $type,
            'file_name' => $result['file_name'],
            'file_path' => $result['file_path'],
            'file_size' => $result['file_size'],
            'mime_type' => $result['mime_type'],
        ]);

        AuditLog::log('upload_application_document', 'member_applications', (int) $id, null, ['document_type' => $type]);

        $this->json(['success' => true, 'message' => 'Document uploaded successfully.']);
    }

    public function approve(string $id): void
    {
        if (!$this->validateCsrf()) {
            $this->json(['success' => false, 'message' => 'Invalid request.'], 403);
        }

        $application = Application::findById((int) $id);
        if (!$application) {
            $this->json(['success' => false, 'message' => 'Application not found.'], 404);
        }

        Database::beginTransaction();
        try {
            $membershipNumber = NumberGenerator::membershipNumber();
            $type = Database::fetch("SELECT duration_years FROM membership_types WHERE id = ?", [$application['membership_type_id']]);
            $durationYears = $type['duration_years'] ?? 1;

            $expiryDate = date('Y-m-d', strtotime("+{$durationYears} years"));

            $memberId = Member::create([
                'membership_number' => $membershipNumber,
                'full_name_tamil' => $application['full_name_tamil'],
                'full_name_english' => $application['full_name_english'],
                'gender' => $application['gender'],
                'date_of_birth' => $application['date_of_birth'],
                'nic_number' => $application['nic_number'],
                'current_address' => $application['current_address'],
                'permanent_address' => $application['permanent_address'],
                'country_id' => $application['country_id'],
                'mobile' => $application['mobile'],
                'whatsapp' => $application['whatsapp'],
                'email' => $application['email'],
                'studied_from_year' => $application['studied_from_year'],
                'studied_to_year' => $application['studied_to_year'],
                'grade_stream' => $application['grade_stream'],
                'teacher_name' => $application['teacher_name'],
                'occupation' => $application['occupation'],
                'company' => $application['company'],
                'membership_type_id' => $application['membership_type_id'],
                'status' => 'active',
                'membership_start_date' => date('Y-m-d'),
                'membership_expiry_date' => $expiryDate,
                'approved_by' => Auth::id(),
                'approved_at' => date('Y-m-d H:i:s'),
            ]);

            Application::update((int) $id, [
                'status' => 'approved',
                'member_id' => $memberId,
                'reviewed_by' => Auth::id(),
                'reviewed_at' => date('Y-m-d H:i:s'),
            ]);

            Database::query(
                "UPDATE member_documents SET member_id = ? WHERE application_id = ?",
                [$memberId, $id]
            );

            AuditLog::log('approve_application', 'member_applications', (int) $id);

            Database::commit();

            $member = Member::findById($memberId);
            $notifyEmail = $member['email'] ?? $application['email'] ?? '';

            if (!empty($notifyEmail)) {
                $mailContext = [
                    'related_type' => 'member_applications',
                    'related_id' => (int) $id,
                ];
                $name = $application['full_name_english'];

                Mailer::sendTemplate($notifyEmail, 'application_approved', [
                    'full_name' => $name,
                    'membership_number' => $membershipNumber,
                ], $name, $mailContext);

                Mailer::sendTemplate($notifyEmail, 'membership_activated', [
                    'full_name' => $name,
                    'membership_number' => $membershipNumber,
                    'expiry_date' => date('d M Y', strtotime($expiryDate)),
                ], $name, $mailContext);

                Mailer::sendTemplate($notifyEmail, 'welcome_email', [
                    'full_name' => $name,
                    'membership_number' => $membershipNumber,
                ], $name, $mailContext);
            } else {
                error_log('Application approve: no member email for application id=' . $id);
            }

            $this->json(['success' => true, 'message' => 'Application approved.', 'membership_number' => $membershipNumber]);
        } catch (\Exception $e) {
            Database::rollback();
            $this->json(['success' => false, 'message' => 'Approval failed.'], 500);
        }
    }

    public function reject(string $id): void
    {
        if (!$this->validateCsrf()) {
            $this->json(['success' => false, 'message' => 'Invalid request.'], 403);
        }

        $reason = Security::sanitize($this->input('reason', ''));
        Application::update((int) $id, [
            'status' => 'rejected',
            'rejection_reason' => $reason,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => date('Y-m-d H:i:s'),
        ]);

        $application = Application::findById((int) $id);
        if ($application && !empty($application['email'])) {
            Mailer::sendTemplate($application['email'], 'application_rejected', [
                'full_name' => $application['full_name_english'],
                'reason' => $reason,
            ]);
        }

        AuditLog::log('reject_application', 'member_applications', (int) $id);
        $this->json(['success' => true, 'message' => 'Application rejected.']);
    }

    public function destroy(string $id): void
    {
        if (!$this->validateCsrf()) {
            $this->json(['success' => false, 'message' => 'Invalid request.'], 403);
        }

        $application = Application::findById((int) $id);
        if (!$application) {
            $this->json(['success' => false, 'message' => 'Application not found.'], 404);
        }

        if ($application['status'] === 'approved' && !empty($application['member_id'])) {
            $this->json([
                'success' => false,
                'message' => 'Approved applications linked to a member cannot be deleted. Manage the member record instead.',
            ], 422);
        }

        Database::beginTransaction();
        try {
            $this->deleteApplicationFiles((int) $id);
            Application::delete((int) $id);
            AuditLog::log('delete_application', 'member_applications', (int) $id, [
                'application_number' => $application['application_number'],
                'status' => $application['status'],
                'full_name' => $application['full_name_english'],
            ]);
            Database::commit();

            $this->json([
                'success' => true,
                'message' => 'Application deleted successfully.',
                'redirect' => \App\Core\App::baseUrl() . '/applications',
            ]);
        } catch (\Exception $e) {
            Database::rollback();
            error_log('Application delete failed: ' . $e->getMessage());
            $this->json(['success' => false, 'message' => 'Could not delete application. Please try again.'], 500);
        }
    }

    public function track(): void
    {
        $number = Security::sanitize($this->input('application_number', ''));
        $application = Database::fetch(
            "SELECT application_number, status, created_at, rejection_reason FROM member_applications WHERE application_number = ?",
            [$number]
        );
        $this->json(['success' => (bool) $application, 'application' => $application]);
    }

    private function collectApplicationData(): array
    {
        $tamil = Security::sanitize($this->input('full_name_tamil', ''));
        $english = Security::sanitize($this->input('full_name_english', ''));

        return [
            'full_name_tamil' => $tamil,
            'full_name_english' => $english !== '' ? $english : $tamil,
            'gender' => $this->input('gender'),
            'nic_number' => ApplicationValidation::normalizeNic(Security::sanitize($this->input('nic_number', ''))),
            'current_address' => Security::sanitize($this->input('current_address', '')),
            'permanent_address' => Security::sanitize($this->input('permanent_address', '')),
            'country_id' => (int) $this->input('country_id') ?: null,
            'mobile' => Security::sanitize($this->input('mobile', '')),
            'whatsapp' => Security::sanitize($this->input('whatsapp', '')),
            'email' => filter_var($this->input('email', ''), FILTER_SANITIZE_EMAIL) ?: null,
            'studied_from_year' => $this->input('studied_from_year') ?: null,
            'studied_to_year' => $this->input('studied_to_year') ?: null,
            'grade_stream' => Security::sanitize($this->input('grade_stream', '')),
            'teacher_name' => Security::sanitize($this->input('teacher_name', '')),
            'occupation' => Security::sanitize($this->input('occupation', '')),
            'company' => Security::sanitize($this->input('company', '')),
            'proposer_name' => Security::sanitize($this->input('proposer_name', '')),
            'proposer_contact' => Security::sanitize($this->input('proposer_contact', '')),
            'membership_type_id' => (int) $this->input('membership_type_id'),
            'amount_paid' => (float) $this->input('amount_paid', 0),
            'payment_method' => Security::sanitize($this->input('payment_method', '')),
            'transaction_number' => Security::sanitize($this->input('transaction_number', '')),
            'payment_date' => $this->input('payment_date') ?: null,
        ];
    }

    private function validateApplicationData(array $data, string $rawDob = ''): array
    {
        $errors = [];
        $required = [
            'full_name_tamil' => 'Tamil full name is required.',
            'gender' => 'Gender is required.',
            'nic_number' => 'NIC number is required.',
            'current_address' => 'Current address is required.',
            'country_id' => 'Country is required.',
            'mobile' => 'Mobile number is required.',
            'studied_from_year' => 'Studied from year is required.',
            'studied_to_year' => 'Studied to year is required.',
            'membership_type_id' => 'Membership category is required.',
            'payment_method' => 'Payment method is required.',
        ];

        foreach ($required as $field => $message) {
            if (empty($data[$field])) {
                $errors[] = $message;
            }
        }

        $rawDob = trim($rawDob);
        if ($rawDob === '') {
            $errors[] = 'Date of birth is required.';
        } elseif (empty($data['date_of_birth'])) {
            $errors[] = 'தயவுசெய்து சரியான பிறந்த திகதியை உள்ளிடவும். Please enter a valid Date of Birth.';
        }

        if ($data['amount_paid'] <= 0) {
            $errors[] = 'Amount paid is required.';
        }

        if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Please enter a valid email address.';
        }

        if (!$this->input('agree_declaration')) {
            $errors[] = 'உறுதிமொழியை ஏற்றுக்கொள்ள வேண்டும். You must accept the declaration before submitting the application.';
        }

        return $errors;
    }

    private function validateUniqueness(array $data): array
    {
        $errors = [];

        if (!empty($data['nic_number'])) {
            $nicCheck = ApplicationValidation::checkNic($data['nic_number']);
            if ($nicCheck['block']) {
                $errors[] = $nicCheck['message_ta'] . ' ' . $nicCheck['message_en'];
            }
        }

        if (!empty($data['mobile'])) {
            $mobileCheck = ApplicationValidation::checkMobile($data['mobile']);
            if ($mobileCheck['block']) {
                $errors[] = $mobileCheck['message_ta'] . ' ' . $mobileCheck['message_en'];
            }
        }

        if (!empty($data['email'])) {
            $emailCheck = ApplicationValidation::checkEmail($data['email']);
            if ($emailCheck['block']) {
                $errors[] = $emailCheck['message_ta'] . ' ' . $emailCheck['message_en'];
            }
        }

        return $errors;
    }

    private function validateRequiredDocuments(): array
    {
        $errors = [];
        $required = [
            'payment_slip' => 'Payment slip is required.',
        ];

        foreach ($required as $field => $message) {
            if (empty($_FILES[$field]['name']) || ($_FILES[$field]['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
                $errors[] = $message;
            }
        }

        return $errors;
    }

    private function handleDocumentUploads(int $applicationId): void
    {
        $docTypes = ['nic_copy', 'passport_photo', 'payment_slip'];
        foreach ($docTypes as $type) {
            if (!empty($_FILES[$type]['name'])) {
                $result = FileUploader::upload($_FILES[$type], 'documents');
                if (!isset($result['errors'])) {
                    Document::create([
                        'application_id' => $applicationId,
                        'document_type' => $type,
                        'file_name' => $result['file_name'],
                        'file_path' => $result['file_path'],
                        'file_size' => $result['file_size'],
                        'mime_type' => $result['mime_type'],
                    ]);
                }
            }
        }
    }

    private function deleteApplicationFiles(int $applicationId): void
    {
        $documents = Document::getByApplication($applicationId);
        foreach ($documents as $doc) {
            $fullPath = FileUploader::getFullPath($doc['file_path']);
            if (is_file($fullPath)) {
                @unlink($fullPath);
            }
        }
    }
}
