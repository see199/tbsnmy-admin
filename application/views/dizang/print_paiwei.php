<style>
@media print,screen{
	body {
		margin: 0.5cm;
		size: A4;
	}
	p, div{
		font-size: 12pt;
		text-align: center;
	}
	div.tbl{
		height: 47mm;
		width: 24mm;
		background-size: 24mm 47mm;
		display: inline-table;
		position: relative;
		font-family: KaiTi;
		font-weight: bold;

	}
	.tbl-亡{ background-image: url(<?=base_url('asset/images/dizang-plate-wangzhe.png');?>); }
	.tbl-壽{ background-image: url(<?=base_url('asset/images/dizang-plate-wangzhe.png');?>); }
	.tbl-水{ background-image: url(<?=base_url('asset/images/dizang-plate-shuiziling.png');?>); }
	.tbl-冤{ background-image: url(<?=base_url('asset/images/dizang-plate-yuanqin.png');?>); }
	.tbl-祖{ background-image: url(<?=base_url('asset/images/dizang-plate-zuxian.png');?>); }
	.tbl-土{ background-image: url(<?=base_url('asset/images/dizang-plate-tudigong.png');?>); }
	.tbl-拿{ background-image: url(<?=base_url('asset/images/dizang-plate-datogong.png');?>); }
	.tbl>small{
		font-size: 8pt;
		width: 100%;
		display: inline-block;
		height: 4mm;
		margin-top: 2pt;
	}
	.tbl>div.content{
		height: 30mm;
		position: absolute;
		top: 5mm;
		width: 24mm;
	}

	.content>div.single{
		font-size: 22pt;
		width: 22pt;
		display: inline-table;
	}

	.content>div.single>small{
		font-size: 8pt;
		width: 22pt;
		display: inline-table;
		margin-top: -1mm;
	}

	.content>div.double{
		font-size: 20pt;
		width: 18pt;
		display: inline-table;
		margin: 5pt 1pt;
	}

	.content>div.life{
		font-size: 18pt;
		width: 22pt;
		display: inline-table;
	}

	.content>div.zuxian{
		font-size: 14pt;
		width: 22pt;
		display: inline-table;
		margin-top: 1.7mm;
	}
	* {-webkit-print-color-adjust:exact;}

}
</style>

<body>

	<?php foreach($list as $i): ?>
	<div class="tbl tbl-<?=$i['deceased_type'];?>">
		<div class='content'>

			<!-- 單人亡者/壽 -->
			<?php if($i['deceased_type'] == '亡' &&!$i['deceased_name_2']): ?>
			<div class='single'><?=preg_replace("/蓮花/","<small>蓮花</small>",$i['deceased_name']);?></div>

			<!-- 雙人亡者/壽 -->
			<?php elseif($i['deceased_type'] == '亡' && $i['deceased_name_2']): ?>
			<div class='double'><?=$i['deceased_name'];?></div>
			<div class='double'><?=$i['deceased_name_2'];?></div>

			<!-- 壽 -->
			<?php elseif($i['deceased_type'] == '壽'): ?>
			<div class='life'><?=$i['deceased_name'];?></div>

			<!-- 祖先 -->
			<?php elseif($i['deceased_type'] == '祖'): ?>
			<div class='zuxian'><?=$i['deceased_name'];?></div>

			<!-- 雙人(陽上) -->
			<?php elseif($i['applicant_name_2']): ?>
			<div class='double'><?=$i['applicant_name'];?></div>
			<div class='double'><?=$i['applicant_name_2'];?></div>

			<!-- 單人(陽上) & 其他 -->
			<?php else: ?>
			<div class='single'><?=$i['applicant_name'];?></div>

			<?php endif; ?>

		</div>
	</div>
	<?php endforeach; ?>
</body>

<script>/*window.print();*/</script>