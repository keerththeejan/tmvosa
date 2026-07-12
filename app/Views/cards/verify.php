<?php
use App\Core\View;

$pageTitle = 'Membership Verification';
$found = !empty($found);
$verified = !empty($verified);
$statusKey = $statusKey ?? 'not_found';
$number = $number ?? '';

$statusMeta = match ($statusKey) {
    'active' => [
        'badge_class' => 'bg-success',
        'badge_icon' => 'bi-check-circle-fill',
        'badge_text' => 'VERIFIED MEMBER',
        'status_class' => 'text-success',
        'status_text' => '✔ Active Membership',
    ],
    'expired' => [
        'badge_class' => 'bg-warning text-dark',
        'badge_icon' => 'bi-exclamation-triangle-fill',
        'badge_text' => 'MEMBERSHIP EXPIRED',
        'status_class' => 'text-warning',
        'status_text' => 'Membership Expired',
    ],
    'suspended' => [
        'badge_class' => 'bg-danger',
        'badge_icon' => 'bi-x-octagon-fill',
        'badge_text' => 'MEMBERSHIP SUSPENDED',
        'status_class' => 'text-danger',
        'status_text' => 'Membership Suspended',
    ],
    'not_found' => [
        'badge_class' => 'bg-danger',
        'badge_icon' => 'bi-x-circle-fill',
        'badge_text' => 'INVALID MEMBERSHIP',
        'status_class' => 'text-danger',
        'status_text' => 'Member Not Found',
    ],
    default => [
        'badge_class' => 'bg-secondary',
        'badge_icon' => 'bi-info-circle-fill',
        'badge_text' => 'MEMBERSHIP RECORD',
        'status_class' => 'text-secondary',
        'status_text' => ucfirst(str_replace('_', ' ', (string) $statusKey)),
    ],
};

$fmtDate = static function (?string $value, bool $withTime = false): string {
    if (!$value) {
        return '—';
    }
    $ts = strtotime($value);
    if ($ts === false) {
        return View::escape($value);
    }
    return $withTime ? date('d M Y H:i', $ts) : date('d M Y', $ts);
};

