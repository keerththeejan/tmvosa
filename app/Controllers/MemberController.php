<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Security;
use App\Core\Database;
use App\Models\Member;
use App\Models\Document;
use App\Models\AuditLog;
use App\Helpers\NumberGenerator;
use App\Helpers\FileUploader;
use App\Helpers\MemberEmail;
use App\Helpers\Mailer;

class MemberController extends Controller
{
    public function index(): void
    {
        $filters = [
            'search' => $this->input('search', ''),
            'status' => $this->input('status', ''),
            'country_id' => $this->input('country_id', ''),
            'membership_type_id' => $this->input('membership_type_id', ''),
            'batch' => $this->input('batch', ''),
            'occupation' => $this->input('occupation', ''),
        ];
        $page = (int) $this->input('page', 1);
        $members = Member::search($filters, $page);
        $countries = Database::fetchAll("SELECT * FROM countries WHERE is_active = 1 ORDER BY name");
        $membershipTypes = \App\Helpers\MembershipType::allActive();

        if ($this->isAjax()) {
            $this->json($members);
            return;
        }

        $this->view('members/index', compact('members', 'filters', 'countries', 'membershipTypes'));
    }

    public function show(string $id): void
    {
        $member = Member::findById((int) $id);
        if (!$member) {
            http_response_code(404);
            \App\Core\View::render('errors/404');
            return;
        }
        $documents = Document::getByMember((int) $id);
        $this->view('members/show', compact('member', 'documents'));
    }

    public function createForm(): void
    {
        $countries = Database::fetchAll("SELECT * FROM countries WHERE is_active = 1 ORDER BY name");
        $membershipTypes = \App\Helpers\MembershipType::allActive();
        $this->view('members/create', compact('countries', 'membershipTypes') + [
            'pageScript' => 'members-create.js',
        ]);
    }

    public function store(): void
    {
        if (!$this->validateCsrf()) {
            $this->rejectCsrf('member_store');
        }

        $emailResult = MemberEmail::parse((string) $this->input('email', ''));
        if (isset($emailResult['error'])) {
            $this->json(['success' => false, 'message' => $emailResult['error']], 422);
        }

        $nic = Security::sanitize($this->input('nic_number', ''));
        if ($nic !== '') {
            $nicCheck = \App\Helpers\ApplicationValidation::checkNic($nic);
            if (!empty($nicCheck['block'])) {
                $this->json(['success' => false, 'message' => $nicCheck['message_en'] ?? 'NIC already exists.'], 422);
            }
        }

        $membershipNumber = $this->input('membership_number') ?: NumberGenerator::membershipNumber();
        if (Member::findByNumber($membershipNumber)) {
            $this->json(['success' => false, 'message' => 'Membership number already exists.'], 422);
        }
        $typeId = (int) $this->input('membership_type_id');
        $type = \App\Helpers\MembershipType::findById($typeId) ?: ['slug' => 'ordinary', 'duration_years' => 1];
        $expiryDate = \App\Helpers\MembershipType::calculateExpiryDate($type);

        $data = [
            'membership_number' => $membershipNumber,
            'full_name_tamil' => Security::sanitize($this->input('full_name_tamil', '')),
            'full_name_english' => Security::sanitize($this->input('full_name_english', '')),
            'gender' => $this->input('gender'),
            'date_of_birth' => $this->input('date_of_birth'),
            'nic_number' => Security::sanitize($this->input('nic_number', '')),
            'current_address' => Security::sanitize($this->input('current_address', '')),
            'permanent_address' => Security::sanitize($this->input('permanent_address', '')),
            'country_id' => (int) $this->input('country_id') ?: null,
            'mobile' => Security::sanitize($this->input('mobile', '')),
            'whatsapp' => Security::sanitize($this->input('whatsapp', '')),
            'email' => $emailResult['value'],
            'studied_from_year' => $this->input('studied_from_year'),
            'studied_to_year' => $this->input('studied_to_year'),
            'grade_stream' => Security::sanitize($this->input('grade_stream', '')),
            'teacher_name' => Security::sanitize($this->input('teacher_name', '')),
            'occupation' => Security::sanitize($this->input('occupation', '')),
            'company' => Security::sanitize($this->input('company', '')),
            'membership_type_id' => $typeId,
            'status' => 'active',
            'membership_start_date' => date('Y-m-d'),
            'membership_expiry_date' => $expiryDate,
            'created_by' => Auth::id(),
        ];

        error_log('Member store email=' . $data['email']);

        $memberId = Member::create($data);
        AuditLog::log('add_member', 'members', $memberId, null, $data);

        if (!empty($_FILES['photo']['name'])) {
            $result = FileUploader::upload($_FILES['photo'], 'photos', [
                'max_bytes' => FileUploader::PROFILE_MAX_BYTES,
                'thumbnail' => true,
            ]);
            if (!isset($result['errors'])) {
                Member::update($memberId, ['photo' => $result['file_path']]);
            }
        }

        $this->json(['success' => true, 'message' => 'Member created.', 'id' => $memberId]);
    }

