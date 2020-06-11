<!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="<?= base_url('asset/css/jquery.dataTables.css');?>">

<!-- DataTables -->
<script type="text/javascript" charset="utf8" src="<?= base_url('asset/js/jquery.dataTables.js');?>"></script>

<script type="text/javascript" language="javascript" class="init">

$(document).ready(function() {
    select_box = 'select[name="example_length"]';

    function get_data(){
        $('#example').dataTable( {
            "processing": true,
            "serverSide": true,
            "iDisplayLength": $(select_box).val(),
            "order": [[ 2, "desc" ]], // Default ordering Last Login DESC
            "destroy":true,
            "sDom": '<"top"l>rt<"bottom"ip><"clear">',
            "ajax": {
                "url": "index",
                "type": "POST",
                "dataType": "json",
            },
            "columns": [
                { "data": "<?= $list_column; ?>" }
            ],
            "columnDefs": [
                { "orderable": false, "targets": [3] }
            ],
        } );
        $.ajax({ success:function(d){console.log(d)}});
    }
    get_data();



} );
</script>

<div id="page-wrapper">

    <div class="row">
        <div class="box">
            <div class="col-lg-8 col-lg-offset-2 text-center">
                <h1><?= lang('title_index'); ?></h1>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="box">
            <div class="col-lg-10 col-lg-offset-1">
                <table id="example" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                    <thead>
                        <tr class="info">
                            <td><b><?= lang('tbl_email'); ?></b></td>
                            <td><b><?= lang('tbl_chapter'); ?></b></td>
                            <td><b><?= lang('tbl_last_login'); ?></b></td>
                            <td><b><?= lang('tbl_activity'); ?></b></td>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    
    <!-- /.row -->
</div>