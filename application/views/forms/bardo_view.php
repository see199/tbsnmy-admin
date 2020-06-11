<script src="http://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script> 
<script>

var url = "<?= $process_form;?>";
var rowcount = 0;
var id_form = '';

$(document).ready( function () {

	// Add row default 3
	addrow();
	addrow();
	addrow();

	$('#submit').click(function(){
		return false;
		if(!check_empty()) return false;

		// Getting Form Data Start
		data = {
			contact_name: $('#contact_name').val(),
			payer_email:  $('#payer_email').val()
		};

		$('#form-table input').each(function(){
			name = $(this).attr("name");
			data[name] = $(this).val();
		});


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

		if(!check_empty_field('#contact_name')) return false;

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

function addrow(){
	rowcount++;
    var html = '<tr><td><input type=text name="name['+rowcount+']" class="form-control" /></td><td><input type=text name="address['+rowcount+']" class="form-control" /></td><td><input type=text name="wish['+rowcount+']" class="form-control" /></td></tr>';
    $('#form-table tbody').append(html);
}

function paypal_payment(data) {

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
		<img class='img-responsive' src='<?=base_url('assets/img/forms/main-fahui-header.jpg');?>'>
	</div></div>

	<div class='row'>
		<div class='col-md-10 col-md-offset-1'>

			<div class='row'>
				<div class='col-md-12'>
					<div class='h1 text-left'>護摩法會主祈報名表格 （超度報名）</div>
					<div>凡贊助 RM 1000 主祈者，可向主壇上師敬獻哈達以表敬意、包含護摩曼陀羅木一支</div>
				</div>
			</div>
			<br />
			<div class='row'><div class='col-md-12'>

				<div class='row'>
					<div class='col-md-6'>
						<div class='form-inline'>
							<div class='form-group'>
								<label for='contact_name'>陽世報恩人姓名: </label>
								<input type='text' class='form-control' id='contact_name'>
							</div>
						</div>
					</div>
					<div class='col-md-6 text-right'>
						<strong>法會日期：2016年10月30日</strong>
					</div>
				</div>

				<hr />

				<table class='table table-bordered' id='form-table'>
					<thead>
						<tr><th>受超度者</th><th>供奉地點</th><th>迴向</th></tr>
					</thead>
					<tbody>
					</tbody>
					<tfoot>
						<tr><td colspan=3><a href='javascript:void(0)' class='btn btn-default' onclick='addrow();'><span class='glyphicon glyphicon-plus-sign'> 添加更多受超度者</span></a></td></tr>
						<tr><td colspan=3>上列諸靈思生前之罪咎，恐歿後於沉淪，欲求出苦超生，須仗佛光接引，往生功德道場，極樂佛國淨土，一心恭請法會主尊南摩大白蓮花童子，祈求佛光加被，伏乞慈悲，虔誠是禱。</td></tr>
					</tfoot>
				</table>


				<hr />
				<div class='row'>
					<div class='col-md-12'>
						<div class='form-inline'>
							<div class='form-group'>
								<label for='contact_name'>聯絡人電郵: </label>
								<input required type='email' id='payer_email' name='payer_email' class='form-control' placeholder='联络電郵 (Email)' data-toggle="tooltip" title="联络電郵 (Email)" />
							</div>
						</div>
					</div>
				</div>

			</div></div>

			<hr />

			<div class='row'>
				<div class='col-md-12 form-horizontal'>

					<div class='row'><div class='col-md-12 text-right'>
						<form action="<?=$paypal_url;?>" method="post" id="paypal_form">
							<?php foreach($paypal as $name => $value): ?>
							<input type="hidden" id="<?=$name;?>" name="<?=$name;?>" value="<?=$value;?>">
							<?php endforeach; ?>
							<input type="hidden" name="cmd" value="_xclick">
							<input type="hidden" name="currency_code" value="MYR">
							<input type="hidden" name="no_shipping" value="1">
							<input type="hidden" name="rm" value="2">
							<input type="hidden" name="amount" value="1000">
							<input type="hidden" name="custom" id="custom">
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
							<div class='col-md-3'>銀行賬號</div><div class='col-md-9'>312 329 6930</div>
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

					<br />
					<br />
					<br />
				
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
						<div class='col-md-3'>銀行賬號</div><div class='col-md-9'>312 329 6930</div>
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