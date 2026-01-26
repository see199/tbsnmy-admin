<script>
function load_box(me){

    $('#btn_modal_update').show();

    if(me == 'new'){

        $('#myModalLabel').html('添加新方案');

        $('#package_id').val('');
        $('#year').val('');
        $('#package_name').val('');
        $('#package_amount').val('');
        $('#package_description').val('');
        $('#parcel_content').val('');
        $('#parcel_value').val('');
        $('#weight_in_kg').val('');


    }else{
        package = jQuery.parseJSON(me);

        $('#myModalLabel').html(package.year + "年: " + package.package_name);
        $('#btn_modal_update').show();

        $('#package_id').val(package.package_id);
        $('#year').val(package.year);
        $('#package_name').val(package.package_name);
        $('#package_amount').val(package.package_amount);
        $('#package_description').val(package.package_description);
        $('#parcel_content').val(package.parcel_content);
        $('#parcel_value').val(package.parcel_value);
        $('#weight_in_kg').val(package.weight_in_kg);

    }
}

function post_data(refresh_page){

    var data = {
        package_id          : $('#package_id').val(),
        year                : $('#year').val(),
        package_name        : $('#package_name').val(),
        package_amount      : $('#package_amount').val(),
        package_description : $('#package_description').val(),
        parcel_content      : $('#parcel_content').val(),
        parcel_value        : $('#parcel_value').val(),
        weight_in_kg        : $('#weight_in_kg').val()

    };

    //$('#btn_modal_update').hide();
    $.ajax({
        type:"POST",
        url: "<?= base_url('wenxuan/lists/ajax_package_update'); ?>",
        data:data
    }).done(function(data) {
        var res = jQuery.parseJSON(data);
        if(refresh_page == 1)
        setTimeout(function(){ location.reload();},2000);
    });
    
}

</script>

<div id="page-wrapper">

    <div class="row">
        <div class="box">
            <div class="col-lg-8 col-lg-offset-2 text-center">
                <h1>文宣功德主方案</h1>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="box text-right">
            <div class="col-lg-10 col-lg-offset-1">
                <button type="button" id="btn_modal_add_new" class="btn btn-info" onclick="load_box('new');" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus-square fa-fw" aria-hidden="true"></i> 添加新方案</button>
                <br /><br />
            </div>
        </div>
    </div>

    <div class="row">
        <div class="box">
            <div class="col-lg-10 col-lg-offset-1">
                <table id="example" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                    <thead>
                        <tr class="info">
                            <td><b>年份</b></td>
                            <td><b>配套方案<br /><small>** 點擊可查看 / 更新資料 **</small></b></td>
                            <td><b>供養金（RM）</b></td>
                            <td><b>簡介</b></td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($package as $p): ?>
                            <tr>
                                <td><?= $p['year']; ?></td>
                                <td>
                                    <input type="hidden" id="package_id"/>
                                    <input type=hidden value='<?= json_encode($p); ?>' />
                                    <a href='javascript:void(0)' onclick="load_box($(this).prev().val())" data-toggle="modal" data-target="#myModal">
                                    <?= $p['package_name']; ?></a></td>
                                <td><?= $p['package_amount']; ?></td>
                                <td><?= $p['package_description']; ?></td>
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
                    <tr><td>年份</td><td><input class="form-control" id="year"/></td></tr>
                    <tr><td>配套方案</td><td><input class="form-control" id="package_name"/></td></tr>
                    <tr><td>供養金(RM)</td><td><input class="form-control" id="package_amount"/></td></tr>
                    <tr><td>簡介</td><td>
                        <textarea rows=5 class="form-control" id="package_description"></textarea>
                    </td></tr>
                    <tr><td>包裹內容 (EasyParcel)</td><td><input class="form-control" id="parcel_content"/></td></tr>
                    <tr><td>包裹價值 (EasyParcel)</td><td><input class="form-control" id="parcel_value"/></td></tr>
                    <tr><td>重量 (kg) (EasyParcel)</td><td><input class="form-control" id="weight_in_kg"/></td></tr>

                </table>
            </div>
            <div class="modal-footer">
                <button type="button" id="btn_modal_update" class="btn btn-success" onclick="post_data(1);" data-dismiss="modal"><i class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></i> Update</button>
                <button type="button" id="btn_modal_cancel" class="btn btn-warning" data-dismiss="modal"><i class="glyphicon glyphicon-remove" aria-hidden="true"></i> Cancel</button>
            </div>
        </div>
    </div>
</div>