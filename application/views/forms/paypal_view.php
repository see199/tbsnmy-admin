<form action="https://www.paypal.com/cgi-bin/webscr" method="post" id="paypal_form">
	<?php foreach($paypal as $name => $value): ?>
	<input type="hidden" id="<?=$name;?>" name="<?=$name;?>" value="<?=$value;?>">
	<?php endforeach; ?>
	<input type="hidden" name="cmd" value="_xclick">
	<input type="hidden" name="currency_code" value="MYR">
	<input type="hidden" name="no_shipping" value="1">
	<input type="hidden" name="rm" value="2">
	<a href='javascript:void(0);' onclick="$('#paypal_form').submit();" id="submit" class='btn btn-success'>提交表格</a>
</form>