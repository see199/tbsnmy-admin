<style>
@media print,screen{
	body {
		margin: 0.5cm;
		size: A4;
	}
	p, div{
		font-size: 11pt;
		padding: 0pt 5pt;
		text-align: center;
	}
	div.tbl{
		border: 1pt solid #000;
		width: 55mm;
		display: inline-table;
		padding: 5pt 5pt;
	}

}
</style>

<body>

	<?php foreach($list as $i): ?>
	<div class="tbl">
		<?=$i['applicant_name'] . ' '. $i['applicant_name_2'] ;?>
		<br /><?=$i['address1'];?>
		<?php if($i['address2']): ?><br /><?=$i['address2'];?><?php endif; ?>
		<br /><?=$i['postcode']." ".$i['city'].", ".$i['state'];?>
	</div>
	<?php endforeach; ?>

</body>

<script>window.print();</script>