<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Models\AuditLog;
use App\Helpers\PdfGenerator;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportController extends Controller
{
    public function index(): void
    {
        $this->view('reports/index');
    }

    public function members(): void
    {
        $period = $this->input('period', 'monthly');
        $format = $this->input('format', 'html');
        $status = $this->input('status', 'active');

        $where = ['1=1'];
        $params = [];

        if ($status !== 'all' && $status !== '') {
            $where[] = 'm.status = ?';
            $params[] = $status;
        }

        if ($period !== 'all') {
            $where[] = match ($period) {
                'daily' => 'DATE(m.created_at) = CURDATE()',
                'yearly' => 'YEAR(m.created_at) = YEAR(CURDATE())',
                default => 'MONTH(m.created_at) = MONTH(CURDATE()) AND YEAR(m.created_at) = YEAR(CURDATE())',
            };
        }

        $whereClause = implode(' AND ', $where);
        $data = Database::fetchAll(
            "SELECT m.membership_number, m.full_name_english, m.mobile, m.email, m.status,
                    mt.name as membership_type, c.name as country, m.created_at
             FROM members m
             LEFT JOIN membership_types mt ON m.membership_type_id = mt.id
             LEFT JOIN countries c ON m.country_id = c.id
             WHERE {$whereClause}
             ORDER BY m.created_at DESC",
            $params
        );

        AuditLog::log('export_report', 'reports', null, null, ['report' => 'members', 'format' => $format]);
        $this->export($data, 'Member Report', $format, [
            'Membership No', 'Name', 'Mobile', 'Email', 'Status', 'Type', 'Country', 'Joined',
        ]);
    }

    public function financial(): void
    {
        $period = $this->input('period', 'monthly');
        $format = $this->input('format', 'html');
        $type = $this->input('type', 'collection');

        if ($type === 'outstanding') {
            $data = Database::fetchAll(
                "SELECT m.membership_number, m.full_name_english, mt.fee, m.status, m.membership_expiry_date
                 FROM members m
                 JOIN membership_types mt ON m.membership_type_id = mt.id
                 WHERE m.status IN ('expired','suspended')
                    OR (m.membership_expiry_date IS NOT NULL AND m.membership_expiry_date < CURDATE())
                 ORDER BY m.membership_expiry_date ASC"
            );
            $headers = ['Membership No', 'Name', 'Fee', 'Status', 'Expiry'];
        } else {
            $dateFilter = match ($period) {
                'daily' => "DATE(p.payment_date) = CURDATE()",
                'yearly' => "YEAR(p.payment_date) = YEAR(CURDATE())",
                'all' => '1=1',
                default => "MONTH(p.payment_date) = MONTH(CURDATE()) AND YEAR(p.payment_date) = YEAR(CURDATE())",
            };
            $data = Database::fetchAll(
                "SELECT pr.receipt_number, m.full_name_english, m.membership_number, p.amount, p.payment_method, p.payment_date, p.status
                 FROM payments p
                 JOIN members m ON p.member_id = m.id
                 LEFT JOIN payment_receipts pr ON pr.payment_id = p.id
                 WHERE {$dateFilter}
                 ORDER BY p.payment_date DESC"
            );
            $headers = ['Receipt No', 'Name', 'Membership No', 'Amount', 'Method', 'Date', 'Status'];
        }

        AuditLog::log('export_report', 'reports', null, null, ['report' => 'financial', 'format' => $format]);
        $this->export($data, 'Financial Report', $format, $headers);
    }

    public function alumni(): void
    {
        $groupBy = $this->input('group_by', 'country');
        $format = $this->input('format', 'html');

        $query = match ($groupBy) {
            'batch' => "SELECT m.studied_to_year as label, COUNT(*) as count FROM members m WHERE m.status = 'active' GROUP BY m.studied_to_year ORDER BY label DESC",
            'occupation' => "SELECT m.occupation as label, COUNT(*) as count FROM members m WHERE m.status = 'active' AND m.occupation IS NOT NULL AND m.occupation != '' GROUP BY m.occupation ORDER BY count DESC LIMIT 20",
            'gender' => "SELECT COALESCE(NULLIF(m.gender,''),'unspecified') as label, COUNT(*) as count FROM members m GROUP BY COALESCE(NULLIF(m.gender,''),'unspecified') ORDER BY count DESC",
            default => "SELECT c.name as label, COUNT(*) as count FROM members m JOIN countries c ON m.country_id = c.id WHERE m.status = 'active' GROUP BY c.name ORDER BY count DESC",
        };

        $data = Database::fetchAll($query);
        $headers = [ucfirst($groupBy), 'Count'];
        AuditLog::log('export_report', 'reports', null, null, ['report' => 'alumni', 'group_by' => $groupBy]);
        $this->export($data, 'Alumni Report', $format, $headers);
    }

    public function audit(): void
    {
        $format = $this->input('format', 'html');
        $data = Database::fetchAll(
            "SELECT al.created_at, u.full_name as user_name, al.action, al.entity_type, al.entity_id, al.ip_address
             FROM audit_logs al
             LEFT JOIN users u ON u.id = al.user_id
             ORDER BY al.created_at DESC
             LIMIT 1000"
        );
        AuditLog::log('export_report', 'reports', null, null, ['report' => 'audit']);
        $this->export($data, 'Audit Report', $format, [
            'Time', 'User', 'Action', 'Entity', 'Entity ID', 'IP',
        ]);
    }

    private function export(array $data, string $title, string $format, array $headers): void
    {
        if ($format === 'csv') {
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="' . strtolower(str_replace(' ', '_', $title)) . '.csv"');
            $out = fopen('php://output', 'w');
            fputcsv($out, $headers);
            foreach ($data as $row) {
                fputcsv($out, array_values($row));
            }
            fclose($out);
            exit;
        }

        if ($format === 'excel') {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle(substr($title, 0, 31));
            $col = 'A';
            foreach ($headers as $header) {
                $sheet->setCellValue($col . '1', $header);
                $col++;
            }
            $rowNum = 2;
            foreach ($data as $row) {
                $col = 'A';
                foreach ($row as $value) {
                    $sheet->setCellValue($col . $rowNum, $value);
                    $col++;
                }
                $rowNum++;
            }
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="' . strtolower(str_replace(' ', '_', $title)) . '.xlsx"');
            (new Xlsx($spreadsheet))->save('php://output');
            exit;
        }

        if ($format === 'pdf') {
            $html = '<h2>' . htmlspecialchars($title) . '</h2><table border="1" cellpadding="5" style="width:100%;border-collapse:collapse;font-size:12px;">';
            $html .= '<tr>' . implode('', array_map(fn($h) => '<th>' . htmlspecialchars($h) . '</th>', $headers)) . '</tr>';
            foreach ($data as $row) {
                $html .= '<tr>' . implode('', array_map(fn($v) => '<td>' . htmlspecialchars((string) $v) . '</td>', $row)) . '</tr>';
            }
            $html .= '</table>';
            $filename = strtolower(str_replace(' ', '_', $title)) . '.pdf';
            $path = PdfGenerator::generateFromHtml($html, $filename, 'reports');
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            readfile(\App\Core\App::config('app.upload_path') . '/' . $path);
            exit;
        }

        $this->view('reports/result', ['title' => $title, 'headers' => $headers, 'data' => $data]);
    }
}
