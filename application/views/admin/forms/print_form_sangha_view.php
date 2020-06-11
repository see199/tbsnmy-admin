<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Bootstrap Core CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.1.0.min.js" integrity="sha256-cCueBR6CsyA4/9szpPfrX3s49M9vUU5BgtiJj06wt/s=" crossorigin="anonymous"></script>
    <title><?= ($form['txn_id']) ? $form['txn_id'] : "sangha_".$form['id_form_sangha'] ; ?>.pdf</title>
    
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body style='max-width:1100px;margin:auto;'>
<style>
    table.print-friendly tbody tr td, table.print-friendly tbody tr th, table.print-friendly tbody, table.print-friendly {
    	page-break-inside: avoid !important;
    }
    div.print-friendly{
    	page-break-inside: avoid !important;
    }
    .print-friendly>tbody>tr>td{
    	font-size:12px;
    }
</style>

<div class='col-md-12'>


	<div class='row' id='sangha_form'>
		<div class='col-md-10 col-md-offset-1'>

			<?php $l = $form;?>

			<div class='col-md-12 form-horizontal print-friendly'>
				<div class='row'><img class='img-responsive' src='<?= base_url('assets/img/forms/sangha-header.jpg'); ?>'></div>
				<div class='h2'>供僧功德主資料</div>
				<table class='table table-bordered print-friendly'><tbody>
					<tr><th width="150px">供僧贊助方案</th><td colspan=3><?= (!in_array($l['amount'],array(108,388,688,888,1080))) ? '隨喜供僧' : ''; ?> RM <?=$l['amount'];?></td></tr>
					<tr><th>供僧功德主</th><td colspan=3><?=$l['name'];?></td></tr>
					<tr><th>祈願 / 迴向</th><td colspan=3><?=$l['wish'];?></td></tr>
					<tr><th>聯絡人</th><td><?=$l['contact_name'];?></td><th width="100px">聯絡號碼</th><td><?=$l['contact_phone'];?></td></tr>
					<tr><th>地址</th><td colspan=3 style='white-space:pre;'><?=$l['contact_address'];?></td></tr>
					<tr><td colspan=4 style='text-align:center;'>報名者 / 功德主 是否將會親自出席『2016 請佛住世全國供僧大會』？ <?= ($l['attend']) ? '會' : '不' ;?>出席</td></tr>
				</tbody></table>

				<table class='table table-bordered print-friendly'><tbody>
					<tr><th width="150px">For Office Use</th><td rowspan=2></td></tr>
					<tr><th>供僧功德主編號</th></tr>
					<tr><th colspan=2>備註:</th></tr>
					<tr><th colspan=2>
						<?php if($l['txn_id'] && !preg_match('/sangha/',$l['txn_id'])): ?>
						Paypal 付款 （Unique Transaction ID #<?= $l['txn_id']; ?>）
						<?php else: ?>
						網上報名，銀行轉賬
						<?php endif;?>
						<br /><br /><br />
					</th></tr>
				</tbody></table>
			</div>


		</div>
	</div>

	
</div>


<script>
$(document).ready(function(){
	window.print();
});
</script>

</body>

</html>