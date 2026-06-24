<?php
use App\Core\View;

$pageTitle = 'Reports';
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
?>
<h5 class="mb-3"><i class="bi bi-bar-chart"></i> Reports</h5>

<div class="row g-3">
    <div class="col-12 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white"><h6 class="mb-0">Membership Report</h6></div>
            <div class="card-body">
                <form action="<?= $base ?>/reports/members" method="get" class="row g-2 align-items-end">
                    <div class="col-12">
                        <select name="period" class="form-select form-select-sm">
                            <option value="daily">Daily</option>
                            <option value="monthly" selected>Monthly</option>
                            <option value="yearly">Yearly</option>
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
            <div class="card-header bg-white"><h6 class="mb-0">Payment Report</h6></div>
            <div class="card-body">
                <form action="<?= $base ?>/reports/financial" method="get" class="row g-2 align-items-end">
                    <div class="col-12">
                        <select name="type" class="form-select form-select-sm">
                            <option value="collection">Payment Collection</option>
                            <option value="outstanding">Outstanding Payments</option>
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
            <div class="card-header bg-white"><h6 class="mb-0">Country Wise Report</h6></div>
            <div class="card-body">
                <form action="<?= $base ?>/reports/alumni" method="get" class="row g-2 align-items-end">
                    <input type="hidden" name="group_by" value="country">
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
            <div class="card-header bg-white"><h6 class="mb-0">Membership Expiry Report</h6></div>
            <div class="card-body">
                <form action="<?= $base ?>/reports/alumni" method="get" class="row g-2 align-items-end">
                    <input type="hidden" name="group_by" value="batch">
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
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><h6 class="mb-0">Revenue Report</h6></div>
            <div class="card-body">
                <form action="<?= $base ?>/reports/financial" method="get" class="row g-2 align-items-end">
                    <input type="hidden" name="type" value="collection">
                    <div class="col-md-4">
                        <select name="format" class="form-select form-select-sm">
                            <option value="html">View</option>
                            <option value="pdf">PDF</option>
                            <option value="excel">Excel</option>
                            <option value="csv">CSV</option>
                        </select>
                    </div>
                    <div class="col-md-4"><button class="btn btn-primary btn-sm w-100" type="submit">Generate Revenue Report</button></div>
                </form>
            </div>
        </div>
    </div>
</div>
