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
			<?php $sum_paper = $sum_lotus = $sum_ship = 0;?>

			<div class='col-md-12 form-horizontal print-friendly'>
				<div class='row'><img class='img-responsive' src='<?= base_url('assets/img/forms/main-fahui-header.jpg'); ?>'></div>

				<div class='row'>
					<div class='col-md-12 text-center' style='font-size:18px;font-weight:bold;'>
						<br />
						<div>祈請根本傳承上師聖尊蓮生活佛佛光加持</div>
						<div>護摩法會 超度報名表格</div>
						<div>Bardo Ceremony Registration Form</div>
					</div>
				</div>
				<br />
				<div class='row'><div class='col-md-12'>

					<table class='table table-bordered print-friendly' id='form-table'>
						<thead>
							<tr><th style='width:150px'>（受超度者）姓名</th><th style='width:500px'>供奉靈牌/ 墓地/ 陽居地址</th><th style='width:120px'>蓮花令牌</th><th style='width:120px'>真佛紙金</th><th style='width:80px'>法船</th></tr>
						</thead>
						<tbody><?php foreach($l['list'] as $list):?>
							<?php $sum_paper += $list['count_paper']; ?>
							<?php $sum_lotus += $list['count_lotus']; ?>
							<?php $sum_ship += $list['count_ship']; ?>
							<tr><td><?=$list['name'];?></td><td><?=$list['address'];?></td><td><?=$list['count_lotus'];?></td><td><?=$list['count_paper'];?></td><td><?=$list['count_ship'];?></td></tr>
						<?php endforeach;?></tbody>
						<tfoot>
							<tr><td class='text-right' colspan=3>蓮花令牌共 <?=$sum_lotus;?> 片 X RM5  </td><td class='text-right' colspan=2>RM <?= $l['total_lotus']; ?></td></tr>
							<tr><td class='text-right' colspan=3>真佛紙金共 <?=$sum_paper;?> 份 X RM 5 </td><td class='text-right' colspan=2>RM <?= $l['total_paper']; ?></td></tr>
							<tr><td class='text-right' colspan=3>法船共 <?=$sum_ship;?> 艘 X RM 50     </td><td class='text-right' colspan=2>RM <?= $l['total_ship']; ?></td></tr>
							<tr><td class='text-right' colspan=3>供養金                                </td><td class='text-right' colspan=2>RM <?= $l['total_donation']; ?></td></tr>
							<tr><td class='text-right' colspan=3>總計                                  </td><td class='text-right' colspan=2>RM <?= $l['total_amount']; ?></td></tr>
							<tr><td colspan=5>陽世報恩人，一心至誠，虔誠供養。仰叩根本上師蓮生活佛、諸佛菩薩，宣經禮慚，代亡者慚悔洗滌前衍，祈求薦拔超度亡靈，暨九玄七祖、過往冤親債主、童嬰靈障、諸位靈覺。須仗佛光接引，超拔往生功德道場，西方極樂淨土；一心恭請, 南摩西方教主阿彌陀佛。並祈求佛光加持，俾陽居眾，安居樂業、強身健體、如意吉祥、佛緣增長。伏乞慈悲，虔誠是禱。</td></tr>
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