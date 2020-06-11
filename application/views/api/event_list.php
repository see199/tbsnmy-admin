<div class="container">
	<div class="row">
		<div class="box">
			<div class='col-md-12'>
				<table class='table table-bordered table-hover table-striped'>
					<thead><tr class='info active'>
						<th>日期</th><th>弘法人員</th><th>活動名稱</th><th>道場</th>
					</tr></thead>
					<tbody>
						<?php foreach($event as $e): ?>
						<?php
							$event_url = $site_url.'/%E4%B8%8A%E5%B8%AB%E5%BC%98%E6%B3%95%E8%A1%8C%E7%A8%8B%E8%A1%A8/details/'.$e->chapter.'/'.$e->event_id.'.html';
						?>
						<tr>
							<td><?= substr($e->start_date,0,-9); ?></td>
							<td><?= $e->master_name; ?></td>
							<td><a href="<?= $event_url; ?>" target="_event"><?= $e->name; ?></a></td>
							<td><?= $e->chapter_name; ?></td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>