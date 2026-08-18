<script src="http://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script> 

<div class='col-md-10 col-md-offset-1'>
	
	<div class='row'>
		<div class='col-md-12'>

			<div class='row'><div class='col-md-12'>

				<table class='table' id='form-table'>

					<thead><tr>
						<td><img src='<?= base_url('assets/img/forms/tbs-logo.png');?>' width='80px'></td>
						<td><b style='font-size:24px;'>馬來西亞真佛宗密教總會</b>
							<br /><b style='font-size:20px;'>PABT Chen Foh Chong Malaysia</b>
							<br />1A, Jalan Perawas, Lebuh Setaka, Taman Chi Liung, 41200 Klang, Selangor
							<br />03-33749399 info@tbsn.my
						</td>
					</tr></thead>
					<tbody>
						<tr><td colspan=2>
							<div style='font-size:28px;'>正式收據 OFFICIAL RECEIPT</div>
							<div class='text-right'>電腦編號 Ref No：<?=$md5_id;?></div>
						</td></tr>
						

						<tr><td colspan=2>
							
							<table class='table table-bordered'>
								<tr><td width="25%">姓名 Name</td><td><?=$subscriber['wenxuan_name'];?></td></tr>
								<tr><td>電話 Tel</td><td><?=$subscriber['wenxuan_contact'];?></td></tr>
								<tr><td>電郵 Email</td><td><?=$subscriber['wenxuan_email'];?></td></tr>
								<tr><td>項目 Item</td><td>
									<?= $details['year']; ?>年大馬文宣功德主方案
									<br /><b><?= $details['package_name'];?></b> - RM <?= $details['package_amount']; ?>
									<br /><b><?= $details['package_description'];?></b>: <?= ($fullpayment) ? '一次付清' : '分期付款'; ?>
								</td></tr>
								<tr><td>付款狀態 Status</td><td>
									<?php if (isset($payment_status) && $payment_status === 'PAID'): ?>
										<span class="label label-success" style="font-size: 14px; background-color: #5cb85c; color: white; padding: 4px 10px; border-radius: 3px;">✓ 付款成功 (PAID)</span>
									<?php else: ?>
										<span class="label label-warning" style="font-size: 14px; background-color: #f0ad4e; color: white; padding: 4px 10px; border-radius: 3px;">⏳ 待確認/處理中 (PENDING)</span>
									<?php endif; ?>
								</td></tr>
								<?php if(!empty($remarks)): ?>
								<tr><td>備註 Remarks</td><td><pre style="background:none; border:none; padding:0; margin:0; font-family:inherit;"><?= $remarks; ?></pre></td></tr>
								<?php endif; ?>
							</table>

						</td></tr>


					</tbody>

					<tfoot><tr><td colspan=2 class='text-center' style='font-size:28px;'>隨喜讚助 . 功德無量</td></tr></tfoot>
				</table>

			</div></div>

			<div class='row'>&nbsp;</div>

		</div>
	</div>
	
</div>

<script>
	$( document ).ready(function() {
	    window.print();
	});
</script>