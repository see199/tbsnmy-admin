<div id="page-wrapper">

    <div class="row">
        <div class="box">
            <div class="col-lg-8 col-lg-offset-2 text-center form-inline">
                <h1>Verified User List</h1>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="box">
            <div class="col-lg-10 col-lg-offset-1 text-right form-inline">
                <a class="btn btn-success"  href='<?= base_url();?>admin/api/verify_setting'><i class="fa fa-cog"></i> Setting</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="box">
            <div class="col-lg-10 col-lg-offset-1">

                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item"><a class="nav-link" id="verified-tab" data-toggle="tab" href="#verified" role="tab" aria-controls="verified" aria-selected="true">Verified <small>(<?= count($users['verified']);?>)</small></a></li>
                    <li class="nav-item"><a class="nav-link" id="non-tab" data-toggle="tab" href="#non" role="tab" aria-controls="non" aria-selected="true">Non-Verified <small>(<?= count($users['non']);?>)</small></a></li>
                </ul>

                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade" id="verified" role="tabpanel" aria-labelledby="verified-tab"><h2>(Verified)</h2>
                        <table id="example" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                            <thead>
                                <tr class="info">
                                    <td><b>No</b></td>
                                    <td><b>姓名</b></td>
                                    <td><b>電郵</b></td>
                                    <td><b>電話</b></td>
                                    <td><b>地址</b></td>
                                    <td><b>Verified Date</b></td>
                                    <td><b>Lucky No</b></td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($users['verified'] as $k => $u): ?>
                                    <tr>
                                        <td><?= $k+1;?></td>
                                        <td><?= $u['name'];?></td>
                                        <td><?= $u['email'];?></td>
                                        <td><?= $u['phone'];?></td>
                                        <td><?= $u['address'];?></td>
                                        <td><?= $u['verified_date'];?></td>
                                        <td><?= $u['lucky_no'];?></td>
                                    </tr>
                                <?php endforeach;?>
                            </tbody>
                        </table>
                    </div>

                    <div class="tab-pane fade" id="non" role="tabpanel" aria-labelledby="non-tab"><h2>(Non-Verified)</h2>
                        <table id="example" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                            <thead>
                                <tr class="info">
                                    <td><b>No</b></td>
                                    <td><b>電郵</b></td>
                                    <td><b>Lucky No</b></td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($users['non'] as $k => $u): ?>
                                    <tr>
                                        <td><?= $k+1;?></td>
                                        <td><?= $u['email'];?></td>
                                        <td><?= $u['lucky_no'];?></td>
                                    </tr>
                                <?php endforeach;?>
                            </tbody>
                        </table>
                    </div>
                </div>




                <br />
                
            </div>
        </div>
    </div>
    
    <!-- /.row -->
</div>