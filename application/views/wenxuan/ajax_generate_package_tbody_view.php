<?php if(count($sp) > 0): ?>
<?php foreach($sp as $cp): ?><tr>
<td><select disabled class="custom-select form-control" id="<?=$cp['package_id'];?>_package_id">
        <?php foreach($package as $p):?>
        <option <?= ($p['package_id'] ==  $cp['package_id']) ? "selected":"" ?> value="<?= $p['package_id']; ?>"><?= $p['year'].'年 '.$p['package_name'].' (RM'.$p['package_amount'].')'; ?></option>
        <?php endforeach;?>
    </select>
    <br />* <?= ($cp['fullpayment']) ? "一次付清" : "分期付款"; ?>
</td>
<td><input type="date" id='<?=$cp['package_id'];?>_start_date' value="<?= $cp['start_date']; ?>"> - 
    <input type="date" id='<?=$cp['package_id'];?>_end_date' value="<?= $cp['end_date']; ?>"></td>
<td><textarea class='form-control' cols=50 id='<?=$cp['package_id'];?>_remarks' rows=5><?=$cp['remarks'];?></textarea>
    <table class="table" style="margin-bottom:0px;"><tr><td style="vertical-align: middle;">Tracking No</td><td><input class='form-control' type="text" id='<?=$cp['package_id'];?>_pos_tracking' value='<?=$cp['pos_tracking'];?>' /></td></tr></table>
</td>
<td nowrap>
    <input <?= ($cp['fullpayment']) ? "checked":"" ?> type="checkbox" id='<?=$cp['package_id'];?>_fullpayment'> 一次付清 
    <br /><input <?= ($cp['status']) ? "checked":"" ?> type="checkbox" id='<?=$cp['package_id'];?>_status'> 完成付款
    <br /><input <?= ($cp['gift_taken']) ? "checked":"" ?> type="checkbox" id='<?=$cp['package_id'];?>_gift_taken'> 已領贈品
    <br /><input <?= ($cp['self_pickup']) ? "checked":"" ?> type="checkbox" id='<?=$cp['package_id'];?>_self_pickup'> 自行領取
    <br /><a href='<?= $receipt_url.$cp['md5_id']; ?>' target='_receipt' >收據</a>
    <br /><a href='http://wa.me/6<?=preg_replace('/[^0-9]/', '', $wenxuan_contact);?>?text=<?= urlencode("感謝您的護持。這是您的電子收據：".$receipt_url.$cp['md5_id']); ?>' target='_receipt' >發送收據</a>
    <br /><a href='<?= $form_url.$cp['md5_id']; ?>' target='_form' >方案表格</a>
</td>
</tr><?php endforeach; ?>
<?php else: ?>
<tr class='text-center'><td colspan=3> - 暫無 - </td></tr>
<?php endif; ?>
<?php /*
<tr class='info text-center'><td colspan=4><b>登記新功德主</b></td></tr>
<tr><td><select class="custom-select form-control" id="new_package_id">
		<option value=''>-選擇配套-</option>
        <?php foreach($package as $p):?>
        <option value="<?= $p['package_id']; ?>"><?= $p['year'].'年 '.$p['package_name'].' (RM'.$p['package_amount'].')'; ?></option>
        <?php endforeach;?>
    </select>
</td>
<td><input type="date" id='new_start_date'> - 
    <input type="date" id='new_end_date'></td>
<td><textarea id='new_remarks' class='form-control' cols=50 rows=5></textarea></td>
<td nowrap>
    <input type="checkbox" id='new_fullpayment'> 一次付清 
    <br /><input type="checkbox" id='new_status'> 完成付款
    <br /><input type="checkbox" id='new_gift_taken'> 已領贈品
</td></tr>

*/?>