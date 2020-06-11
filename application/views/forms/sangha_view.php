<script src="http://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script> 
<script>

var url = "<?= $process_form;?>";
var id_form = '';

$(document).ready( function () {

	$('[data-toggle="tooltip"]').tooltip(); 
	
	$('input[name=donor_type]').click(function(){
		
		value = $('input[name=donor_type]:checked').val();

		if(value == 'rand'){
			$('#amount').val("0");
			$('#amount_display').show();
		}
		else{
			$('#amount').val(value);
			$('#amount_display').hide();
		}
	});

	$('#submit').click(function(){
		return false;
		if(!check_empty()) return false;

		// Getting Form Data Start
		data = {
			contact_address: $('#contact_address').val()
		};

		$('#sangha_form input').each(function(){
			name = $(this).attr("name");
			if(jQuery.inArray(name,['donor_type','attend']) != -1){
				data[name] = $('input[name='+name+']:checked').val();
			}else{
				data[name] = $(this).val();
			}
		});
		data["amount"] = $('#amount').val(); //override paypal amount name
		// Getting Form Data Ends


		$.ajax({
	        url: url,
	        traditional: true,
	        type: "post",
	        dataType: "text",
	        data: data,
	        success: function (result) {
	            var data = $.parseJSON(result);

	            if(data.status == 'success'){
	            	id_form = data.id_form;

	            	$('#success_payment').hide();
					$('#bank_details').hide();

					$('#myModal').modal('show');
					$('#myModalLabel').html('恭喜！成功提交表格（表格編號：<?=$type;?>_'+id_form+'）');
					$('#success_payment').show();

	            }else{
	            	$('#res').html("ERROR: " + data.message);
	            }
	        }
	    });
	});

	function check_empty(){

		if(!check_empty_field('#name')) return false;
		if(!check_empty_field('#wish')) return false;
		if(!check_empty_field('#contact_name')) return false;
		if(!check_empty_field('#contact_phone')) return false;
		if(!check_empty_field('#contact_address')) return false;
		if(!check_empty_field('#amount')) return false;

		return true;
	}

	function check_empty_field(id){
		if($(id).val() == ""){
			alert("请把所以资料填上");
			$(id).focus();
			return false;
		}
		else return true;
	}
});



function paypal_payment(data) {

	$('#paypal_amount').val($('#amount').val());
	$('#return').val($('#return').val()+"/"+id_form);
	$('#custom').val("<?=$type;?>|"+id_form);
	$('#paypal_form').submit();
}

function show_bank_details() {
	$('#success_payment').hide();
	$('#bank_details').show();
}

function return_success() {
	window.location.replace("<?=base_url('forms/return_success');?>");
}

</script>


