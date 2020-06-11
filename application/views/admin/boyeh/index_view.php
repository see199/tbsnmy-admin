<style>
table.attendance>tbody>tr>td{
	width:25%;
}
</style>

<a class='btn btn-default' href='<?= site_url('admin/attendance/update_log');?>'>Update Log</a>
<form class='form-inline' method='post' action='<?= site_url('admin/attendance'); ?>'>
	<div class='form-group'>
		<input type='date' class='form-control' value='<?= $start_date; ?>' name='start_date'>
		<input type='date' class='form-control' value='<?= $end_date; ?>' name='end_date'>
		<input type='submit' class='btn btn-default' value='Filter' />
	</div>
</form>

<div class='container'>

	<div class="col-md-6 col-md-offset-3">	

		<?php foreach($data as $id => $employee): if(sizeof($employee['data']) > 0): ?>
		<table class='table table-striped table-bordered table-hover table-condensed attendance'>
			<thead>
				<tr class='info'><th colspan=4><?= $employee['name']; ?> (ID: <?= $id; ?>) -- Late count: <?= count($employee['data']) ;?></th></tr>
				<tr><th>Date</th><th>Check-In</th><th>Check-Out</th><th>Work Hour</th></tr>
			</thead>
			<tbody>
				<?php foreach($employee['data'] as $date => $type): ?>
				<tr <?= ($type['hrs'] < 8) ? 'class="danger"' : ''; ?>>
					<td><?= $date . ' ('. date('D',strtotime($date)).')'; ?></td>
					<td><?= $type['in']; ?></td>
					<td><?= isset($type['out']) ? $type['out'] : '-'; ?></td>
					<td><?= ($type['hrs'] == 0) ? '-' : $type['hrs'] . ' Hrs'; ?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<?php endif; endforeach; ?>
	</div>

</div>