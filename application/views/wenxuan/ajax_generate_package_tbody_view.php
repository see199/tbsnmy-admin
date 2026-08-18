<?php if(count($sp) > 0): ?>
<?php foreach($sp as $cp): ?>
<?php $sub_key = !empty($cp['sub_year_id']) ? $cp['sub_year_id'] : (!empty($cp['id']) ? $cp['id'] : $cp['package_id']); ?>
<tr>
<td>
    <select disabled class="custom-select form-control" id="<?=$sub_key;?>_package_id">
        <?php foreach($package as $p):?>
        <option <?= ($p['package_id'] ==  $cp['package_id']) ? "selected":"" ?> value="<?= $p['package_id']; ?>"><?= $p['year'].'年 '.$p['package_name'].' (RM'.$p['package_amount'].')'; ?></option>
        <?php endforeach;?>
    </select>
    <br />* <?= ($cp['fullpayment']) ? "一次付清" : "分期付款"; ?>
    <br /><br />Source:
    <?php echo form_dropdown('',['desk' => '知客', 'web' => '網站'],$cp['source'],['class' => 'custom-select form-control','id' => $sub_key.'_source']); ?>

</td>
<td><input type="date" id='<?=$sub_key;?>_start_date' value="<?= $cp['start_date']; ?>"> - 
    <input type="date" id='<?=$sub_key;?>_end_date' value="<?= $cp['end_date']; ?>"></td>
<td><textarea class='form-control' cols=50 id='<?=$sub_key;?>_remarks' rows=5><?=$cp['remarks'];?></textarea>
    <table class="table" style="margin-bottom:0px;"><tr><td style="vertical-align: middle;">Tracking No</td><td><input class='form-control' type="text" id='<?=$sub_key;?>_pos_tracking' value='<?=$cp['pos_tracking'];?>' /></td></tr></table>
</td>
<td nowrap>
    <input <?= ($cp['fullpayment']) ? "checked":"" ?> type="checkbox" id='<?=$sub_key;?>_fullpayment'> 一次付清 
    <br /><input <?= ($cp['status']) ? "checked":"" ?> type="checkbox" id='<?=$sub_key;?>_status'> 完成付款
    <br /><input <?= ($cp['gift_taken']) ? "checked":"" ?> type="checkbox" id='<?=$sub_key;?>_gift_taken'> 已領贈品
    <br /><input <?= ($cp['self_pickup']) ? "checked":"" ?> type="checkbox" id='<?=$sub_key;?>_self_pickup'> 自行領取
    <br /><a href='<?= $receipt_url.$cp['md5_id']; ?>' target='_receipt' >收據</a>
    <br /><a href='http://wa.me/6<?=preg_replace('/[^0-9]/', '', $wenxuan_contact);?>?text=<?= urlencode("感謝您的護持。這是您的電子收據：".$receipt_url.$cp['md5_id']); ?>' target='_receipt' >發送收據</a>
    <br /><a href='<?= $form_url.$cp['md5_id']; ?>' target='_form' >方案表格</a>
</td>
</tr><?php endforeach; ?>
<?php else: ?>
<tr class='text-center'><td colspan=3> - 暫無 - </td></tr>
<?php endif; ?>