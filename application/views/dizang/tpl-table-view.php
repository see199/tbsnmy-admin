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
    <?php foreach($mydata as $key => $v): ?>
    <tr>
      <td>
        <?= $v['name'];?> [<?= $v['country'];?>] (<?= $v['contact'];?>) <small>@ <?= $v['date'];?></small><b>ID: <?=$v['id'];?></b>
        <br /><pre><?= $v['question'];?></pre>

        <?php if(in_array($v['status'],array('待回'))):?>
        <br />待回復之訊息：<br /><textarea class="form-control"><?= $v['answer'];?></textarea>
        <?php endif;?>

        <?php if(in_array($v['status'],array('待答','師尊'))):?>
        <br />回復之訊息：
        <div id="answer-alert-<?= $v['row']; ?>" class="alert alert-success" role="alert" style="display:none;"></div>
        <textarea id="answer-<?= $v['row']; ?>" class="form-control"><?= $v['answer'];?></textarea>
        <br /><button type="button" class="btn btn-info" onclick="update_answer(<?= $v['row']; ?>);">保存</button>
        <?php endif;?>

      </td>
      <td nowrap>
        
        <?php if(in_array($v['status'],array('新'))):?>
        <button type="button" class="btn btn-success" onclick="update_status(<?= $v['row']; ?>,'宗委');">宗委</button>
        <?php endif;?>

        <?php if(in_array($v['status'],array('新','宗委'))):?>
        <button type="button" class="btn btn-warning" onclick="update_status(<?= $v['row']; ?>,'落選');">落選</button>
        <?php endif;?>

        <?php if(in_array($v['status'],array('落選'))):?>
        <button type="button" class="btn btn-warning" onclick="update_status(<?= $v['row']; ?>,'待答');">待答</button>
        <?php endif;?>

        <?php if(in_array($v['status'],array('待答'))):?>
        <button type="button" class="btn btn-warning" onclick="update_status(<?= $v['row']; ?>,'待回');">待回</button>
        <?php endif;?>

        <?php if(in_array($v['status'],array('宗委'))):?>
        <button type="button" class="btn btn-success" onclick="update_status(<?= $v['row']; ?>,'師尊');">師尊</button>
        <?php endif;?>

        <?php if(in_array($v['status'],array('師尊','待回'))):?>
        <button type="button" class="btn btn-danger" onclick="update_status(<?= $v['row']; ?>,'完成');">完成</button>
        <?php endif;?>

        <?php if(in_array($v['status'],array('新','落選'))):?>
        <button type="button" class="btn btn-danger" onclick="update_status(<?= $v['row']; ?>,'不理');">不理</button>
        <?php endif;?>

        <?php if(in_array($v['status'],array('新'))):?>
        <button type="button" class="btn btn-danger" onclick="update_status(<?= $v['row']; ?>,'重複');">重複</button>
        <?php endif;?>

        <div id="alert-<?= $v['row']; ?>" class="alert alert-success" role="alert" style="display:none;">
        </div>

      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>