$display = static function (?string $value): string {
    $v = trim((string) $value);
    return $v !== '' ? View::escape($v) : '—';
};
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#1a5276">
    <title><?= View::escape($pageTitle) ?> - OSA Alumni</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Tamil:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --osa-primary: #1a5276;
            --osa-primary-dark: #154360;
        }
        body {
            font-family: 'Noto Sans Tamil', 'Segoe UI', system-ui, sans-serif;
            background: linear-gradient(160deg, #e8f1f8 0%, #f4f6f8 45%, #ffffff 100%);
            min-height: 100vh;
        }
        .verify-wrap { max-width: 640px; }
        .verify-brand {
            width: 72px; height: 72px;
            border-radius: 50%;
            background: var(--osa-primary);
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            box-shadow: 0 8px 20px rgba(26, 82, 118, 0.25);
        }
        .verify-card {
            border: 0;
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(26, 82, 118, 0.08);
        }
        .verify-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 700;
            letter-spacing: 0.03em;
            padding: 0.65rem 1rem;
            border-radius: 999px;
            font-size: 0.95rem;
        }
        .member-photo {
            width: 120px; height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #fff;
            box-shadow: 0 4px 14px rgba(0,0,0,0.12);
        }
        .member-photo-placeholder {
            width: 120px; height: 120px;
            border-radius: 50%;
            background: #d6e4ef;
            color: var(--osa-primary);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            font-weight: 700;
        }
        .detail-table th {
            width: 42%;
            font-weight: 600;
            color: #5a6a7a;
            background: #f8fafc;
        }
        .detail-table td, .detail-table th {
            vertical-align: middle;
            padding: 0.7rem 0.85rem;
        }
        .btn-osa {
            background: var(--osa-primary);
            border-color: var(--osa-primary);
            color: #fff;
            min-height: 44px;
        }
        .btn-osa:hover { background: var(--osa-primary-dark); border-color: var(--osa-primary-dark); color: #fff; }
        @media print {
            body { background: #fff; }
            .no-print { display: none !important; }
            .verify-card { box-shadow: none; border: 1px solid #dee2e6; }
        }
    </style>
</head>
<body>
<div class="container py-4 py-md-5 verify-wrap">
    <div class="text-center mb-4">
        <div class="verify-brand mb-3"><i class="bi bi-mortarboard-fill"></i></div>
        <h1 class="h4 mb-1 text-dark">OSA Alumni Membership Verification</h1>
        <p class="text-muted small mb-0">Kilinochchi / Thiruvaiyaru Maha Vidyalayam Old Students' Association</p>
    </div>

    <div class="card verify-card mb-3">
        <div class="card-body p-4 text-center">
            <div class="mb-3">
                <span class="verify-badge <?= View::escape($statusMeta['badge_class']) ?>">
                    <i class="bi <?= View::escape($statusMeta['badge_icon']) ?>"></i>
                    <?= $found && $verified ? '✔ ' : ($found ? '' : '✖ ') ?>
                    <?= View::escape($statusMeta['badge_text']) ?>
                </span>
            </div>

            <?php if ($found && $member): ?>
                <div class="mb-3">
                    <?php if (!empty($photoDataUri)): ?>
                        <img src="<?= View::escape($photoDataUri) ?>" alt="Member photo" class="member-photo">
                    <?php else: ?>
                        <div class="member-photo-placeholder mx-auto">
                            <?= strtoupper(substr((string) ($member['full_name_english'] ?? 'M'), 0, 1)) ?>
                        </div>
                    <?php endif; ?>
                </div>

                <h2 class="h5 mb-0"><?= $display($member['full_name_english'] ?? '') ?></h2>
                <?php if (!empty($member['full_name_tamil'])): ?>
                    <p class="text-muted mb-2"><?= $display($member['full_name_tamil']) ?></p>
                <?php endif; ?>
                <p class="fw-semibold text-primary mb-2"><?= $display($member['membership_number'] ?? $number) ?></p>
                <p class="mb-0 <?= View::escape($statusMeta['status_class']) ?> fw-semibold">
                    <?= View::escape($statusMeta['status_text']) ?>
                </p>
            <?php else: ?>
                <div class="text-danger mb-2" style="font-size:2.75rem"><i class="bi bi-x-circle-fill"></i></div>
                <h2 class="h5">Member Not Found</h2>
                <p class="text-muted mb-0">
                    Membership number <strong><?= View::escape($number) ?></strong> could not be verified.
                </p>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($found && $member): ?>
    <div class="card verify-card mb-3">
        <div class="card-header bg-white border-0 pt-3 pb-0">
            <h3 class="h6 mb-0 text-secondary"><i class="bi bi-person-vcard"></i> Member Details</h3>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table detail-table mb-0">
                    <tbody>
                        <tr>
                            <th>Full Name (English)</th>
                            <td><?= $display($member['full_name_english'] ?? '') ?></td>
                        </tr>
                        <tr>
                            <th>Full Name (Tamil)</th>
                            <td><?= $display($member['full_name_tamil'] ?? '') ?></td>
                        </tr>
                        <tr>
                            <th>Membership Number</th>
                            <td><?= $display($member['membership_number'] ?? '') ?></td>
                        </tr>
                        <tr>
                            <th>NIC</th>
                            <td><?= $display($member['nic_number'] ?? '') ?></td>
                        </tr>
                        <tr>
                            <th>Membership Type</th>
                            <td>
                                <?php if (!empty($membershipDisplay)): ?>
                                    <div><?= View::escape($membershipDisplay['title_en'] ?? '') ?></div>
                                    <div class="small text-muted"><?= View::escape($membershipDisplay['title_ta'] ?? '') ?></div>
                                <?php else: ?>
                                    <?= $display($member['membership_type_name'] ?? '') ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Application Number</th>
                            <td><?= $display($member['application_number'] ?? '') ?></td>
                        </tr>
                        <tr>
                            <th>Registered Date</th>
                            <td><?= $fmtDate($member['membership_start_date'] ?? $member['created_at'] ?? null) ?></td>
                        </tr>
                        <tr>
                            <th>Approval Date</th>
                            <td><?= $fmtDate($member['approved_at'] ?? null, true) ?></td>
                        </tr>
                        <tr>
                            <th>Expiry Date</th>
                            <td><?= $fmtDate($member['membership_expiry_date'] ?? null) ?></td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td class="<?= View::escape($statusMeta['status_class']) ?> fw-semibold">
                                <?= View::escape($statusMeta['status_text']) ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td><?= $display($member['email'] ?? '') ?></td>
                        </tr>
                        <tr>
                            <th>Phone</th>
                            <td><?= $display($member['mobile'] ?? '') ?></td>
                        </tr>
                        <tr>
                            <th>Batch</th>
                            <td><?= $display($member['studied_to_year'] ?? $member['batch'] ?? '') ?></td>
                        </tr>
                        <tr>
                            <th>Department / Stream</th>
                            <td><?= $display($member['grade_stream'] ?? '') ?></td>
                        </tr>
                        <tr>
                            <th>Membership Fee</th>
                            <td>
                                <?php if (isset($member['fee']) && $member['fee'] !== null && $member['fee'] !== ''): ?>
                                    Rs. <?= number_format((float) $member['fee'], 2) ?>
                                <?php else: ?>
                                    —
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>QR Generated Date</th>
                            <td><?= $fmtDate($member['qr_generated_at'] ?? null, true) ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="d-grid gap-2 no-print">
        <button type="button" class="btn btn-osa" onclick="window.print()">
            <i class="bi bi-printer"></i> Print Verification
        </button>
        <a class="btn btn-outline-secondary" href="tel:0778870135">
            <i class="bi bi-telephone"></i> Contact Alumni Office
        </a>
        <a class="btn btn-link text-decoration-none" href="mailto:tmvosa@vkitnet.info">tmvosa@vkitnet.info</a>
    </div>

    <p class="text-center text-muted small mt-4 mb-0">
        © <?= date('Y') ?> OSA Alumni · Official membership verification
    </p>
</div>
</body>
</html>
