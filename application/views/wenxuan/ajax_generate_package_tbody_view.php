<?php if(isset($sp)): ?>
<?php foreach($sp as $cp): ?><tr>
<td><select disabled class="custom-select form-control" id="<?=$cp['package_id'];?>_package_id">
        <?php foreach($package as $p):?>
        <option <?= ($p['package_id'] ==  $cp['package_id']) ? "selected":"" ?> value="<?= $p['package_id']; ?>"><?= $p['year'].'年 '.$p['package_name'].' (RM'.$p['package_amount'].')'; ?></option>
        <?php endforeach;?>
    </select>
</td>
<td><input type="date" id='<?=$cp['package_id'];?>_start_date' value="<?= $cp['start_date']; ?>"> - 
    <input type="date" id='<?=$cp['package_id'];?>_end_date' value="<?= $cp['end_date']; ?>"></td>
<td>
    <input <?= ($cp['fullpayment']) ? "checked":"" ?> type="checkbox" id='<?=$cp['package_id'];?>_fullpayment'> 一次付清 
    <input <?= ($cp['status']) ? "checked":"" ?> type="checkbox" id='<?=$cp['package_id'];?>_status'> 完成付款
</td>
</tr><?php endforeach; ?>
<?php else: ?>
<tr class='text-center'><td colspan=3> - 暫無 - </td></tr>
<?php endif; ?>
<tr class='info text-center'><td colspan=3><b>登記新功德主</b></td></tr>
<tr><td><select class="custom-select form-control" id="new_package_id">
		<option value=''>-選擇配套-</option>
        <?php foreach($package as $p):?>
        <option value="<?= $p['package_id']; ?>"><?= $p['year'].'年 '.$p['package_name'].' (RM'.$p['package_amount'].')'; ?></option>
        <?php endforeach;?>
    </select>
</td>
<td><input type="date" id='new_start_date'> - 
    <input type="date" id='new_end_date'></td>
<td>
    <input type="checkbox" id='new_fullpayment'> 一次付清 
    <input type="checkbox" id='new_status'> 完成付款
</td></tr>