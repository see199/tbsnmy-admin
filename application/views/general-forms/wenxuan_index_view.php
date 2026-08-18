<script src="http://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script> 
<script>

var url = "<?= base_url('wenxuan/form/add_subscriber'); ?>";

$(document).ready( function () {

	$('#submit').click(function(){

		var incomplete = false;

		// Empty Checking
		var selected_payment = $('#form-table-package input[name="payment"]:checked').val();
		if(!selected_payment){
			alert("訂閱方案 不能空白！");
			return false;
		}

		$("input.no-empty").each(function(){
			if($(this).val().trim() == ""){
				alert($(this).attr('placeholder') + " 不能空白！");
				incomplete = true;
				$(this).focus();
				return false;
			}
		});
		if(incomplete) return false;

		// Disable button during submission
		$('#submit').attr('disabled', true).addClass('disabled').text('提交中 Processing...');

		// Serialize form fields correctly
		var formData = $('#wenxuan_main_form').serialize();

		$.ajax({
			url: url,
			type: "post",
			dataType: "json",
			data: formData,
			success: function (data) {
				if(data.Status == true || data.Status == 1){
					if(data.payment_type === 'xendit' && data.invoice_url) {
						$('#myModalLabel').html('正在前往 Xendit 安全付款頁面...');
						$('#myModalBody').html('<div class="text-center" style="padding:20px;"><h4><i class="fa fa-spinner fa-spin"></i> 提交成功！正在為您開啟 Xendit 線上付款頁面...</h4><br/><p><a href="' + data.invoice_url + '" class="btn btn-lg btn-success">如果頁面沒有自動跳轉，請點擊此處前往付款</a></p></div>');
						$('#myModal').modal('show');
						setTimeout(function(){
							window.location.href = data.invoice_url;
						}, 1200);
					} else {
						$('#myModalLabel').html('恭喜！成功提交表格。');
						$('#myModalBody').html('<div class="row"><div class="col-md-10 col-md-offset-1">' + data.Msg + '<hr/><a href="' + data.URL + '" target="_receipt" class="btn btn-success"><i class="fa fa-print"></i> 查看/打印收據</a> <button type="button" class="btn btn-danger" onclick="$(\'#myModal\').modal(\'hide\');"><i class="fa fa-times"></i> 關閉 / Close</button></div></div>');
						$('#myModal').modal('show');
					}
				} else {
					alert("報名失敗: " + (data.Msg || "請稍後再試"));
				}
				$('#submit').attr('disabled', false).removeClass('disabled').text('提交表格');
			},
			error: function (xhr, status, error) {
				alert("網路連線錯誤，請稍後再試。");
				$('#submit').attr('disabled', false).removeClass('disabled').text('提交表格');
			}
		});
	});
});

</script>

