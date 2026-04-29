<!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="<?= base_url('asset/css/jquery.dataTables.css'); ?>">

<!-- DataTables -->
<script type="text/javascript" charset="utf8" src="<?= base_url('asset/js/jquery.dataTables.js'); ?>"></script>

<script type="text/javascript" language="javascript" class="init">

$(document).ready(function() {
    select_box = 'select[name="example_length"]';

    var table = $('#example').DataTable( {
        "processing": true,
        "serverSide": true,
        "iDisplayLength": 50,
        "order": [[ 0, "desc" ]],
        "destroy":true,
        "sDom": '<"top"lf>rt<"bottom"ip><"clear">',
        "ajax": {
            "url": "<?=base_url('/admin/contact/dharma/'.$dtype);?>",
            "type": "POST",
            "dataType": "json",
            "data": function ( d ) {
                if($('#f_exam_batch').length) d.f_exam_batch = $('#f_exam_batch').val();
                if($('#f_status').length) d.f_status = $('#f_status').val();
                if($('#f_member').length) d.f_member = $('#f_member').val();
            }
        },
        "columns": [
            { "data": "name_chinese" },
            { "data": "name_malay" },
            { "data": "name_dharma" },
            { "data": "nric" },
            { "data": "phone_mobile" },
            { "data": "email" },
            { "data": "status" },
            { "data": "membership_id" },
            { "data": "contact_id" }
        ],
        "columnDefs": [
            { "orderable": false, "targets": [8] }
        ],
    } );

    $('.filter_input').on('change keyup', function() {
        table.draw();
    });
} );
</script>

<div id="page-wrapper">

    <div class="row">
        <div class="box">
            <div class="col-lg-8 col-lg-offset-2 text-center">
                <h1><?=lang('glb_nav_dharma');?> (<?= $this->config->item('tbs')['dharma_staff'][strtoupper($dtype)];?>)</h1>
            </div>
        </div>
    </div>

    <?php if(in_array($dtype, array('js','zj', 'ss', 'jss', 'fs'))): ?>
    <div class="row">
        <div class="box">
            <div class="col-lg-10 col-lg-offset-1">
                <div class="well">
                    <div class="row">
                        <?php if(in_array($dtype, array('js','zj'))): ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>第幾屆考取</label>
                                <input type="number" id="f_exam_batch" class="form-control filter_input" placeholder="Batch Number">
                            </div>
                        </div>
                        <?php endif; ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>狀態</label>
                                <select id="f_status" class="form-control filter_input">
                                    <option value="">- All -</option>
                                    <?php foreach($staff_status as $k => $v): ?>
                                    <option value="<?= $k; ?>"><?= $v; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>馬密總會員</label>
                                <select id="f_member" class="form-control filter_input">
                                    <option value="">- All -</option>
                                    <option value="Y">Yes</option>
                                    <option value="N">No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="row">
        <div class="box">
            <div class="col-lg-10 col-lg-offset-1">
                <table id="example" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                    <thead>
                        <tr class="info">
                            <td><b>姓名</b></td>
                            <td><b>國語姓名</b></td>
                            <td><b>法號</b></td>
                            <td><b>NRIC</b></td>
                            <td><b>聯絡</b></td>
                            <td><b>電郵</b></td>
                            <td><b>狀態</b></td>
                            <td><b>馬密總會員</b></td>
                            <td><b></b></td>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    
    <!-- /.row -->
</div>