
<style>
    table.print-friendly tbody tr td, table.print-friendly tbody tr th, table.print-friendly tbody, table.print-friendly {
    	page-break-inside: avoid !important;
    }
    div.print-friendly{
    	page-break-inside: avoid !important;
    }
</style>

<div class='col-md-12'>


	<div class='row' id='sangha_form'>
		<div class='col-md-10 col-md-offset-1'>

			<table class='table'>
			<?php foreach($list as $l): ?>
			<tr><td>

				<div class='col-md-12 form-horizontal print-friendly'>
					<div class='h2'>供僧功德主 (RM <?=$l['amount'];?>) <small><?= ($l['attend']) ? '會' : '不' ;?>出席</small></div>
					<table class='table table-bordered print-friendly'><tbody>
						<tr><th width="100px">姓名</th><td colspan=3><?=$l['name'];?></td></tr>
						<tr><th>迴向</th><td colspan=3><?=$l['wish'];?></td></tr>
						<tr><th>聯絡人</th><td><?=$l['contact_name'];?></td><th width="100px">聯絡號碼</th><td><?=$l['contact_phone'];?></td></tr>
						<tr><th>地址</th><td colspan=3 style='white-space:pre;'><?=$l['contact_address'];?></td></tr>
					</tbody></table>
				</div>


			</td></tr>
			<?php endforeach;?>
			</table>
		</div>
	</div>

	
</div>