<div class='col-md-10 col-md-offset-1'>
	
	<div class='row'>
		<div class='col-md-12'>

			<div class='row'>
				<div class='col-md-12 text-center h1'>
					<div>大馬文宣功德主 <?= $year; ?></div>
					<div>讚助回饋方案 報名表格</div>
				</div>
				<div class='col-md-12 text-left'>
					<div>護持文宣推廣是成就無量功德、福慧並修的良善法緣！聖尊蓮生活佛曾說：【眾生不可思議。】大馬真佛宗秉持著聖尊蓮生活佛【不捨一眾生】的宗旨，法船慈航，廣渡眾生。而大馬真佛宗文宣肩負著推廣宗派成長與弘揚的使命，您的發心護持文宣等同護持真佛宗派，亦與助印經書相等，有無量無邊功德。</div>
				</div>
			</div>
			<br />

			<form id="wenxuan_main_form">
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
						<?php for($i=1;$i<=6;$i++): /*Loop 6 Times*/ ?>
						<tr>
							<td><input class="form-control" type="text" name="name[<?=$i;?>]"></td>
							<td><input class="form-control" type="text" name="age[<?=$i;?>]"></td>
							<td><input class="form-control" type="text" name="address[<?=$i;?>]"></td>
							<td><input class="form-control" type="text" name="wish[<?=$i;?>]"></td>
						</tr>
						<?php endfor;?>

						<tr><td colspan=4>
							
							<table class='table table-bordered' id='form-table-package'>
								<tr class='info'><td colspan=4><b>訂閱方案</b></td></tr>
								<?php foreach($packages as $package_data): ?>
									<tr>
										<td><?=$package_data['package_name'];?></td>
										<td>RM <?=$package_data['package_amount'];?></td>
										<td><?=$package_data['package_description'];?></td>
										<td>
											<input type="radio" name="payment" id="<?=$package_data['package_id'];?>_full" value="<?=$package_data['package_id'];?>_full">
											<label for="<?=$package_data['package_id'];?>_full"> 一次付清 (Xendit 線上付款)</label>
											<br/>
											<input type="radio" name="payment" id="<?=$package_data['package_id'];?>_nofull" value="<?=$package_data['package_id'];?>_nofull">
											<label for="<?=$package_data['package_id'];?>_nofull"> 分期付款 (手動轉賬)</label>
										</td>
									</tr>
								<?php endforeach;?>
							</table>
						</td></tr>

						<tr><td colspan=4>
							
							<table class='table table-bordered'>
								<tr class='info'><td colspan=2><b>個人資料</b></td></tr>
								<tr><td>聯絡人 <font color='red'>*</font></td><td><input class="form-control no-empty" name='wenxuan_name' placeholder="聯絡人" /></td></tr>
								<tr><td>英文名字<small> (郵寄用途)</small> <font color='red'>*</font></td><td><input class="form-control no-empty" name='wenxuan_name_receiver' placeholder="英文名字" /></td></tr>
								<tr><td>聯絡號碼 <font color='red'>*</font></td><td><input class="form-control no-empty" name='wenxuan_contact' placeholder="聯絡號碼" /></td></tr>
								<tr><td>電郵 <font color='red'>*</font></td><td><input class="form-control no-empty" name='wenxuan_email' placeholder="電郵" /></td></tr>
								<tr><td>贈品 <font color='red'>*</font></td><td>
									<input type="radio" name="self_pickup" id="pickup_yes" value="1">
									<label for="pickup_yes"> 自行領取</label>
									<input type="radio" style='margin-left: 20px;' name="self_pickup" id="pickup_no" value="0" checked>
									<label for="pickup_no"> 郵寄</label></td></tr>
								<tr><td>地址 <font color='red'>*</font></td><td>
									<input class="form-control no-empty" name='wenxuan_address1' placeholder="Address 1" />
									<br /><input class="form-control" name='wenxuan_address2' placeholder="Address 2" />
									<br /><input class="form-control no-empty" name='wenxuan_city' placeholder="City" />
									<br /><input class="form-control no-empty" name='wenxuan_postcode' placeholder="Postcode" />
									<br /><input class="form-control no-empty" name='wenxuan_state' placeholder="State" />
									<br /><input class="form-control no-empty" name='wenxuan_country' placeholder="Country" value="Malaysia" />
								</td></tr>
							</table>
						</td></tr>
					</tbody>
					<tfoot><tr><td colspan=4>
						支票，匯票或 Wang Pos 抬頭： P.A.B.T Chen Foh Chong Malaysia 或 馬來西亞真佛宗密教總會
						<br />您也可以將款項直接匯入馬密總文宣部戶口 - 銀行： Public Bank 號碼： 312 584 2930
						<br />（請將匯款收據連同報名收據一起電郵至馬密總： <a href="mailto:info@tbsn.my" target="_blank">info@tbsn.my</a>）
					</td></tr></tfoot>
				</table>

			</div></div>

			<div class='row'>
				<div class='col-md-12 text-right'>
					<a href='javascript:void(0);' id="submit" class='btn btn-success btn-lg'>提交表格</a>
				</div>
			</div>
			</form>

			<div class='row'>&nbsp;</div>

		</div>
	</div>
</div>

<script src="https://use.fontawesome.com/2d403fd83d.js"></script>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel">系統通知</h4>
			</div>
			<div class="modal-body" id="myModalBody"></div>
		</div>
	</div>
</div>