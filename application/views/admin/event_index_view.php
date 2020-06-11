<!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="<?= base_url('asset/css/jquery.dataTables.css');?>">

<!-- DataTables -->
<script type="text/javascript" charset="utf8" src="<?= base_url('asset/js/jquery.dataTables.js');?>"></script>

<script type="text/javascript" language="javascript" class="init">

$(document).ready(function() {
    var chapter_url = '<?= $chapter_url; ?>';
    select_box = 'select[name="example_length"]';

    function get_data(check_all){
        $('#example').dataTable( {
            "processing": true,
            "serverSide": true,
            "iDisplayLength": $(select_box).val(),
            "order": [[ 0, "desc" ]], // Default ordering Created at DESC
            "destroy":true,
            "sDom": '<"top"l>rt<"bottom"ip><"clear">',
            "ajax": {
                "url": "index",
                "type": "POST",
                "data": {
                    "chapter_url": chapter_url,
                    "show_all": (check_all) ? '1' : '00', 
                },
                "dataType": "json",
            },
            "columns": [
                { "data": "<?= $list_column; ?>" }
            ],
            "columnDefs": [
                { "orderable": false, "targets": [2,3] }
            ],
        } );
        $.ajax({ success:function(d){console.log(d)}});
    }
    get_data(false);

    $("#check_all").click(function(){
        var me = $(this);
        get_data(me.is(':checked'));
    });

} );
</script>

<div id="page-wrapper">

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><?= lang('title_index'); ?></h1>
        </div>
    </div>

    <div class="row">
        <div class="box">
            <div class="col-lg-10 col-lg-offset-1">
                <label><input type='checkbox' id='check_all'> <?= lang('chk_show_all'); ?></label>
                <table id="example" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                    <thead>
                        <tr class="info">
                            <td><b><?= lang('tbl_start_date'); ?></b></td>
                            <td><b><?= lang('tbl_end_date'); ?></b></td>
                            <td><b><?= lang('tbl_master_name'); ?></b></td>
                            <td><b><?= lang('tbl_event_name'); ?></b></td>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    
    <!-- /.row -->
</div>