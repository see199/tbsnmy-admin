<script type="text/javascript">
$(document).ready(function() {
  $('#snap').click(function() {
    $.ajax({
      url: '<?= base_url(); ?>wenxuan/lists/snapshot_list',
      type: 'GET',
      success: function(result) {
        location.reload();
      }
    });
  });
});
</script>

<div id="page-wrapper">
    <div class="row">&nbsp;</div>

    <div class="row">
        <div class="col-lg-6 col-lg-offset-3">
            <div class="panel panel-default">
              <div class="panel-heading text-center"><h1>馬密總文宣系統</h1></div>
              <div class="panel-body text-center">
                <img src="http://admin.tbsn.my/asset/img/tbsn-my-logo.jpg" />

                <hr />
                
                <table class="table table-bordered">
                  <thead>
                    <tr class='warning'><td colspan=9><b>刊物統計</b></td></tr>
                    <tr class="info">
                        <td rowspan=2><b></b></td>
                        <td colspan=3><b>真佛報</b></td>
                        <td colspan=3><b>燃燈</b></td>
                        <td rowspan=2><b>文集</b></td>
                        <td rowspan=2><b>最後更新</b></td>
                    </tr>
                    <tr class="info">
                        <td><b>贈送</b></td>
                        <td><b>訂閱</b></td>
                        <td><b>總數</b></td>
                        <td><b>贈送</b></td>
                        <td><b>訂閱</b></td>
                        <td><b>總數</b></td>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td><b>道場 (<?= $wenxuan_snapshot['subscriber']['chapter']['total_subscriber']; ?>間)</b></td>
                      <td><?= $wenxuan_snapshot['subscriber']['chapter']['tbnews_free']; ?></td>
                      <td><?= $wenxuan_snapshot['subscriber']['chapter']['tbnews_paid']; ?></td>
                      <td><?= $wenxuan_snapshot['subscriber']['chapter']['tbnews_free']+$wenxuan_snapshot['subscriber']['chapter']['tbnews_paid']; ?></td>
                      <td><?= $wenxuan_snapshot['subscriber']['chapter']['randeng_free']; ?></td>
                      <td><?= $wenxuan_snapshot['subscriber']['chapter']['randeng_paid']; ?></td>
                      <td><?= $wenxuan_snapshot['subscriber']['chapter']['randeng_paid']+$wenxuan_snapshot['subscriber']['chapter']['randeng_free']; ?></td>
                      <td><?= $wenxuan_snapshot['subscriber']['chapter']['gmbook']; ?></td>
                      <td><?= $wenxuan_snapshot['subscriber']['chapter']['update_date']; ?></td>
                    </tr><tr>
                      <td><b>個人 (<?= $wenxuan_snapshot['subscriber']['contact']['total_subscriber']; ?>人)</b></td>
                      <td><?= $wenxuan_snapshot['subscriber']['contact']['tbnews_free']; ?></td>
                      <td><?= $wenxuan_snapshot['subscriber']['contact']['tbnews_paid']; ?></td>
                      <td><?= $wenxuan_snapshot['subscriber']['contact']['tbnews_free']+$wenxuan_snapshot['subscriber']['contact']['tbnews_paid']; ?></td>
                      <td><?= $wenxuan_snapshot['subscriber']['contact']['randeng_free']; ?></td>
                      <td><?= $wenxuan_snapshot['subscriber']['contact']['randeng_paid']; ?></td>
                      <td><?= $wenxuan_snapshot['subscriber']['contact']['randeng_free']+$wenxuan_snapshot['subscriber']['contact']['randeng_paid']; ?></td>
                      <td>- N/A -</td>
                      <td><?= $wenxuan_snapshot['subscriber']['contact']['update_date']; ?></td>
                    </tr><tr>
                      <td><b>總數</b></td>
                      <td><b><?= $wenxuan_snapshot['subscriber']['chapter']['tbnews_free']+$wenxuan_snapshot['subscriber']['contact']['tbnews_free']; ?></b></td>
                      <td><b><?= $wenxuan_snapshot['subscriber']['chapter']['tbnews_paid']+$wenxuan_snapshot['subscriber']['contact']['tbnews_paid']; ?></b></td>
                      <td><b><?= $wenxuan_snapshot['subscriber']['chapter']['tbnews_free']+$wenxuan_snapshot['subscriber']['contact']['tbnews_free']+$wenxuan_snapshot['subscriber']['chapter']['tbnews_paid']+$wenxuan_snapshot['subscriber']['contact']['tbnews_paid']; ?></b></td>
                      <td><b><?= $wenxuan_snapshot['subscriber']['chapter']['randeng_free']+$wenxuan_snapshot['subscriber']['contact']['randeng_free']; ?></b></td>
                      <td><b><?= $wenxuan_snapshot['subscriber']['chapter']['randeng_paid']+$wenxuan_snapshot['subscriber']['contact']['randeng_paid']; ?></b></td>
                      <td><b><?= $wenxuan_snapshot['subscriber']['chapter']['randeng_free']+$wenxuan_snapshot['subscriber']['contact']['randeng_free']+$wenxuan_snapshot['subscriber']['chapter']['randeng_paid']+$wenxuan_snapshot['subscriber']['contact']['randeng_paid']; ?></b></td>
                      <td>- N/A -</td>
                      <td><?= $wenxuan_snapshot['subscriber']['contact']['update_date']; ?></td>
                    </tr>
                  </tbody>
                </table>

                <hr />

                <table class="table table-bordered table-striped">
                  <thead>
                    <tr class='warning'><td colspan=3><b>功德主統計</b> <button id="snap" class="btn btn-success"><i class="fa fa-refresh"></i> Refresh</button></td></tr>
                    <tr class="info">
                        <td><b>年份</b></td>
                        <td><b>詳情</b></td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach($wenxuan_snapshot['package'] as $y => $k): ?>
                    <tr>
                      <td><b><a href="<?= base_url(); ?>wenxuan/lists/subscriber/<?= $y; ?>"><?= $y; ?></a></b></td>
                      <td>
                        <table class="table table-bordered">
                          <tr class='success'>
                            <td><b>配套方案</b></td>
                            <td><b>人數</b></td>
                            <td><b>預測金額(RM)</b></td>
                          </tr>
                          <?php 
                            $total_amount = $total_subscriber = 0; 
                            foreach($k as $name => $p): 
                              $total_amount += $p['total_subscriber'] * $p['package_amount'];
                              $total_subscriber += $p['total_subscriber'];
                          ?>
                          <tr>
                            <td><?=$name."(RM".$p['package_amount'].")";?></td>
                            <td><?=$p['total_subscriber'];?></td>
                            <td><?=number_format($p['total_subscriber'] * $p['package_amount']);?></td>
                          </tr>
                          <?php endforeach;?>
                          <tr>
                            <td><b>總數</b></td>
                            <td><b><?=$total_subscriber;?></b></td>
                            <td><b><?=number_format($total_amount);?></b></td>
                          </tr>
                        </table>
                      </td>

                    </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
        </div>
        <!-- /.col-lg-12 -->
    </div>
</div>