    public function editForm(string $id): void
    {
        $member = Member::findById((int) $id);
        if (!$member) {
            http_response_code(404);
            return;
        }
        $countries = Database::fetchAll("SELECT * FROM countries WHERE is_active = 1 ORDER BY name");
        $membershipTypes = \App\Helpers\MembershipType::allActive();
        $this->view('members/edit', compact('member', 'countries', 'membershipTypes') + [
            'pageScript' => 'members-edit.js',
        ]);
    }

    public function update(string $id): void
    {
        if (!$this->validateCsrf()) {
            $this->rejectCsrf('member_update');
        }

        $memberId = (int) $id;
        $oldMember = Member::findById($memberId);
        if (!$oldMember) {
            $this->json(['success' => false, 'message' => 'Member not found.'], 404);
        }

        $emailResult = MemberEmail::parse((string) $this->input('email', ''));
        if (isset($emailResult['error'])) {
            $this->json(['success' => false, 'message' => $emailResult['error']], 422);
        }

        $nic = Security::sanitize($this->input('nic_number', ''));
        if ($nic !== '' && strcasecmp($nic, (string) ($oldMember['nic_number'] ?? '')) !== 0) {
            $nicCheck = \App\Helpers\ApplicationValidation::checkNic($nic);
            if (!empty($nicCheck['block'])) {
                $this->json(['success' => false, 'message' => $nicCheck['message_en'] ?? 'NIC already exists.'], 422);
            }
        }

        $data = [
            'full_name_tamil' => Security::sanitize($this->input('full_name_tamil', '')),
            'full_name_english' => Security::sanitize($this->input('full_name_english', '')),
            'gender' => $this->input('gender'),
            'date_of_birth' => $this->input('date_of_birth') ?: null,
            'nic_number' => Security::sanitize($this->input('nic_number', '')),
            'current_address' => Security::sanitize($this->input('current_address', '')),
            'permanent_address' => Security::sanitize($this->input('permanent_address', '')),
            'country_id' => (int) $this->input('country_id') ?: null,
            'mobile' => Security::sanitize($this->input('mobile', '')),
            'whatsapp' => Security::sanitize($this->input('whatsapp', '')),
            'email' => $emailResult['value'],
            'occupation' => Security::sanitize($this->input('occupation', '')),
            'company' => Security::sanitize($this->input('company', '')),
            'membership_type_id' => (int) $this->input('membership_type_id'),
            'status' => $this->input('status'),
        ];

        error_log('Member update id=' . $memberId . ' POST=' . json_encode($_POST) . ' email=' . $data['email']);

        $rows = Member::update($memberId, $data);
        error_log('Member update rows=' . $rows . ' id=' . $memberId);

        $saved = Member::findById($memberId);
        if (!$saved || empty($saved['email'])) {
            error_log('Member update verification failed for id=' . $memberId);
            $this->json(['success' => false, 'message' => 'Email could not be saved. Please try again.'], 500);
        }

        AuditLog::log('edit_member', 'members', $memberId, $oldMember, $data);
        AuditLog::log('profile_updated', 'members', $memberId, $oldMember, $data);

        if (!empty($_FILES['photo']['name'])) {
            $result = FileUploader::upload($_FILES['photo'], 'photos', [
                'max_bytes' => FileUploader::PROFILE_MAX_BYTES,
                'thumbnail' => true,
            ]);
            if (!isset($result['errors'])) {
                Member::update($memberId, ['photo' => $result['file_path']]);
            }
        }

        $emailNotice = null;
        $sent = Mailer::sendTemplate($saved['email'], 'profile_updated', [
            'full_name' => $saved['full_name_english'],
            'membership_number' => $saved['membership_number'],
            'email' => $saved['email'],
        ], $saved['full_name_english'], [
            'related_type' => 'members',
            'related_id' => $memberId,
        ]);

        if (!$sent) {
            $emailNotice = 'Profile saved, but confirmation email could not be sent: ' . (Mailer::getLastError() ?? 'unknown error');
            error_log('Member profile email failed id=' . $memberId . ' ' . (Mailer::getLastError() ?? ''));
        }

        $this->json([
            'success' => true,
            'message' => 'Member updated successfully.',
            'email' => $saved['email'],
            'email_notice' => $emailNotice,
        ]);
    }

