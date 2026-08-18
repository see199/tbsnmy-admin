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
							<div style='font-size:28px;'>報名收據 RECEIVE NOTE</div>
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
							</table>

							<div class='text-center' style='margin: 15px 0; font-size:16px;'><b>** 此收據代表電腦系統已收到您的報名 **</b></div>

							<?php if (!$fullpayment): ?>
							<div class="alert alert-info" style="border: 1px solid #bce8f1; padding: 15px; border-radius: 4px; background-color: #f4fbfd;">
								<h4><b>手動匯款 / 分期付款 指引：</b></h4>
								<p>若您選擇手動轉賬或分期付款，請將款項匯入以下戶口：</p>
								<p><b>銀行：</b> Public Bank<br/>
								<b>戶口號碼：</b> 312 584 2930<br/>
								<b>戶口抬頭：</b> P.A.B.T Chen Foh Chong Malaysia 或 馬來西亞真佛宗密教總會</p>
								<p>匯款後，請將匯款收據（Bank Slip）附上此電腦編號 (<b><?=$md5_id;?></b>) 電郵至 <a href="mailto:info@tbsn.my">info@tbsn.my</a>，以便行政人員為您核對與記錄。</p>
							</div>
							<?php endif; ?>

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