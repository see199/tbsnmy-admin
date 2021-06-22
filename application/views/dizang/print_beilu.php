<style>
@media print{
	body {
		margin: 0.5cm;
		size: A4;
	}
	p, td{
		font-size: 12pt;
		padding: 0pt 5pt;
		text-align: center;
	}
	h1{
		font-size: 16pt;
	}
	td{
		border: 1pt solid #000;
	}

}
</style>

<body>
	<h1><b>功德殿之靈位備錄（冤親債主、纏身靈、水子靈、亡者、祖先）</b></h1>

	<table border="1" cellspacing="0">
	<tr>
		<td>牌位<br />號碼</td>
		<td>陽居報恩人</td>
		<td>冤親<br />債主</td>
		<td>水子<br />靈</td>
		<td>安奉<br />亡者、歷代祖先</td>
		<td>安奉日期</td>
		<td>聯絡電話</td>
	</tr>
	<?php foreach($list as $i): ?>
	<tr>
		<td><?=$i['reg_loc'];?></td>
		<td><?=$i['applicant_name'] . ' '. $i['applicant_name_2'] ;?></td>
		<td><?= ($i['deceased_type'] == '冤') ? "&#9733;" : "" ?></td>
		<td><?= ($i['deceased_type'] == '水') ? "&#9733;" : "" ?></td>
		<td><?= (!in_array($i['deceased_type'],array('水','冤'))) ? $i['deceased_name'] . ' '. $i['deceased_name_2'] : "" ?></td>
		<td><?= $i['date']; ?></td>
		<td><?= $i['applicant_contact']; ?></td>
	</tr>
	<?php endforeach; ?>
	</table>

</body>

<script>window.print();</script>