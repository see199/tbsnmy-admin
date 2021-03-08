<div id="page-wrapper">

    <div class="row">
        <div class="box">
            <div class="col-lg-8 col-lg-offset-2 text-center form-inline">
                <h1>會員大會出席表（總數）</h1>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="box">
            <div class="col-lg-10 col-lg-offset-1">
                <br />
                <table id="example" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                    <thead>
                        <tr class="info">
                            <td><b>會員編號</b></td>
                            <td><b>道場</b></td>
                            <?php foreach($years as $y): ?>
                            <td><b><a href="<?= base_url('admin/agm/year/'.$y); ?>"><?=$y;?></a></b>
                                <small>
                                    <br /> 總道場：<?= $total['chapter'][$y];?>
                                    <br /> 總人數：<?= $total['chapter_member'][$y];?>
                                </small>
                            </td>
                            <?php endforeach;?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($chapter as $chapter_id => $c): ?>
                            <tr>
                                <td><?= $c['membership_id'];?></td>
                                <td><?= $c['name_chinese'];?></td>
                                <?php foreach($years as $y): ?>
                                <td><?=@($c['agm'][$y]['total']) ? $c['agm'][$y]['total'] : '-';?></td>
                                <?php endforeach;?>
                            </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- /.row -->
</div>