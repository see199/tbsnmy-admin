<!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="<?= base_url('asset/css/jquery.dataTables.css'); ?>">

<!-- DataTables -->
<script type="text/javascript" charset="utf8" src="<?= base_url('asset/js/jquery.dataTables.js'); ?>"></script>
<script type="text/javascript" language="javascript" class="init">

function get_data(){
    var table = $('#example').dataTable( {
        processing: true,
        serverSide: true,
        destroy:true,
        iDisplayLength: 25,
        ajax: {
            "url": "<?= base_url('admin/game/ajax_get_list'); ?>",
            "type": "POST",
            "data":{
                table_name: "tbs_game",
            }
        },
        columns:[
            {data: 'id'},
            {data: 'name'},
            {data: 'email'},
            {data: 'phone'},
            {data: 'create_date'}
        ],
    });
}

$(document).ready(function() {
    get_data();
} );
</script>

<div id="page-wrapper">

    <div class="row">
        <div class="box">
            <div class="col-lg-8 col-lg-offset-2 text-center">
                <h1>2021年中秋活動</h1>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="box">
            <div class="col-lg-10 col-lg-offset-1">


                <table id="example" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                    <thead>
                        <tr class="info">
                            <td><b>ID</b></td>
                            <td><b>姓名</b></td>
                            <td><b>聯絡電話</b></td>
                            <td><b>電郵</b></td>
                            <td><b>日期</b></td>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    
    <!-- /.row -->
</div>
