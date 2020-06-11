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
			contact_address: $('#contact_address').val(),
			contact_phone: $('#contact_phone').val(),
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

function calculate(type, price){
	var total = 0;
	$('input[name^="count_'+type+'"]').each(function() {
		var val = check_zero_val($(this));
		total += (val) ? val : 0;
	});

	var price = total * price;
	$('#'+type+'_count').html(total);
	$('#total_'+type).val(price);
	calculate_total();
}

function calculate_total(){
	total_amount = check_zero_val($('#total_wood')) + check_zero_val($('#total_card')) + check_zero_val($('#total_donation'));
	$('#total_amount').val(total_amount);
	$('#amount').val(total_amount);
}

function check_zero_val(obj){

	var val = parseInt(obj.val());
	return (val) ? val : 0;
}


function addrow(){
	rowcount++;
	var html = '<tr>';
	html += td_gen_input('name','text',rowcount,0);
	html += td_gen_input('address','text',rowcount,0);
	html += td_gen_input('wish','text',rowcount,0);
	html += td_gen_input('card','number',rowcount,5);
	html += td_gen_input('wood','number',rowcount,5);
	html += '</tr>';
    $('#form-table tbody').append(html);
}

function td_gen_input(name, type, rowcount, price){

	if(type == 'text')
		return '<td><input type=text name="'+name+'['+rowcount+']" class="form-control" /></td>';
	else if(type == 'number')
		return '<td><input type=number min=0 name="count_'+name+'['+rowcount+']" onchange="calculate(\''+name+'\','+price+')" class="form-control" /></td>';
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
				<div class='col-md-12 text-center h1'>
					<div>祈請根本傳承上師聖尊蓮生活佛佛光加持</div>
					<div>般若雷藏寺主持護摩火供大法會</div>
					<div>息災﹑祈福﹑增益﹑報名表格</div>
					<div>Blessing Ceremony Registration Form</div>
				</div>
			</div>
			<br />
			<div class='row'><div class='col-md-12'>

				<table class='table table-bordered' id='form-table'>
					<thead>
						<tr><th class='col-md-2'>祈福者姓名</th><th class='col-md-4'>現居地址</th><th class='col-md-2'>祈願</th><th class='col-md-2'>延生令牌</th><th class='col-md-2'>護摩木</th></tr>
					</thead>
					<tbody>
					</tbody>
					<tfoot>
						<tr><td colspan=5><a href='javascript:void(0)' class='btn btn-default' onclick='addrow();'><span class='glyphicon glyphicon-plus-sign'> 添加更多祈福者</span></a></td></tr>
						<tr><td class='text-right' colspan=3>護摩木 <span id='wood_count'>0</span> 片 x RM 5</td><td colspan=2><div class='input-group'><div class="input-group-addon">RM</div><input class='form-control' value='0' type=number min=0 name='total_wood' id='total_wood' readonly><div class="input-group-addon">.00</div></div></td></tr>
						<tr><td class='text-right' colspan=3>延生令牌 <span id='card_count'>0</span> 份 x RM 5</td><td colspan=2><div class='input-group'><div class="input-group-addon">RM</div><input class='form-control' value='0' type=number min=0 name='total_card' id='total_card' readonly><div class="input-group-addon">.00</div></div></td></tr>
						<tr><td class='text-right' colspan=3>供養金</td><td colspan=2><div class='input-group'><div class="input-group-addon">RM</div><input onchange='calculate_total()' class='form-control' value='0' type=number min=0 name='total_donation' id='total_donation'><div class="input-group-addon">.00</div></div></td></td></tr>
						<tr><td class='text-right' colspan=3>總計</td><td colspan=2><div class='input-group'><div class="input-group-addon">RM</div><input class='form-control' value='0' type=number min=0 name='total_amount' id='total_amount' readonly><div class="input-group-addon">.00</div></div></td></tr>
						<tr><td colspan=5>上列善信， 一行至誠， 虔具備供養，仰叩根本上師蓮生活佛聖尊，主尊及諸佛菩薩，金剛，空行，護法諸尊，慈悲宏願，靈光加持、期能業障消除，災厄化解，疾病去除，福慧增長，身体健康，運程亨通，增福添壽，万事如意，一切安樂，心願圓滿，伏乞慈悲，虔誠是禱。</td></tr>
					</tfoot>
				</table>

			</div></div>

			<hr />

			<div class='row'><div class='col-md-12 form-horizontal'>

				<div class='col-md-6'><input required type='text' id='contact_name'  name='contact_name'  class='form-control col-md-6' data-toggle="tooltip" placeholder='主祈人' title='主祈人' /></div>
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
							<input type="hidden" name="amount" id='amount' value="0">
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

<div class='row'><div class='col-md-12'>
<div id="res">
</div>
</div></div>


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