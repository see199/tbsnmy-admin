<style>
@media print{
	body {
		margin: 0.5cm;
		size: A4;
	}
	p, td{
		font-size: 13pt;
		padding: 5pt;
	}
	td.reg{
		font-size: 15pt;
	}
	h1{
		font-size: 18pt;
	}

}
</style>

<body>
	<h1><b><u><?= date('d-m-Y'); ?></u></b></h1>

	<p>&#9678; 今天為眾靈安奉靈位在般若雷藏寺功德殿，祈求根本傳承上師聖尊蓮生活佛、南摩西方極樂世界阿彌陀佛、南摩大慈大悲觀世音菩薩、南摩大勢至菩薩、南摩大願地藏王菩薩、諸佛菩薩放光加持，安奉靈位儀式一切吉祥圓滿；並祈求佛光加持眾靈業障消除，離苦得樂，見聞佛法，蒙佛接引，早日往生佛國淨土。 &#9678;</p>

	<table border=0>
	<?php foreach($list as $k => $i): ?>
	<tr><td width=90%>
		今有陽居報恩人&nbsp;&nbsp;<b><?=$i['applicant_name'] . ' '. $i['applicant_name_2'] ;?></b>&nbsp;&nbsp;為&nbsp;&nbsp;<b>
		<?php     if($i['deceased_type'] == '亡'): ?>
			<?=$i['deceased_name'] . ' '. $i['deceased_name_2'] ;?>
		<?php elseif($i['deceased_type'] == '祖'): ?>
			<?=$i['deceased_name']?>門堂上歷代祖先
		<?php elseif($i['deceased_type'] == '壽'): ?>
		<?php elseif($i['deceased_type'] == '冤'): ?>
			自身之冤親債主及纏身靈
		<?php elseif($i['deceased_type'] == '水'): ?>
			自身之水子靈
		<?php elseif($i['deceased_type'] == '土'): ?>
			土地公
		<?php elseif($i['deceased_type'] == '拿'): ?>
			拿督公
		<?php endif;?>
		&nbsp;&nbsp;</b>安奉靈位
		<?php if($k == 0): ?> 在般若雷藏寺 &#9678;<?php endif; ?>
		<?php if(sizeof($list)-1 == $k): ?> &#9678;<?php endif; ?>
	</td><td class="reg"><b><?= $i['reg_loc']; ?></b></td></tr>
	<?php endforeach; ?>
	</table>

	<p>感恩根本傳承上師聖尊蓮生活佛、南摩西方極樂世界阿彌陀佛、南摩大慈大悲觀世音菩薩、南摩大勢至菩薩、南摩大願地藏王菩薩、諸佛菩薩放光加持，安位儀式，一切吉祥圓滿。 &#9678;</p>
</body>

<script>window.print();</script>