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
        $membershipTypes = Database::fetchAll("SELECT * FROM membership_types WHERE is_active = 1");

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
            return;
        }
        $documents = Document::getByMember((int) $id);
        $this->view('members/show', compact('member', 'documents'));
    }

    public function createForm(): void
    {
        $countries = Database::fetchAll("SELECT * FROM countries WHERE is_active = 1 ORDER BY name");
        $membershipTypes = Database::fetchAll("SELECT * FROM membership_types WHERE is_active = 1");
        $this->view('members/create', compact('countries', 'membershipTypes'));
    }

    public function store(): void
    {
        if (!$this->validateCsrf()) {
            $this->json(['success' => false, 'message' => 'Invalid request.'], 403);
        }

        $membershipNumber = $this->input('membership_number') ?: NumberGenerator::membershipNumber();
        $typeId = (int) $this->input('membership_type_id');
        $type = Database::fetch("SELECT duration_years FROM membership_types WHERE id = ?", [$typeId]);
        $durationYears = $type['duration_years'] ?? 1;

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
            'email' => filter_var($this->input('email', ''), FILTER_SANITIZE_EMAIL),
            'studied_from_year' => $this->input('studied_from_year'),
            'studied_to_year' => $this->input('studied_to_year'),
            'grade_stream' => Security::sanitize($this->input('grade_stream', '')),
            'teacher_name' => Security::sanitize($this->input('teacher_name', '')),
            'occupation' => Security::sanitize($this->input('occupation', '')),
            'company' => Security::sanitize($this->input('company', '')),
            'membership_type_id' => $typeId,
            'status' => 'active',
            'membership_start_date' => date('Y-m-d'),
            'membership_expiry_date' => date('Y-m-d', strtotime("+{$durationYears} years")),
            'created_by' => Auth::id(),
        ];

        $memberId = Member::create($data);
        AuditLog::log('add_member', 'members', $memberId, null, $data);

        if (!empty($_FILES['photo']['name'])) {
            $result = FileUploader::upload($_FILES['photo'], 'photos');
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
        $membershipTypes = Database::fetchAll("SELECT * FROM membership_types WHERE is_active = 1");
        $this->view('members/edit', compact('member', 'countries', 'membershipTypes'));
    }

    public function update(string $id): void
    {
        if (!$this->validateCsrf()) {
            $this->json(['success' => false, 'message' => 'Invalid request.'], 403);
        }

        $oldMember = Member::findById((int) $id);
        if (!$oldMember) {
            $this->json(['success' => false, 'message' => 'Member not found.'], 404);
        }

        $data = [
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
            'email' => filter_var($this->input('email', ''), FILTER_SANITIZE_EMAIL),
            'occupation' => Security::sanitize($this->input('occupation', '')),
            'company' => Security::sanitize($this->input('company', '')),
            'membership_type_id' => (int) $this->input('membership_type_id'),
            'status' => $this->input('status'),
        ];

        Member::update((int) $id, $data);
        AuditLog::log('edit_member', 'members', (int) $id, $oldMember, $data);

        if (!empty($_FILES['photo']['name'])) {
            $result = FileUploader::upload($_FILES['photo'], 'photos');
            if (!isset($result['errors'])) {
                Member::update((int) $id, ['photo' => $result['file_path']]);
            }
        }

        $this->json(['success' => true, 'message' => 'Member updated.']);
    }

    private function isAjax(): bool
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
}