    public function suspend(string $id): void
    {
        $this->changeStatus((int) $id, 'suspended', 'suspend_member');
    }

    public function activate(string $id): void
    {
        $this->changeStatus((int) $id, 'active', 'activate_member');
    }

    public function deactivate(string $id): void
    {
        $this->changeStatus((int) $id, 'expired', 'deactivate_member');
    }

    public function renew(string $id): void
    {
        if (!$this->validateCsrf()) {
            $this->json(['success' => false, 'message' => 'Invalid request.'], 403);
        }

        $member = Member::findById((int) $id);
        if (!$member) {
            $this->json(['success' => false, 'message' => 'Member not found.'], 404);
        }

        $type = \App\Helpers\MembershipType::findById((int) $member['membership_type_id'])
            ?: ['slug' => 'ordinary', 'duration_years' => 1];
        $newExpiry = \App\Helpers\MembershipType::extendExpiryDate($type, $member['membership_expiry_date'] ?? null);

        Member::update((int) $id, [
            'status' => 'active',
            'membership_expiry_date' => $newExpiry,
        ]);

        AuditLog::log('renew_member', 'members', (int) $id, null, ['membership_expiry_date' => $newExpiry]);

        $this->json(['success' => true, 'message' => 'Membership renewed until ' . $newExpiry, 'expiry' => $newExpiry]);
    }

    public function printProfile(string $id): void
    {
        $member = Member::findById((int) $id);
        if (!$member) {
            http_response_code(404);
            \App\Core\View::render('errors/404');
            return;
        }
        $documents = Document::getByMember((int) $id);
        $this->view('members/print', compact('member', 'documents'));
    }

    public function export(): void
    {
        $filters = [
            'search' => $this->input('search', ''),
            'status' => $this->input('status', ''),
            'country_id' => $this->input('country_id', ''),
            'membership_type_id' => $this->input('membership_type_id', ''),
            'batch' => $this->input('batch', ''),
        ];
        $members = Member::search($filters, 1, 5000)['data'];

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $headers = ['Membership No', 'Name', 'NIC', 'Mobile', 'Email', 'Status', 'Country', 'Batch', 'Type', 'Expiry'];
        $sheet->fromArray($headers, null, 'A1');
        $row = 2;
        foreach ($members as $m) {
            $sheet->fromArray([
                $m['membership_number'],
                $m['full_name_english'],
                $m['nic_number'] ?? '',
                $m['mobile'],
                $m['email'] ?? '',
                $m['status'],
                $m['country_name'] ?? '',
                $m['studied_to_year'] ?? '',
                $m['membership_type_name'] ?? '',
                $m['membership_expiry_date'] ?? '',
            ], null, 'A' . $row++);
        }

        AuditLog::log('export_members', 'members', null, null, ['count' => count($members)]);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="members_export.xlsx"');
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    private function changeStatus(int $id, string $status, string $action): void
    {
        if (!$this->validateCsrf()) {
            $this->json(['success' => false, 'message' => 'Invalid request.'], 403);
        }

        $member = Member::findById($id);
        if (!$member) {
            $this->json(['success' => false, 'message' => 'Member not found.'], 404);
        }

        Member::update($id, ['status' => $status]);
        AuditLog::log($action, 'members', $id, ['status' => $member['status']], ['status' => $status]);
        $this->json(['success' => true, 'message' => 'Member status updated to ' . $status . '.']);
    }

    private function isAjax(): bool
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
}
