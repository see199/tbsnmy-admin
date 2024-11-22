<html><head>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
</head>
<body>
	<div class='row'>
		<div class='col-md-offset-1 col-md-10'>
			<div class='row text-center'>
				<div class='h4'><a href='<?=base_url('api/tbsnews_stats/'.($year-1)); ?>'>&lt;<?= $year-1;?>&lt;</a>  | Year <?= $year; ?> <?php if(date('Y') > $year): ?>| <a href='<?=base_url('api/tbsnews_stats/'.($year+1)); ?>'>&gt;<?= $year+1;?>&gt;</a><?php endif;?></div>
			</div>

			<div class='row text-center'>

				<div class='col-md-offset-1 col-md-10'>
					<div class='h4 text-center'>Total View: <?= $view['total']; ?></div>

					<table class='table table-hover table-bordered table-striped'>
						<thead>
							<tr><th>Month</th><?php for($i=1;$i<13;$i++): ?><th><?=$i;?></th><?php endfor; ?></tr>
							<tr><th>Total</th><?php for($i=1;$i<13;$i++): ?><th><?= isset($view['details'][$i]['total']) ? $view['details'][$i]['total'] : '0';?></th><?php endfor; ?></tr>
						</thead>
						<tbody>
							<?php for($day=1;$day<32;$day++): ?>
							<tr><td class='text-center'><b><?= $day;?></b></td><?php for($i=1;$i<13;$i++):?><td<?php if((int)date('m').(int)date('d') == $i.$day) echo ' style="background-color:#FAA;"' ?>><?= isset($view['details'][$i]['details'][$day]) ? $view['details'][$i]['details'][$day] : '0' ; ?></td><?php endfor; ?>
							<?php endfor;?>
						</tbody>
					</table>
				</div>

				<?php /* 
				<div class='col-md-6'>
					<div class='h4 text-center'>Total Download: <?= $download['total']; ?></div>

					<table class='table table-hover table-bordered table-striped'>
						<thead>
							<tr><th>Month</th><?php for($i=1;$i<13;$i++): ?><th><?=$i;?></th><?php endfor; ?></tr>
							<tr><th>Total</th><?php for($i=1;$i<13;$i++): ?><th><?= isset($download['details'][$i]['total']) ? $download['details'][$i]['total'] : '0';?></th><?php endfor; ?></tr>
						</thead>
						<tbody>
							<?php for($day=1;$day<32;$day++): ?>
							<tr><td class='text-center'><b><?= $day;?></b></td><?php for($i=1;$i<13;$i++):?><td<?php if((int)date('m').(int)date('d') == $i.$day) echo ' style="background-color:#FAA;"' ?>><?= isset($download['details'][$i]['details'][$day]) ? $download['details'][$i]['details'][$day] : '0' ; ?></td><?php endfor; ?>
							<?php endfor;?>
						</tbody>
					</table>
				</div>
				*/ ?>
			</div>


			<div class='row'>
				<table class='table table-hover table-bordered'>
					<thead><tr><th>Issue</th><th>Total</th></tr></thead>
					<tbody>
					<?php foreach($issue as $i => $t): ?>
					<tr><td><?=$i . ' ' . $t['date'];?></td><td><?=$t['total'];?></td></tr>
					<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</body>
</html>
