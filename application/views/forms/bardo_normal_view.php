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
	total_amount = check_zero_val($('#total_ship')) + check_zero_val($('#total_paper')) + check_zero_val($('#total_lotus')) + check_zero_val($('#total_donation'));
	$('#total_amount').val(total_amount);
	$('#amount').val(total_amount);
}

function check_zero_val(obj){

	var val = parseInt(obj.val());
	return (val) ? val : 0;
}


function addrow(){
	rowcount++;
    var html = '<tr><td class="form-inline">' + bardo_box_generator(rowcount) + '</td>';
	html += td_gen_input('address','text',rowcount,0);
	html += td_gen_input('lotus','number',rowcount,5);
	html += td_gen_input('paper','number',rowcount,5);
	html += td_gen_input('ship','number',rowcount,50);
	html += '</tr>';
    $('#form-table tbody').append(html);
}

function td_gen_input(name, type, rowcount, price){

	if(type == 'text')
		return '<td><input type=text name="'+name+'['+rowcount+']" class="form-control" /></td>';
	else if(type == 'number')
		return '<td><input type=number min=0 name="count_'+name+'['+rowcount+']" onchange="calculate(\''+name+'\','+price+')" class="form-control" /></td>';
}

function bardo_type(me){
	var pre = me.next().next().children();
	var txt = pre.next();
	var pos = txt.next();
	var nam = pos.next();

	pre.html("");
	txt.val("");
	pos.html("");
	txt.css('width','110px');

	switch(me.val()){
		case '1':
			pos.html("門堂上歷代祖先");
			txt.css('width','45px');
			break;
		case '2':
			pre.html("亡者: ");
			pos.html("");
			break;
		case '3':
			pre.html("陽上: ");
			pos.html(" 之 怨親債主，纏身靈");
			break;
		case '4':
			pre.html("陽上: ");
			pos.html(" 之 水子靈");
			break;
		case '5':
			txt.val("土地公/地基主");
			txt.css('width','145px');
			break;
	}
}

function setvalue(me){
	var pre = me.prev();
	var pos = me.next();
	var nam = pos.next();
	nam.val(pre.html()+me.val()+pos.html());
}

function bardo_box_generator(rowcount){
	var html = "";
	html += "<select class='form-control' onchange='bardo_type($(this))'>";
	html += "	<option value='1'>歷代祖先</option>";
	html += "	<option value='2'>亡者</option>";
	html += "	<option value='3'>怨親債主/纏身靈</option>";
	html += "	<option value='4'>水子靈</option>";
	html += "	<option value='5'>土地公地基主</option>";
	html += "</select><br />";
	html += "<div class='form-group'><span></span>";
	html += "	<input style='width:45px;' type='text' class='form-control' onchange='setvalue($(this))'>";
	html += "	<span>門堂上歷代祖先</span>";
	html += '	<input type=hidden name="name['+rowcount+']" class="form-control" />'
	html += "</div>";
	return html;
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
					<div>超度報名表格</div>
					<div>Bardo Ceremony Registration Form</div>
				</div>
			</div>
			<br />
			<div class='row'><div class='col-md-12'>

				<table class='table table-bordered' id='form-table'>
					<thead>
						<tr><th style='width:220px'>受超度者姓名</th><th style='width:270px'>供奉靈牌/ 墓地/ 陽居地址</th><th style='width:80px'>蓮花令牌</th><th style='width:80px'>真佛紙金</th><th style='width:80px'>法船</th></tr>
					</thead>
					<tbody>
					</tbody>
					<tfoot>
						<tr><td colspan=5><a href='javascript:void(0)' class='btn btn-default' onclick='addrow();'><span class='glyphicon glyphicon-plus-sign'> 添加更多受超度者者</span></a></td></tr>
						<tr><td class='text-right' colspan=2>蓮花令牌共 <span id='lotus_count'>0</span> 份 x RM 5</td><td colspan=3><div class='input-group'><div class="input-group-addon">RM</div><input class='form-control' value='0' type=number min=0 name='total_lotus' id='total_lotus' readonly><div class="input-group-addon">.00</div></div></td></tr>
						<tr><td class='text-right' colspan=2>真佛紙金共 <span id='paper_count'>0</span> 份 x RM 5</td><td colspan=3><div class='input-group'><div class="input-group-addon">RM</div><input class='form-control' value='0' type=number min=0 name='total_paper' id='total_paper' readonly><div class="input-group-addon">.00</div></div></td></tr>
						<tr><td class='text-right' colspan=2>法船共 <span id='ship_count'>0</span> 艘 x RM 50</td><td colspan=3><div class='input-group'><div class="input-group-addon">RM</div><input class='form-control' value='0' type=number min=0 name='total_ship' id='total_ship' readonly><div class="input-group-addon">.00</div></div></td></tr>
						<tr><td class='text-right' colspan=2>超度供養金</td><td colspan=3><div class='input-group'><div class="input-group-addon">RM</div><input onchange='calculate_total()' class='form-control' value='0' type=number min=0 name='total_donation' id='total_donation'><div class="input-group-addon">.00</div></div></td></td></tr>
						<tr><td class='text-right' colspan=2>總計</td><td colspan=3><div class='input-group'><div class="input-group-addon">RM</div><input class='form-control' value='0' type=number min=0 name='total_amount' id='total_amount' readonly><div class="input-group-addon">.00</div></div></td></tr>
						<tr><td colspan=5>上列諸靈思生前之罪咎，恐歿後於沉淪，欲求出苦超生，須仗佛光接引，往生功德道場，極樂佛國淨土，一心恭請法會主尊，祈求佛光加被，伏乞慈悲，虔誠是禱。</td></tr>
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