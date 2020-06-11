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

    
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body style='max-width:1100px;margin:auto;'>
<style>
    table.print-friendly tbody tr td, table.print-friendly tbody tr th, table.print-friendly tbody, table.print-friendly, table.print-friendly tfoot tr td,  {
    	page-break-inside: avoid !important;
    }
    div.print-friendly{
    	page-break-inside: avoid !important;
    }
    .print-friendly>tbody>tr>td,.print-friendly>tfoot>tr>td{
    	font-size:10px;
    }
</style>

<div class='col-md-12'>


	<div class='row' id='sangha_form'>
		<div class='col-md-10 col-md-offset-1'>

			<?php $l = $form;?>
			<?php $sum_wood = $sum_card = 0;?>

			<div class='col-md-12 form-horizontal print-friendly'>
				<div class='row'><img class='img-responsive' src='<?= base_url('assets/img/forms/main-fahui-header.jpg'); ?>'></div>

				<div class='row'>
					<div class='col-md-12 text-center' style='font-size:18px;font-weight:bold;'>
						<br />
						<div>祈請根本傳承上師聖尊蓮生活佛佛光加持</div>
						<div>般若雷藏寺主持護摩火供大法會</div>
						<div>息災﹑祈福﹑增益﹑報名表格</div>
						<div>Blessing Ceremony Registration Form</div>
					</div>
				</div>
				<br />
				<div class='row'><div class='col-md-12'>

					<table class='table table-bordered print-friendly' id='form-table'>
						<thead>
							<tr><th style='width:150px'>祈福者姓名</th><th style='width:500px'>現居地址</th><th style='width:120px'>祈願回向</th><th style='width:120px'>延生令牌</th><th style='width:80px'>護摩木</th></tr>
						</thead>
						<tbody><?php foreach($l['list'] as $list):?>
							<?php $sum_wood += $list['count_wood']; ?>
							<?php $sum_card += $list['count_card']; ?>
							<tr><td><?=$list['name'];?></td><td><?=$list['address'];?></td><td><?=$list['wish'];?></td><td><?=$list['count_card'];?></td><td><?=$list['count_wood'];?></td></tr>
						<?php endforeach;?></tbody>
						<tfoot>
							<tr><td class='text-right' colspan=3>護摩木 <?=$sum_wood;?> 片 X RM5   </td><td class='text-right' colspan=2>RM <?= $l['total_wood']; ?></td></tr>
							<tr><td class='text-right' colspan=3>延生令牌 <?=$sum_card;?> 份 X RM 5</td><td class='text-right' colspan=2>RM <?= $l['total_card']; ?></td></tr>
							<tr><td class='text-right' colspan=3>供養金                            </td><td class='text-right' colspan=2>RM <?= $l['total_donation']; ?></td></tr>
							<tr><td class='text-right' colspan=3>總計                              </td><td class='text-right' colspan=2>RM <?= $l['total_amount']; ?></td></tr>
							<tr><td colspan=5>上列善信， 一行至誠， 虔具備供養，仰叩根本上師蓮生活佛聖尊，主尊及諸佛菩薩，金剛，空行，護法諸尊，慈悲宏願，靈光加持、期能業障消除，災厄化解，疾病去除，福慧增長，身体健康，運程亨通，增福添壽，万事如意，一切安樂，心願圓滿，伏乞慈悲，虔誠是禱。 </td></tr>
						</tfoot>
					</table>

					<table class='table print-friendly' style='font-size:10px;'>
						<tr>
							<td><label for='contact_name'>主祈者姓名: </label><?=$l['contact_name'];?></td>
							<td><label for='contact_address'>聯絡電話: </label><?=$l['contact_phone'];?></td>
							<td class='text-right'><strong>法會日期：2016年10月30日</strong></td>
						</tr>
						<tr>
							<td colspan=3><label for='contact_address'>地址: </label><?=$l['contact_address'];?></td>
						</tr>
					</table>
				</div></div>
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