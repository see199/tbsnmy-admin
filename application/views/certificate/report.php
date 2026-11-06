<!DOCTYPE html>
<html lang="zh-Hant">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>獎狀查詢／下載紀錄 <?= html_escape($year) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container-fluid py-4">
  <h1 class="h4 mb-3">華光杯 <?= html_escape($year) ?> 獎狀查詢／下載紀錄</h1>

  <?php
    $q = function ($o) use ($year, $status, $group) {
        return '?' . http_build_query(array_merge(array('year' => $year, 'status' => $status, 'group' => $group), $o));
    };
    $btn = function ($on) { return 'btn btn-sm ' . ($on ? 'btn-dark' : 'btn-outline-dark'); };
  ?>
  <div class="d-flex flex-wrap gap-2 mb-3">
    <a class="<?= $btn($status === '') ?>"            href="<?= $q(['status' => '']) ?>">全部 All</a>
    <a class="<?= $btn($status === 'never') ?>"       href="<?= $q(['status' => 'never']) ?>">從未查詢 Never searched</a>
    <a class="<?= $btn($status === 'no_download') ?>" href="<?= $q(['status' => 'no_download']) ?>">查詢但未下載 Searched, no download</a>
    <span class="vr"></span>
    <a class="<?= $btn($group === '') ?>"  href="<?= $q(['group' => '']) ?>">A+B</a>
    <a class="<?= $btn($group === 'A') ?>" href="<?= $q(['group' => 'A']) ?>">A 組</a>
    <a class="<?= $btn($group === 'B') ?>" href="<?= $q(['group' => 'B']) ?>">B 組</a>
    <a class="btn btn-sm btn-success ms-auto" href="<?= $q(['export' => 1]) ?>">匯出 CSV</a>
  </div>

  <p class="text-muted small"><?= count($rows) ?> 位參賽者</p>
  <div class="table-responsive bg-white border rounded">
    <table class="table table-sm table-hover align-middle mb-0">
      <thead class="table-light">
        <tr><th>組別</th><th>姓名</th><th>NRIC</th><th>獎項</th><th class="text-end">查詢次數</th><th>最後查詢</th><th class="text-end">入圍獎狀下載</th><th class="text-end">參與獎狀下載</th></tr>
      </thead>
      <tbody>
      <?php foreach ($rows as $r): ?>
        <tr>
          <td><?= html_escape($r['group_code']) ?></td>
          <td><?= html_escape($r['name']) ?></td>
          <td class="font-monospace"><?= html_escape($r['nric']) ?></td>
          <td><?= html_escape(!empty($r['award_level']) ? $r['award_level'] : '—') ?></td>
          <td class="text-end"><?= (int) $r['searches'] ?></td>
          <td><?= html_escape(!empty($r['last_search']) ? $r['last_search'] : '—') ?></td>
          <td class="text-end"><?= $r['award_level'] ? (int) $r['award_dl'] : '—' ?></td>
          <td class="text-end"><?= (int) $r['part_dl'] ?></td>
        </tr>
      <?php endforeach; ?>
      <?php if (!$rows): ?><tr><td colspan="8" class="text-center text-muted py-4">沒有符合的資料</td></tr><?php endif; ?>
      </tbody>
    </table>
  </div>

  <h2 class="h6 mt-4">查無資料的 NRIC（可能是輸入錯誤，或名單漏了）</h2>
  <div class="table-responsive bg-white border rounded" style="max-width:520px">
    <table class="table table-sm mb-0">
      <thead class="table-light"><tr><th>NRIC</th><th class="text-end">次數</th><th>最後嘗試</th></tr></thead>
      <tbody>
      <?php foreach ($misses as $m): ?>
        <tr><td class="font-monospace"><?= html_escape($m['nric']) ?></td><td class="text-end"><?= (int) $m['tries'] ?></td><td><?= html_escape($m['last_try']) ?></td></tr>
      <?php endforeach; ?>
      <?php if (!$misses): ?><tr><td colspan="3" class="text-center text-muted py-3">沒有紀錄</td></tr><?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
</body>
</html>