<div class='col-md-10 col-md-offset-1'>
	<div class='row'><div class='col-md-10 col-md-offset-1'>
		<img class='img-responsive' src='<?=base_url('assets/img/forms/sangha-header.jpg');?>' />
	</div></div>


	<div class='row' id='sangha_form'>
		<div class='col-md-10 col-md-offset-1'>

			<div class='row'>
				<div class='col-md-12'>
					<div class='h1 text-left'>供僧功德主报名表</div>
					<div>僧宝是一切世间供养、布施、修福的无上福田。供僧就是一种清净的供养，让众生当下远离烦恼，除心束缚，得清净心。发心设斋供众成就道场，不仅能广结善缘、减轻业障，长养布施喜舍之心，更为当来深植出世的菩提善因。</div>
				</div>
			</div>
			<br />
			<div class="row">
				<div class="col-md-12">
					<div class="alert alert-warning">
						<table><tbody><tr>
							<td style="padding: 5px 30px 5px 10px;"><i style="font-size: 50px;" class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></i></td>
							<td><strong>公告：</strong><br />为确保报名表格顺利的处理，网络报名已经截止。<br />感谢大家的护持。谢谢。</td>
						</tr></tbody></table>
					</div>
				</div>
			</div>
			<br />
			<div class='row'><div class='col-md-12'>

				<table class='table table-bordered'>
					<tr>
						<td class='col-md-7'>
							<div style='padding:10px;'>
								除了供僧本身殊胜的功德，功德主还可得到以下迴向：
								<br />
								<br />真佛宗僧团恭诵49部《真佛经》
								<br />真佛宗上师团启建21坛护摩火供
								<br />马密总7月份『高王观世音菩萨藏密式护摩法会』
								<br />马密总8月份『莲华生大士藏密式护摩法会 』
								<br />马密总9月份『瑶池金母藏密式护摩法会』
								<br />真佛宗僧团7、8、9月份《真佛宝忏》同修
								<br />
								<br />* 凡赞助供僧 RM108 或以上的功德主在大法会当天可向僧人敬献哈达以表敬意
							</div>
						</td>
						<td class='col-md-5'>
							<div class='col-md-10 col-offset-2'>
								<div class='radio'><label><input type='radio' name='donor_type' value='108' checked> 供僧赞助方案 MYR 108</label></div>
								<div class='radio'><label><input type='radio' name='donor_type' value='388' checked> 供僧赞助方案 MYR 388</label></div>
								<div class='radio'><label><input type='radio' name='donor_type' value='688' checked> 供僧赞助方案 MYR 688</label></div>
								<div class='radio'><label><input type='radio' name='donor_type' value='888' checked> 供僧赞助方案 MYR 888</label></div>
								<div class='radio'><label><input type='radio' name='donor_type' value='1080' checked> 供僧赞助方案 MYR 1080</label></div>
								<div class='radio'><label><input type='radio' name='donor_type' value='rand' checked> 随喜供僧</label></div>
								<div class='col-md-12 input-group' id='amount_display'>
									<span class='input-group-addon'>MYR</span>
									<input class='form-control' type='text' name='amount' id='amount'>
									<span class='input-group-addon'>.00</span>
								</div>
							</div>
						</td>
					</tr>
				</table>
			</div></div>

			<hr />

			<div class='h3'>供僧功德主资料</div>

			<div class='row'>
				<div class='col-md-12 form-horizontal'>

					<div class='col-md-12'>
						<input required type='text' id='name' name='name' class='form-control' placeholder='供僧功德主' data-toggle="tooltip" title="供僧功德主：供僧功德主可以是报名者本人、合家、历代祖先、亡者、阳上（某某某）之冤亲债主缠身灵、阳上（某某某）之水子灵、土地公等等" />
						<div class='small'>供僧功德主可以是报名者本人、合家、历代祖先、亡者、阳上（某某某）之冤亲债主缠身灵、阳上（某某某）之水子灵、土地公等等</div>
						
						<br />
					</div>

					<div class='col-md-12'>
						<input required type='text' id='wish' name='wish' class='form-control' placeholder='祈愿 / 迴向' data-toggle="tooltip" title="祈愿 / 迴向" />
						<br />
					</div>
				
					<div class='col-md-6'><input required type='text' id='contact_name'  name='contact_name'  class='form-control col-md-6' data-toggle="tooltip" placeholder='联络人姓名' title='联络人姓名' /></div>
					<div class='col-md-6'><input required type='text' id='contact_phone' name='contact_phone' class='form-control col-md-6' data-toggle="tooltip" placeholder='联络电话' title='联络电话' /></div>

					<div class='col-md-12'>
						<br />
						<input required type='email' id='payer_email' name='payer_email' class='form-control' placeholder='联络電郵 (Email)' data-toggle="tooltip" title="联络電郵 (Email)" />
						<br />
					</div>

					<div class='col-md-12'>
						<textarea required id='contact_address' name='contact_address' class='form-control' data-toggle="tooltip" placeholder='住址或通讯地址' title='住址或通讯地址'></textarea>
						<br />
					</div>

					<div class='col-md-12'>
						报名者 / 功德主 是否会亲自出席『2016年请佛住世全国供僧大会』？
						<div class='radio'><label><input type='radio' name='attend' value='1' checked> 会出席</label></div>
						<div class='radio'><label><input type='radio' name='attend' value='0'> 不会出席</label></div>
						<br />
					</div>

					<div class='row'><div class='col-md-12 text-right'>
						<form action="<?=$paypal_url;?>" method="post" id="paypal_form">
							<?php foreach($paypal as $name => $value): ?>
							<input type="hidden" id="<?=$name;?>" name="<?=$name;?>" value="<?=$value;?>">
							<?php endforeach; ?>
							<input type="hidden" name="cmd" value="_xclick">
							<input type="hidden" name="currency_code" value="MYR">
							<input type="hidden" name="no_shipping" value="1">
							<input type="hidden" name="rm" value="2">
							<input type="hidden" name="custom" id="custom">
							<input type="hidden" id="paypal_amount" name="amount">
							<a href='<?= base_url('forms');?>' class='btn btn-danger'>返回线上报名主页</a>
							<a href='javascript:void(0);' id="submit" class='btn btn-success' disabled>提交表格</a>
						</form>
					</div></div>

					<div class='col-md-12'>
						<br />* 目前线上报名只限于信用卡或 Paypal 转账<!--，如果通過銀行轉賬，請在提交表格後，上載匯款記錄（文件為  JPEG 種類）。-->
						<br />* 基於 Paypal 手續費比較高，因此希望本地同門可以通過銀行轉賬，再把收據電郵至 info@tbsn.my
						<br />
						<br /><strong>馬密總銀行戶口資料：</strong>
						<br />
						<div class='row'>
							<div class='col-md-3'>銀行名稱</div><div class='col-md-9'>PUBLIC BANK BERHAD (大眾銀行)</div>
							<div class='col-md-3'>銀行賬號</div><div class='col-md-9'>312 584 3015</div>
							<div class='col-md-3'>戶口名稱</div><div class='col-md-9'>P.A.B.T CHEN FOH CHONG MALAYSIA</div>
							<div class='col-md-3'>SWIFT CODE</div><div class='col-md-9'>PBBEMYKL</div>
						</div>
						<br />
						<br />
						<br />
						<hr />
						<br />
						<br />
						<br />
					</div>
				
				</div>
			</div>

		</div>
	</div>

	
