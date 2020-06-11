<div class="container-fluid">
	<div class='row'><div class='col-md-10 col-md-offset-1'><img class="img-responsive" src='<?= base_url('assets/img/forms/header.jpg'); ?>'></div></div>

	<div class='row'>
		<div class='col-md-10 col-md-offset-1'>

			<!-- div class='col-md-10 col-md-offset-1'><div class='alert alert-warning text-center'>網站正在建設當中。敬請等候...</div></div -->

			<?php if($success_message): ?>
			<div id='success_message' class='row'>
				<div class='col-md-10 col-md-offset-1'>
					<div class='alert alert-success text-center' role='alert'>
						<span class='glyphicon glyphicon-ok-circle' style='font-size:50px;'></span>
						<br /><strong>成功發送！</strong>感謝您的報名，我們會為您處理！阿彌陀佛。
					</div>
				</div>
			</div>
			<?php endif; ?>

			<div class="row">
				<div class="col-md-12">
					<div class="alert alert-warning">
						<table><tbody><tr>
							<td style="padding: 5px 30px 5px 10px;"><i style="font-size: 50px;" class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></i></td>
							<td><strong>公告：</strong><br />为确保报名表格顺利的处理，网络报名已经截止。<br />感谢大家的护持。谢谢。</td>
						</tr></tbody></table>
					</div>
				</div>
			</div>

			<div class='row'>
				<?php foreach($form as $id => $f): ?>
				<div class='col-md-4'>
					<div class='thumbnail'>
						<img src="<?= base_url('assets/img/forms/'.$id.'.jpg'); ?>">
						<div class='caption'>
							<h3><?= $f['title']; ?></h3>
							<p><?= $f['desc']; ?></p>
							<p class='text-right'>
								<?php if($id != 'sangha'): ?>
								<a disabled href="<?= base_url('forms/'.$id.'_normal'); ?>" class="btn btn-default" role="button">填写<?=$f['type'];?>表格</a>
								<a disabled href="<?= base_url('forms/'.$id); ?>" class="btn btn-default" role="button">填写主祈者表格</a>
								<?php else: ?>
								<a disabled href="<?= base_url('forms/'.$id); ?>" class="btn btn-default" role="button">填写<?=$f['type'];?>表格</a>
								<?php endif; ?>
							</p>
						</div>
					</div>
				</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</div>