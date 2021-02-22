<div id="page-wrapper">
  <div class="row">&nbsp;</div>
  <div class="row">
    <div class="col-lg-10 col-lg-offset-1">

      <ul class="nav nav-tabs" id="myTab" role="tablist">
        <?php foreach($gstatus as $status => $desc): ?>
        <li class="nav-item"><a class="nav-link" id="s<?= $status;?>-tab" data-toggle="tab" href="#s<?= $status;?>" role="tab" aria-controls="s<?= $status;?>" aria-selected="true"><?= $status;?> <small>[<?= isset($gsheet[$status]) ? count($gsheet[$status]) -1 : "0";?>]</small></a></li>
        <?php endforeach; ?>
      </ul>

      <div class="tab-content" id="myTabContent">
        <?php foreach($gstatus as $status => $desc): ?>
        <div class="tab-pane fade" id="s<?= $status;?>" role="tabpanel" aria-labelledby="s<?= $status;?>-tab"><h2>(<?= $status . " - " . $desc;?>)</h2>
          <?php if(isset($gsheet[$status]['table-tpl'])):?>
            <?= $gsheet[$status]['table-tpl']; ?>
          <?php else:?>
            - 暫無資料 -
          <?php endif;?>
        </div>
        <?php endforeach; ?>
      </div>

    </div>
  </div>
</div>

