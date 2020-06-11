<div class="container">
    <div class="row">
        <div class="box">
            <div class="col-md-8 text-center">
                <h1>全月活動 - <?php echo (!$temple) ? "（其他道場）" : $list_menu[$temple];?></h1>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="box">
            <div class="col-md-8 text-center">
                <!-- Navigation -->
                <nav class="navbar navbar-default" role="navigation">
                    <div class="container-fluid">
                        <div class="navbar-header">
                          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                            <span class="sr-only">Toggle navigation</span>
                            其他道場
                            <span class="caret"></span>
                          </button>
                          <a class="navbar-brand" href="#"><?= $list_menu[$temple]; ?></a>
                        </div>
                        <!-- Collect the nav links, forms, and other content for toggling -->
                        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                            <ul class="nav navbar-nav">
                                <?php foreach($list_menu as $tk => $tv): ?>
                                <?php if($tk == $temple): ?>
                                    <li class="active"><a href='#'><?= $tv;?></a></li>
                                <?php else: ?>
                                    <li><a href='<?= base_url('admin/monthly_calendar/'.$tk);?>'><?= $tv;?></a></li>
                                <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <!-- /.navbar-collapse -->
                    </div>
                    <!-- /.container -->
                </nav>
            </div>
        </div>
    </div>

    <?php if($temple): ?>
    <?php if(sizeof($display)){ foreach($display as $date): $img_url = $url_location.'/'.$temple.'/'.$date.'.jpg'; ?>
    <div class="row">
        <div class="box">
            <div class="col-md-8 text-center">
                <a target="monthly" href="<?= $img_url; ?>"><img src="<?= $img_url; ?>" style="max-width:95%" alt="<?php echo $date;?>" /></a>
                <br /><a target="monthly" href="<?= $img_url; ?>">- 按此下載 - </a>
            </div>
        </div>
    </div>
    <?php endforeach;}else{ ?> - 暫無更新 -<?php }?>
    <?php endif; ?>

</div>

<!-- jQuery -->
<script src="<?= base_url();?>assets/js/jquery.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="<?= base_url();?>assets/js/bootstrap.min.js"></script>