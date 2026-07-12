<?php
use App\Core\View;

$pageTitle = 'Reports';
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
?>
<h5 class="mb-3"><i class="bi bi-bar-chart"></i> Reports</h5>
<p class="text-muted small mb-3">Charts live on the <a href="<?= $base ?>/dashboard">Dashboard</a>. Use the forms below for printable/exportable lists.</p>

<div class="row g-3">
    <div class="col-12 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white"><h6 class="mb-0">Members Report</h6></div>
            <div class="card-body">
                <form action="<?= $base ?>/reports/members" method="get" class="row g-2 align-items-end">
                    <div class="col-12">
                        <select name="period" class="form-select form-select-sm">
                            <option value="daily">Daily</option>
                            <option value="monthly" selected>Monthly</option>
                            <option value="yearly">Yearly</option>
                            <option value="all">All Time</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <select name="status" class="form-select form-select-sm">
                            <option value="active" selected>Active</option>
                            <option value="expired">Expired</option>
                            <option value="suspended">Suspended</option>
                            <option value="pending">Pending</option>
                            <option value="all">All Statuses</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <select name="format" class="form-select form-select-sm">
                            <option value="html">View</option>
                            <option value="pdf">PDF</option>
                            <option value="excel">Excel</option>
                            <option value="csv">CSV</option>
                        </select>
                    </div>
                    <div class="col-12"><button class="btn btn-primary btn-sm w-100" type="submit">Generate</button></div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white"><h6 class="mb-0">Payments / Income Report</h6></div>
            <div class="card-body">
                <form action="<?= $base ?>/reports/financial" method="get" class="row g-2 align-items-end">
                    <div class="col-12">
                        <select name="type" class="form-select form-select-sm">
                            <option value="collection">Payment Collection</option>
                            <option value="outstanding">Outstanding Payments</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <select name="period" class="form-select form-select-sm">
                            <option value="daily">Daily</option>
                            <option value="monthly" selected>Monthly</option>
                            <option value="yearly">Yearly</option>
                            <option value="all">All Time</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <select name="format" class="form-select form-select-sm">
                            <option value="html">View</option>
                            <option value="pdf">PDF</option>
                            <option value="excel">Excel</option>
                            <option value="csv">CSV</option>
                        </select>
                    </div>
                    <div class="col-12"><button class="btn btn-primary btn-sm w-100" type="submit">Generate</button></div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white"><h6 class="mb-0">Country / Batch / Occupation</h6></div>
            <div class="card-body">
                <form action="<?= $base ?>/reports/alumni" method="get" class="row g-2 align-items-end">
                    <div class="col-12">
                        <select name="group_by" class="form-select form-select-sm">
                            <option value="country">By Country</option>
                            <option value="batch">By Batch (Year)</option>
                            <option value="occupation">By Occupation</option>
                            <option value="gender">By Gender</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <select name="format" class="form-select form-select-sm">
                            <option value="html">View</option>
                            <option value="pdf">PDF</option>
                            <option value="excel">Excel</option>
                            <option value="csv">CSV</option>
                        </select>
                    </div>
                    <div class="col-12"><button class="btn btn-primary btn-sm w-100" type="submit">Generate</button></div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white"><h6 class="mb-0">Expired / Inactive Members</h6></div>
            <div class="card-body">
                <form action="<?= $base ?>/reports/members" method="get" class="row g-2">
                    <input type="hidden" name="period" value="all">
                    <input type="hidden" name="status" value="expired">
                    <div class="col-12">
                        <select name="format" class="form-select form-select-sm">
                            <option value="html">View</option>
                            <option value="pdf">PDF</option>
                            <option value="excel">Excel</option>
                            <option value="csv">CSV</option>
                        </select>
                    </div>
                    <div class="col-12"><button class="btn btn-primary btn-sm w-100" type="submit">Expired Members</button></div>
                </form>
                <form action="<?= $base ?>/reports/members" method="get" class="row g-2 mt-2">
                    <input type="hidden" name="period" value="all">
                    <input type="hidden" name="status" value="suspended">
                    <div class="col-12"><button class="btn btn-outline-secondary btn-sm w-100" type="submit">Suspended Members</button></div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><h6 class="mb-0">Audit Report</h6></div>
            <div class="card-body">
                <form action="<?= $base ?>/reports/audit" method="get" class="row g-2 align-items-end">
                    <div class="col-12 col-md-4">
                        <select name="format" class="form-select form-select-sm">
                            <option value="html">View</option>
                            <option value="pdf">PDF</option>
                            <option value="excel">Excel</option>
                            <option value="csv">CSV</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-4"><button class="btn btn-primary btn-sm w-100" type="submit">Generate Audit Report</button></div>
                    <div class="col-12 col-md-4"><a href="<?= $base ?>/admin/audit-logs" class="btn btn-outline-secondary btn-sm w-100">Open Audit Logs</a></div>
                </form>
            </div>
        </div>
    </div>
</div>
