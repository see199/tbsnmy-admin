<!DOCTYPE html>
<html lang="zh-Hant">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>华光杯 <?= html_escape($year) ?> 奖状下载 Certificate Download</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Serif+TC:wght@600;700&family=Noto+Sans+TC:wght@400;500;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
  :root { --ink:#1d2540; --paper:#f4f6fa; --lotus:#0d783b; --lotus-dark:#074e25; --gold:#c8962e; --gold-soft:#fbf0d4; --line:#d9deea; }
  body { margin:0; background:var(--paper); color:var(--ink); font-family:"Noto Sans TC","PingFang TC","Microsoft JhengHei",sans-serif; line-height:1.6; }
  .wrap { max-width:720px; margin:0 auto; padding:24px 16px 32px; }
  h1 { font-family:"Noto Serif TC","Songti TC",serif; font-weight:700; font-size:1.9rem; margin:0; }
  .sub { color:#5b6482; margin:0 0 20px; }
  .search { background:#fff; border:1px solid var(--line); border-radius:12px; padding:18px; }
  .search label { font-weight:500; }
  .search small { color:#5b6482; font-weight:400; }
  #nric { letter-spacing:.12em; font-variant-numeric:tabular-nums; }
  .btn-brand { background:var(--lotus); border-color:var(--lotus); color:#fff; font-weight:500; }
  .btn-brand:hover, .btn-brand:focus { background:var(--lotus-dark); border-color:var(--lotus-dark); color:#fff; }
  .btn-brand:focus-visible, #nric:focus-visible { outline:3px solid var(--gold); outline-offset:2px; }
  #results { margin-top:24px; }
  .result { background:#fff; border:1px solid var(--line); border-left:5px solid var(--gold); border-radius:4px 12px 12px 4px; padding:18px; margin-bottom:20px; }
  .result-head { display:flex; flex-wrap:wrap; align-items:baseline; gap:6px 14px; margin-bottom:6px; }
  .result-name { font-family:"Noto Serif TC","Songti TC",serif; font-size:1.5rem; font-weight:700; margin:0; }
  .result-group { color:#5b6482; }
  .cert { padding-top:16px; margin-top:16px; border-top:1px solid var(--line); }
  .cert:first-of-type { border-top:0; margin-top:8px; padding-top:0; }
  .cert-title { display:flex; flex-wrap:wrap; align-items:baseline; gap:2px 10px; margin-bottom:10px; font-weight:700; }
  .cert-title small { font-weight:400; color:#5b6482; }
  .level { background:var(--gold-soft); color:#7a5509; border-radius:999px; padding:1px 12px; font-size:.9rem; font-weight:500; }
  .cert-frame { display:block; width:100%; aspect-ratio:1.414/1; min-height:240px; border:1px solid var(--line); border-radius:8px; background:#eef0f5; margin-bottom:12px; }
  @media (prefers-reduced-motion:no-preference) { .result { animation:in .25s ease-out; } @keyframes in { from { opacity:0; } to { opacity:1; } } }
</style>
</head>
<body>
<main class="wrap">
  <h1>华光杯 <?= html_escape($year) ?> 奖状下载</h1>
  <p class="sub">Lotus Light Cup <?= html_escape($year) ?> – Certificate Download</p>

  <form id="lookupForm" class="search" autocomplete="off" novalidate>
    <label for="nric" class="form-label">
      请输入参赛者的身份证号码（12位数字，不需要「-」）<br>
      <small>Enter the contestant’s NRIC (12 digits, without “-”)</small>
    </label>
    <div class="input-group input-group-lg">
      <input id="nric" name="nric" class="form-control" type="text" inputmode="numeric" pattern="[0-9]*" maxlength="12" placeholder="XXXXXXXXXXXX" aria-describedby="msg">
      <button id="btnSearch" class="btn btn-brand" type="submit">查询 Search</button>
    </div>
    <div id="msg" class="mt-2" role="alert" aria-live="polite"></div>
    <div class="form-text mt-2">家中有多位参赛者？可以连续查询，结果会保留在下方。<br>More than one child? Search again — earlier results stay below.</div>
  </form>

  <div id="results"></div>
</main>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(function () {
  var LOOKUP_URL = <?= json_encode(site_url('certificate/lookup')) ?>;
  var $nric = $('#nric'), $msg = $('#msg'), $btn = $('#btnSearch'), $res = $('#results');

  // Digits only: block other keys, and clean pasted text (dashes, spaces).
  $nric.on('keypress', function (e) {
    if (e.ctrlKey || e.metaKey || e.which < 32) return;
    if (e.which < 48 || e.which > 57) e.preventDefault();
  });
  $nric.on('input', function () {
    var v = this.value.replace(/\D/g, '').slice(0, 12);
    if (v !== this.value) this.value = v;
  });

  function showMsg(zh, en, type) {
    var $a = $('<div class="alert py-2 mb-0">').addClass('alert-' + type)
      .append($('<div>').text(zh)).append($('<small>').text(en));
    $msg.empty().append($a);
    fit();
  }

  function card(r) {
    var $c = $('<section class="result">');
    $c.append($('<header class="result-head">')
      .append($('<h2 class="result-name">').text(r.name))
      .append($('<span class="result-group">').text(r.group_zh + ' ' + r.group_en)));
    $.each(r.certs, function (_, c) {
      var $cert = $('<div class="cert">');
      var $t = $('<div class="cert-title">').append($('<span>').text(c.zh)).append($('<small>').text(c.en));
      if (c.level_zh) $t.append($('<span class="level">').text(c.level_zh + (c.level_en ? ' ' + c.level_en : '')));
      $cert.append($t);
      if (c.preview) {
        $cert.append($('<iframe class="cert-frame" allow="fullscreen" loading="lazy">').attr({ src: c.preview, title: c.zh }));
      }
      $cert.append($('<a class="btn btn-brand" target="_blank" rel="noopener">').attr('href', c.download).text('下载 PDF Download'));
      $c.append($cert);
    });
    return $c;
  }

  function handle(r, nric) {
    if (!r.ok) { showMsg(r.zh, r.en, r.code === 'not_found' ? 'danger' : 'warning'); return; }
    $res.find('[data-nric="' + nric + '"]').remove();          // same child searched twice
    $res.prepend(card(r).attr('data-nric', nric));
    $nric.val('').trigger('focus');
    fit();
  }

  $('#lookupForm').on('submit', function (e) {
    e.preventDefault();
    $msg.empty();
    var nric = $nric.val();
    if (nric.length !== 12) { showMsg('请输入完整12位数字。', 'Please enter all 12 digits.', 'warning'); return; }
    $btn.prop('disabled', true);
    $.ajax({ url: LOOKUP_URL, method: 'POST', data: { nric: nric }, dataType: 'json' })
      .done(function (r) { handle(r, nric); })
      .fail(function (xhr) {
        handle(xhr.responseJSON || { ok: false, zh: '系统暂时无法使用，请稍后再试。', en: 'Service unavailable. Please try again later.' }, nric);
      })
      .always(function () { $btn.prop('disabled', false); });
  });

  // Tell the parent page (Sitejet) how tall we are, so the iframe has no inner scrollbar.
  function fit() {
    if (window.parent === window) return;
    var h = Math.ceil(document.body.getBoundingClientRect().height);
    window.parent.postMessage({ type: 'huaguang-cert-height', height: h }, '*');
  }
  window.addEventListener('load', fit);
  window.addEventListener('resize', fit);
  if (window.ResizeObserver) new ResizeObserver(fit).observe(document.body);
});
</script>
</body>
</html>
