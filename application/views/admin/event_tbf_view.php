<script>
function load_box(me){

    if(me == 'new'){

        $('#myModalLabel').html("添加新活動");
        $('#btn_modal_update_r').show();
        $('#btn_modal_update').show();

        $('#event_id').val('');
        $('#name').val('');
        $('#title').val('');
        $('#description').val('');
        $('#banner_url').val('');
        $('#date_start').val('');
        $('#date_end').val('');
        $('#event_view').val('');
        $('#stats_view').val('');
        $('#eview').val('').attr("rows", 1);
        $('#sview').val('').attr("rows", 1);

    }else{
        event = jQuery.parseJSON(me);

        $('#myModalLabel').html("查看 / 更改資料");
        $('#btn_modal_update_r').show();
        $('#btn_modal_update').show();

        $('#event_id').val(event.event_id);
        $('#name').val(event.name);
        $('#title').val(event.title);
        $('#description').val(event.description);
        $('#banner_url').val(event.banner_url);
        $('#date_start').val(event.date_start);
        $('#date_end').val(event.date_end);
        $('#event_view').val(event.event_view);
        $('#stats_view').val(event.stats_view);
        $('#eview').val('').attr("rows", 1);
        $('#sview').val('').attr("rows", 1);
    }
}

function post_data(refresh_page){

    var data = {
        event_id    : $('#event_id').val(),
        name        : $('#name').val(),
        title       : $('#title').val(),
        description : $('#description').val(),
        banner_url  : $('#banner_url').val(),
        date_start  : $('#date_start').val(),
        date_end    : $('#date_end').val(),
        event_view  : $('#event_view').val(),
        stats_view  : $('#stats_view').val(),
    };
    
    $.ajax({
        type:"POST",
        url: "<?= base_url('admin/event/ajax_update_tbf'); ?>",
        data:data
    }).done(function(data) {
        var res = jQuery.parseJSON(data);
        $('#title').val(event.title);
        if(refresh_page == 1)
        setTimeout(function(){ location.reload();},2000);
    });
    
}

function delete_data(){
    
    if(!confirm("確認要刪除嗎？")) return;
    $.ajax({
        type:"POST",
        url: "<?= base_url('admin/event/ajax_delete_tbf'); ?>",
        data:{event_id : $('#event_id').val()}
    }).done(function(data) {
        var res = jQuery.parseJSON(data);
        setTimeout(function(){ location.reload();},200);
    });

}

function load_view(view_name_id){

    var view_name = (view_name_id == 'eview') ? $('#event_view').val() : $('#stats_view').val();

    $.ajax({
        type:"POST",
        url: "<?= base_url('admin/event/ajax_load_view'); ?>/"+view_name,
    }).done(function(data) {
        var res = jQuery.parseJSON(data);
        $('#'+view_name_id).val(res.content).attr("rows", 5);
    });
}

function save_view(view_name_id){
    var view_name = (view_name_id == 'eview') ? $('#event_view').val() : $('#stats_view').val();
    var data = {
        view_name : view_name,
        content   : $('#'+view_name_id).val(),
    };

    if(data.content == ''){
        alert("Empty Content! Data not saved!");
        return;
    }

    $.ajax({
        type:"POST",
        url: "<?= base_url('admin/event/ajax_save_view'); ?>/"+view_name,
        data:data
    }).done(function(data) {
        console.log(data);
        if(data == 1) alert("Saved!");
        else alert(data);
    });

}

</script>

<div id="page-wrapper">

    <div class="row">
        <div class="box">
            <div class="col-lg-8 col-lg-offset-2 text-center">
                <h1>宗委會活動</h1>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="box text-right">
            <div class="col-lg-8 col-lg-offset-2">
                <button type="button" id="btn_modal_add_new" class="btn btn-info" onclick="load_box('new');" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus-square fa-fw" aria-hidden="true"></i> 添加新活動</button>
                <br /><br />
            </div>
        </div>
    </div>


    <div class="row">
        <div class="box">
            <div class="col-lg-8 col-lg-offset-2">

                
                <table id="example" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                    <thead>
                        <tr class="info">
                            <td><b>[ID] 活動名字<br /><small>** 點擊可查看 / 更新資料 **</small></b></td>
                            <td><b>Web Title</b></td>
                            <td><b>開始日期</b></td>
                            <td><b>結束日期</b></td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($events as $id => $c): ?>
                            <tr>
                                <input type="hidden" id="event_id" value="<?=$c['event_id'];?>" />
                                <td>[<?=$c['event_id'];?>] <textarea style="display:none;"><?= json_encode($c);?></textarea><a href='javascript:void(0)' onclick="load_box($(this).prev().val())" data-toggle="modal" data-target="#myModal"><?= $c['name']; ?></a></td>
                                <td><?= $c['title']; ?></td>
                                <td><?= $c['date_start']; ?></td>
                                <td><?= $c['date_end']; ?></td>
                            </tr>

                        <?php endforeach; ?>
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
                <table class="table display">
                    <tr><td>活動名字</td><td><input class="form-control" id="name"/></td></tr>
                    <tr><td>Web Title</td><td><input class="form-control" id="title"/></td></tr>
                    <tr><td>Web Description</td><td>
                        <textarea cols=70 rows=10 class="form-control" id="description" placeholder="Description (supports HTML)"></textarea>
                    </td></tr>
                    <tr><td>Banner URL</td><td><input class="form-control" id="banner_url"/></td></tr>
                    <tr><td>開始日期</td><td><input class="form-control" id="date_start" type="date"/></td></tr>
                    <tr><td>結束日期</td><td><input class="form-control" id="date_end" type="date"/></td></tr>
                    <tr><td>View</td><td>
                        <table class="table display"><tr>
                            <td><input class="form-control" id="event_view" placeholder="Public" />
                                <textarea class="form-control" id="eview" rows=5></textarea>
                                <a href='javascript:void(0)' onclick="load_view('eview')">Load View</a> | 
                                <a href='javascript:void(0)' onclick="save_view('eview')">Save View</a>
                            </td>
                            <td><input class="form-control" id="stats_view" placeholder="Stats"/>
                                <textarea class="form-control" id="sview" rows=5></textarea>
                                <a href='javascript:void(0)' onclick="load_view('sview')">Load View</a> | 
                                <a href='javascript:void(0)' onclick="save_view('sview')">Save View</a>
                            </td>
                        </tr></table>
                        
                        </td></tr>
                </table>
                
            </div>
            <div class="modal-footer">
                <button type="button" id="btn_modal_update" class="btn btn-success" onclick="post_data(0);" data-dismiss="modal"><i class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></i> Update</button>
                <button type="button" id="btn_modal_update_r" class="btn btn-success" onclick="post_data(1);" data-dismiss="modal"><i class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></i> Update & Refresh</button>
                <button type="button" id="btn_modal_cancel" class="btn btn-warning" data-dismiss="modal"><i class="glyphicon glyphicon-remove" aria-hidden="true"></i> Cancel</button>
                <button type="button" id="btn_modal_update" class="btn btn-danger" onclick="delete_data();" data-dismiss="modal"><i class="glyphicon glyphicon-remove" aria-hidden="true"></i> DELETE!</button>
            </div>
        </div>
    </div>
</div>