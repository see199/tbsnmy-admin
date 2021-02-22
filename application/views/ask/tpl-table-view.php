<style>
pre{
white-space: break-spaces;
}
</style>

<table class="table table-bordered table-striped">
  <thead>
    <tr class='info'><td><b>資料</b></td><td><b>修改</b></td></tr>
  </thead>

  <tbody>
    <?php foreach($mydata as $v): ?>
    <tr>
      <td><?= $v['name'];?> [<?= $v['country'];?>] (<?= $v['contact'];?>) <small>@ <?= $v['date'];?></small><br /><pre><?= $v['question'];?></pre></td>
      <td nowrap>EDIT | CHANGE STATUS</td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>