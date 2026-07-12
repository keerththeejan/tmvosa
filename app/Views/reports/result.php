<?php $pageTitle = $title; $base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/'); ?>
<div class="card"><div class="card-header d-flex flex-wrap justify-content-between gap-2"><h6 class="mb-0"><?= \App\Core\View::escape($title) ?></h6>
<a href="javascript:history.back()" class="btn btn-sm btn-outline-secondary">Back</a></div>
<div class="card-body p-0">
    <div class="table-responsive d-none d-md-block">
        <table class="table table-striped mb-0"><thead><tr><?php foreach ($headers as $h): ?><th><?= \App\Core\View::escape($h) ?></th><?php endforeach; ?></tr></thead>
        <tbody><?php foreach ($data as $row): ?><tr><?php foreach ($row as $v): ?><td><?= \App\Core\View::escape((string)$v) ?></td><?php endforeach; ?></tr><?php endforeach; ?></tbody></table>
    </div>
    <div class="d-md-none member-cards p-3">
        <?php foreach ($data as $row): ?>
        <div class="member-card"><div class="card-body-content"><?php $i=0; foreach ($row as $v): ?><div><small class="text-muted"><?= \App\Core\View::escape($headers[$i++] ?? '') ?>:</small> <strong><?= \App\Core\View::escape((string)$v) ?></strong></div><?php endforeach; ?></div></div>
        <?php endforeach; ?>
    </div>
</div></div>
