<script>
function load_box(id){

    $('#myModalLabel').html('更新保險資料');
    $('#btn_modal_update').show();
    $('#myModalBody').html('Loading data...');

    $.ajax({
        type:"POST",
        url: "<?= base_url('admin/insurance/ajax_get_view'); ?>/"+id,
        data:{}
    }).done(function(data) {
        var res = jQuery.parseJSON(data);
        $('#myModalBody').html(res.html);
    });
}

function post_data(){

    var data = {
        contact_id     : $('#contact_id').val(),
        group_medical  : $("input[name='group_medical']:checked").val(),
        group_pa       : $("input[name='group_pa']:checked").val(),
        a_max          : $('#a_max').val(),
        a_max_price    : $('#a_max_price').val(),
        policy_number  : $('#policy_number').val(),
        entry_age      : $('#entry_age').val(),
        date_issue     : $('#date_issue').val(),
        date_effective : $('#date_effective').val(),
        date_expiry    : $('#date_expiry').val()
    };

    $('#myModalBody').html('<div class="text-center"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i><span class="sr-only"><br />Saving...</span></div>');
    $('#btn_modal_update').hide();
    $.ajax({
        type:"POST",
        url: "<?= base_url('admin/insurance/ajax_post'); ?>",
        data:data
    }).done(function(data) {
        var res = jQuery.parseJSON(data);
        $('#myModalBody').html(res.html);
        setTimeout(function(){ location.reload();},2000);
    });
    
}

</script>

<div id="page-wrapper">

    <div class="row">
        <div class="box">
            <div class="col-lg-8 col-lg-offset-2 text-center">
                <h1>保險（出家眾）</h1>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="box">
            <div class="col-lg-10 col-lg-offset-1">
                <table id="example" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                    <thead>
                        <tr class="info">
                            <td colspan="<?= sizeof($stats['age_group']); ?>" align="center"><b>出家眾人數 / 年齡層</b></td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <?php foreach($stats['age_group'] as $k => $v):?>
                            <td align="center"><b><?= $k; ?></b></td>
                            <?php endforeach;?>
                        </tr>
                        <tr>
                            <?php foreach($stats['age_group'] as $k => $v):?>
                            <td align="center"><?= $v; ?></td>
                            <?php endforeach;?>
                        </tr>
                        <tr>
                            <td colspan="<?= ceil(sizeof($stats['age_group'])/2); ?>" align="center">
                                <table class="display table table-striped table-bordered table-hover" cellspacing="0">
                                    <tr class="info"><td colspan=3 align="center"><b>加保總計</b></td></tr>
                                    <tr class="info"><td><b>種類</b></td><td><b>總人數</b></td><td><b>總額 (RM)</b></td></tr>
                                    <?php foreach($stats['amax_total'] as $type => $v): ?>
                                        <tr><td><?=$type;?></td><td><?=$v['pax'];?></td><td><?=number_format($v['amount'],2);?></td></tr>
                                    <?php endforeach;?>
                                </table>
                            </td>
                        
                            <td colspan="<?= sizeof($stats['age_group']) - ceil(sizeof($stats['age_group'])/2); ?>" align="center">
                                <table class="display table table-striped table-bordered table-hover" cellspacing="0">
                                    <tr class="info"><td colspan=2 align="center"><b>保險人數</b></td></tr>
                                    <tr class="info"><td><b>Group Medical</b></td><td><b>Group PA</b></td></tr>
                                    <tr><td><?=$stats['group_medical'];?></td><td><?=$stats['group_pa'];?></td></tr>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="box">
            <div class="col-lg-10 col-lg-offset-1">
                <table id="example" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                    <thead>
                        <tr class="info">
                            <td><b>No.</b></td>
                            <td><b>國語姓名</b></td>
                            <td><b>法號</b></td>
                            <td><b>NRIC</b></td>
                            <td><b>年齡</b></td>
                            <td><b>Group Medical</b></td>
                            <td><b>Group PA</b></td>
                            <td><b>Additional</b></td>
                            <td><b>Price (RM)</b></td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $total_max=0; foreach($insurance as $k => $i): $total_max += $i['a_max_price'];?>
                            <tr>
                                <td><?= $k+1; ?>.</td>
                                <td><a href='javascript:void(0)' onclick="load_box(<?= $i['contact_id'];?>)" data-toggle="modal" data-target="#myModal">
                                    <?= $i['name_malay'];?>
                                </a></td>
                                <td><?= $i['name_dharma'];?></td>
                                <td><?= $i['nric'];?></td>
                                <td><?= date_diff(date_create($i['dob']),date_create('today'))->y;?></td>
                                <td style="background:<?= ($i['group_medical']) ? "#dff0d8" : "#f2dede";?>"><?= ($i['group_medical']) ? "有" : "無";?></td>
                                <td style="background:<?= ($i['group_pa']) ? "#dff0d8" : "#f2dede";?>"><?= ($i['group_pa']) ? "有" : "無";?></td>
                                <td><?= $i['a_max'];?></td>
                                <td><?= ($i['a_max_price'] > 0) ? number_format($i['a_max_price'],2) : '-';?></td>
                            </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- /.row -->
</div>


<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Modal title</h4>
            </div>
            <div class="modal-body" id="myModalBody">
            </div>
            <div class="modal-footer">
                <button type="button" id="btn_modal_update" class="btn btn-success" onclick="post_data();"><i class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></i> Update</button>
                <button type="button" id="btn_modal_cancel" class="btn btn-warning" data-dismiss="modal"><i class="glyphicon glyphicon-remove" aria-hidden="true"></i> Cancel</button>
            </div>
        </div>
    </div>
</div>