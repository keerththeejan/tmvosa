<?php
use App\Core\View;

$pageTitle = 'Reports';
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');
?>
<div class="mb-3"><?php View::heading('reports', 'h5', 'bar-chart'); ?></div>

<div class="row g-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header bilingual-text bilingual-block"><?php View::text('member_reports', 'h6', true, 'mb-0'); ?></div>
            <div class="card-body">
                <form action="<?= $base ?>/reports/members" method="get" class="row g-2 align-items-end">
                    <div class="col-6">
                        <select name="period" class="form-select form-select-sm">
                            <?php foreach (['daily', 'monthly', 'yearly'] as $p): $l = \App\Helpers\Lang::ui($p); ?>
                            <option value="<?= $p ?>" <?= $p === 'monthly' ? 'selected' : '' ?>><?= View::escape($l['ta']) ?> / <?= View::escape($l['en']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-6">
                        <select name="format" class="form-select form-select-sm">
                            <option value="html"><?= View::escape(\App\Helpers\Lang::ui('view')['ta']) ?> / <?= View::escape(\App\Helpers\Lang::ui('view')['en']) ?></option>
                            <option value="pdf">PDF</option>
                            <option value="excel">Excel</option>
                            <option value="csv">CSV</option>
                        </select>
                    </div>
                    <div class="col-12"><button class="btn btn-primary btn-sm w-100 bilingual-btn" type="submit">
                        <span class="label-ta"><?= View::escape(\App\Helpers\Lang::ui('generate')['ta']) ?></span>
                        <span class="label-en"><?= View::escape(\App\Helpers\Lang::ui('generate')['en']) ?></span>
                    </button></div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card">
            <div class="card-header bilingual-text bilingual-block"><?php View::text('financial_reports', 'h6', true, 'mb-0'); ?></div>
            <div class="card-body">
                <form action="<?= $base ?>/reports/financial" method="get" class="row g-2 align-items-end">
                    <div class="col-6">
                        <select name="type" class="form-select form-select-sm">
                            <option value="collection"><?= View::escape(\App\Helpers\Lang::ui('collection')['ta']) ?> / <?= View::escape(\App\Helpers\Lang::ui('collection')['en']) ?></option>
                            <option value="outstanding"><?= View::escape(\App\Helpers\Lang::ui('outstanding')['ta']) ?> / <?= View::escape(\App\Helpers\Lang::ui('outstanding')['en']) ?></option>
                        </select>
                    </div>
                    <div class="col-6">
                        <select name="format" class="form-select form-select-sm">
                            <option value="html"><?= View::escape(\App\Helpers\Lang::ui('view')['ta']) ?> / View</option>
                            <option value="pdf">PDF</option>
                            <option value="excel">Excel</option>
                            <option value="csv">CSV</option>
                        </select>
                    </div>
                    <div class="col-12"><button class="btn btn-primary btn-sm w-100 bilingual-btn" type="submit">
                        <span class="label-ta"><?= View::escape(\App\Helpers\Lang::ui('generate')['ta']) ?></span>
                        <span class="label-en"><?= View::escape(\App\Helpers\Lang::ui('generate')['en']) ?></span>
                    </button></div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card">
            <div class="card-header bilingual-text bilingual-block"><?php View::text('alumni_reports', 'h6', true, 'mb-0'); ?></div>
            <div class="card-body">
                <form action="<?= $base ?>/reports/alumni" method="get" class="row g-2 align-items-end">
                    <div class="col-6">
                        <select name="group_by" class="form-select form-select-sm">
                            <?php foreach (['country_wise' => 'country', 'batch_wise' => 'batch', 'occupation_wise' => 'occupation'] as $key => $val):
                                $l = \App\Helpers\Lang::ui($key);
                            ?>
                            <option value="<?= $val ?>"><?= View::escape($l['ta']) ?> / <?= View::escape($l['en']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-6">
                        <select name="format" class="form-select form-select-sm">
                            <option value="html"><?= View::escape(\App\Helpers\Lang::ui('view')['ta']) ?> / View</option>
                            <option value="pdf">PDF</option>
                            <option value="excel">Excel</option>
                            <option value="csv">CSV</option>
                        </select>
                    </div>
                    <div class="col-12"><button class="btn btn-primary btn-sm w-100 bilingual-btn" type="submit">
                        <span class="label-ta"><?= View::escape(\App\Helpers\Lang::ui('generate')['ta']) ?></span>
                        <span class="label-en"><?= View::escape(\App\Helpers\Lang::ui('generate')['en']) ?></span>
                    </button></div>
                </form>
            </div>
        </div>
    </div>
</div>
