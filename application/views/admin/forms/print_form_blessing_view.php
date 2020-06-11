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
				<div class='row'><img class='img-responsive' src='<?= base_url('assets/img/forms/main-fahui-header.jpg'); ?>'></div>

				<div class='row'>
					<div class='col-md-12'>
						<div class='h1 text-left'>護摩法會主祈報名表格 （祈福報名）</div>
						<div>凡贊助 RM 1000 主祈者，可向主壇上師敬獻哈達以表敬意、包含護摩曼陀羅木一支</div>
					</div>
				</div>
				<br />
				<div class='row'><div class='col-md-12'>

					<table class='table'><tr>
						<td><label for='contact_name'>主祈者姓名: </label><?=$l['contact_name'];?></td>
						<td class='text-right'><strong>法會日期：2016年10月30日</strong></td>
					</tr></table>

					<table class='table table-bordered print-friendly' id='form-table'>
						<thead>
							<tr><th style='width:180px'>祈福者姓名</th><th style='width:650px'>現居地址</th><th style='width:170px'>祈願</th></tr>
						</thead>
						<tbody><?php foreach($l['list'] as $list):?>
							<tr><td><?=$list['name'];?></td><td><?=$list['address'];?></td><td><?=$list['wish'];?></td></tr>
						<?php endforeach;?></tbody>
						<tfoot>
							<tr><td colspan=3>上列善信，一心頂禮，虔具供養，叩仰諸佛菩薩，南無華光自在佛蓮生聖尊佛光加持，消除業障，化解災厄，身強體健，運程亨通，增幅延壽，萬事如意，一切安樂，心願圓滿。</td></tr>
						</tfoot>
					</table>

				</div></div>

				<table class='table table-bordered print-friendly'><tbody>
					<tr><th width="150px">For Office Use</th><td rowspan=2></td></tr>
					<tr><th>法會主祈編號</th></tr>
					<tr><th colspan=2>備註:</th></tr>
					<tr><th colspan=2><br /><br /><br /></th></tr>
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