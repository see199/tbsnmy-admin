<div class='col-md-10 col-md-offset-1'>
	
	<div class='row'>
		<div class='col-md-12'>

			<div class='row'>
				<div class='col-md-12 text-center h1'>
					<div>大馬文宣功德主 <?= $packages['year']; ?></div>
					<div>讚助回饋方案 報名表格</div>
				</div>
				<div class='col-md-12 text-left'>
					<div>護持文宣推廣是成就無量功德、福慧並修的良善法緣！聖尊蓮生活佛曾說：【眾生不可思議。】大馬真佛宗秉持著聖尊蓮生活佛【不捨一眾生】的宗旨，法船慈航，廣渡眾生。而大馬真佛宗文宣肩負著推廣宗派成長與弘揚的使命，您的發心護持文宣等同護持真佛宗派，亦與助印經書相等，有無量無邊功德。</div>
				</div>
			</div>
			<br />
			<div class='row'><div class='col-md-12'>

				<table class='table table-bordered' id='form-table'>
					<thead>
						<tr class='info'>
							<th class='col-md-2'>姓名</th>
							<th class='col-md-1'>年齡</th>
							<th class='col-md-6'>地址</th>
							<th class='col-md-3'>祈願</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($package['register_blessing'] as $k => $u): ?>
						<tr>
							<td><?= $u['name']; ?></td>
							<td><?= $u['age']; ?></td>
							<td><?= $u['address']; ?></td>
							<td><?= $u['wish']; ?></td>
						</tr>
						<?php endforeach;?>

						<tr><td colspan=4>
							
							<table class='table table-bordered' id='form-table-package'>
								<tr class='info'><td colspan=4><b>訂閱方案</b></td></tr>
								<tr>
									<td>
										<b><?=$packages['package_name'];?> (RM <?=$packages['package_amount'];?>)</b>
										<br /><small><?=$packages['package_description'];?></small>
									</td>
									<td>
										<?= ($package['fullpayment']) ? "&#10004;" : "&nbsp;&nbsp;"; ?>一次付清
										<br /><?= ($package['fullpayment']) ? "&nbsp;&nbsp;" : "&#10004;"; ?>分期付款</td>
								</tr>
							</table>
						</td></tr>

						<tr><td colspan=4>
							
							<table class='table table-bordered'>
								<tr class='info'><td colspan=2><b>個人資料</b></td></tr>
								<tr><td>聯絡人 <font color='red'>*</font></td><td><?= $subscriber['wenxuan_name']; ?></td></tr>
								<tr><td>英文名字 <font color='red'>*</font></td><td><?= $subscriber['wenxuan_name_receiver']; ?></td></tr>
								<tr><td>聯絡號碼 <font color='red'>*</font></td><td><?= $subscriber['wenxuan_contact']; ?></td></tr>
								<tr><td>地址 <font color='red'>*</font></td><td>
									<?= $subscriber['wenxuan_address1']; ?>
									<br /><?= $subscriber['wenxuan_address2']; ?>
									<br /><?= $subscriber['wenxuan_postcode']; ?> <?= $subscriber['wenxuan_city']; ?>,
									<br /><?= $subscriber['wenxuan_state']; ?>, <?= $subscriber['wenxuan_country']; ?>
								</td></tr>
							</table>
						</td></tr>
					</tbody>
					<tfoot><tr><td colspan=4>
						支票，匯票或 Wang Pos 抬頭： P.A.B.T Chen Foh Chong Malaysia 或 馬來西亞真佛宗密教總會
						<br />您也可以將款項直接匯入馬密總文宣部戶口 - 銀行： MayBank 號碼： 5121 0160 4748
						<br />（請將匯款收據連同報名收據一起電郵至馬密總： info@tbsn.my）
					</td></tr></tfoot>
				</table>

			</div></div>


			<div class='row'>&nbsp;</div>

		</div>
	</div>

	
</div>

<div class='row'><div class='col-md-12'>
<div id="res">
</div>
</div></div>


<script src="https://use.fontawesome.com/2d403fd83d.js"></script>