</div>

<div id="res">
</div>

<script src="https://use.fontawesome.com/2d403fd83d.js"></script>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="myModalLabel">Modal title</h4>
			</div>
			<div class="modal-body" id="myModalBody"><div class='row'>
				<div id='success_payment' class='col-md-10 col-md-offset-1'>
					恭喜！成功提交表格。請選擇付款/匯款方式：
					<br />
					<hr />
					<button type="button" class="btn btn-info" onclick="paypal_payment();"><i class="fa fa-paypal" aria-hidden="true"></i> Paypal 付款</button>
					<button type="button" class="btn btn-info" onclick="show_bank_details();"><i class="fa fa-university" aria-hidden="true"></i> 銀行轉賬</button>
					<hr />
				</div>
				<div id='bank_details' class='col-md-10 col-md-offset-1'>
					<br />匯款後，請把收據電郵至 info@tbsn.my
					<br />
					<br /><strong>馬密總銀行戶口資料：</strong>
					<br />
					<div class='row'>
						<div class='col-md-3'>銀行名稱</div><div class='col-md-9'>PUBLIC BANK BERHAD (大眾銀行)</div>
						<div class='col-md-3'>銀行賬號</div><div class='col-md-9'>312 584 3015</div>
						<div class='col-md-3'>戶口名稱</div><div class='col-md-9'>P.A.B.T CHEN FOH CHONG MALAYSIA</div>
						<div class='col-md-3'>SWIFT CODE</div><div class='col-md-9'>PBBEMYKL</div>
					</div>
					<hr />
					<button type="button" class="btn btn-success" onclick="return_success();"><i class="fa fa-undo" aria-hidden="true"></i> 返回主頁</button>
					<hr />
				</div>
				<br /><br />
			</div></div>
		</div>
	</div>
</div>