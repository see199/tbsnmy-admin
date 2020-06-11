<script>
function load_box(me){

    if(me == 'new'){

        $('#form_packages').hide();

        $('#myModalLabel').html("添加新個人資料");
        $('#btn_modal_update_r').show();
        $('#btn_modal_update').hide();

        $('#wenxuan_id').val('');
        $('#wenxuan_name').val('');
        $('#wenxuan_contact').val('');
        $('#address1').val('');
        $('#address2').val('');
        $('#city').val('');
        $('#postcode').val('');
        $('#state').val('');
        $('#country').val('');
        $('#tbnews_free').val('');
        $('#tbnews_paid').val('');
        $('#randeng_free').val('');
        $('#randeng_paid').val('');
        $('#remarks').val('');
        $('#update_date').html('');
        $('#update_by').html('');

    }else{

        $('#form_packages').show();

        subscriber = jQuery.parseJSON(me);
        $('#myModalLabel').html("查看 / 更改資料");
        $('#btn_modal_update_r').show();
        $('#btn_modal_update').show();

        $('#wenxuan_id').val(subscriber.wenxuan_id);
        $('#wenxuan_name').val(subscriber.wenxuan_name);
        $('#wenxuan_contact').val(subscriber.wenxuan_contact);
        $('#address1').val(subscriber.wenxuan_address1);
        $('#address2').val(subscriber.wenxuan_address2);
        $('#city').val(subscriber.wenxuan_city);
        $('#postcode').val(subscriber.wenxuan_postcode);
        $('#state').val(subscriber.wenxuan_state);
        $('#country').val(subscriber.wenxuan_country);
        $('#tbnews_free').val(subscriber.tbnews_free);
        $('#tbnews_paid').val(subscriber.tbnews_paid);
        $('#randeng_free').val(subscriber.randeng_free);
        $('#randeng_paid').val(subscriber.randeng_paid);
        $('#remarks').val(subscriber.remarks);
        $('#update_date').html(subscriber.update_date);
        $('#update_by').html(subscriber.update_by);

        $.ajax({
            type:"POST",
            url: "<?= base_url('wenxuan/lists/ajax_generate_package_tbody'); ?>",
            data:subscriber.package
        }).done(function(data) {
            var res = jQuery.parseJSON(data);
            $('#package_tbody').html(res.html);
        });
    }
}

function post_data(refresh_page){

    var data = {
        wenxuan_id       : $('#wenxuan_id').val(),
        wenxuan_name     : $('#wenxuan_name').val(),
        wenxuan_contact  : $('#wenxuan_contact').val(),
        wenxuan_address1 : $('#address1').val(),
        wenxuan_address2 : $('#address2').val(),
        wenxuan_city     : $('#city').val(),
        wenxuan_postcode : $('#postcode').val(),
        wenxuan_state    : $('#state').val(),
        wenxuan_country  : $('#country').val(),
        tbnews_free      : $('#tbnews_free').val(),
        tbnews_paid      : $('#tbnews_paid').val(),
        randeng_free     : $('#randeng_free').val(),
        randeng_paid     : $('#randeng_paid').val(),
        remarks          : $('#remarks').val(),
    };

    $("#form_packages").each(function(){
        $(this).find(':input').each(function(){
            data['package_'+$(this).attr("id")] = $(this).val();
        });
        $(this).find(':input[type=checkbox]').each(function(){
            data['package_'+$(this).attr("id")] = $(this).prop('checked');
        });
    });
    
    $.ajax({
        type:"POST",
        url: "<?= base_url('wenxuan/lists/ajax_contact_update'); ?>",
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
                <h1>個人訂閱</h1>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="box text-right">
            <div class="col-lg-10 col-lg-offset-1">
                <button type="button" id="btn_modal_add_new" class="btn btn-info" onclick="load_box('new');" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus-square fa-fw" aria-hidden="true"></i> 添加新個人檔案</button>
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
                            <td rowspan=2><b>名字<br /><small>** 點擊可查看 / 更新資料 **</small></b></td>
                            <td rowspan=2><b>聯絡</b></td>
                            <td colspan=2><b>真佛報</b></td>
                            <td colspan=2><b>燃燈</b></td>
                            <td colspan=2><b>文宣功德主</b></td>
                        </tr>
                        <tr class="info">
                            <td><b>贈送</b></td>
                            <td><b>訂閱</b></td>
                            <td><b>贈送</b></td>
                            <td><b>訂閱</b></td>
                            <td><b><?= date('Y'); ?></b></td>
                            <td><b><?= date('Y') - 1; ?></b></td>
                        </tr>
                        <tr class="success">
                            <td colspan=2><b>總數</b></td>
                            <td><b><?= $total['tbnews_free']; ?></b></td>
                            <td><b><?= $total['tbnews_paid']; ?></b></td>
                            <td><b><?= $total['randeng_free']; ?></b></td>
                            <td><b><?= $total['randeng_paid']; ?></b></td>
                            <td><b></b></td>
                            <td><b></b></td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($list as $c): ?>
                            <tr>
                                <td><input type=hidden value='<?= json_encode($c); ?>' />
                                    <a href='javascript:void(0)' onclick="load_box($(this).prev().val())" data-toggle="modal" data-target="#myModal"><?= $c['wenxuan_name']; ?></a></td>
                                <td><?= $c['wenxuan_contact']; ?></td>
                                <td><?= $c['tbnews_free']; ?></td>
                                <td><?= $c['tbnews_paid']; ?></td>
                                <td><?= $c['randeng_free']; ?></td>
                                <td><?= $c['randeng_paid']; ?></td>
                                <td><?= isset($c['package'][date('Y')]['package_id']) ? $package[$c['package'][date('Y')]['package_id']]['package_name'] : '-'; ?></td>
                                <td><?= isset($c['package'][date('Y')-1]['package_id']) ? $package[$c['package'][date('Y')-1]['package_id']]['package_name'] : '-'; ?></td>
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
                <div class="text-right"><small><b>Last update:</b> <span id="update_date"></span> by <span id="update_by"></span></small></div>
                <table class="table display">
                    <tr><td>聯絡人</td><td><input class="form-control" id="wenxuan_name"/></td></tr>
                    <tr><td>聯絡號碼</td><td><input class="form-control" id="wenxuan_contact"/></td></tr>
                    <tr><td>地址</td><td>
                        <input type="hidden" id="wenxuan_id"/>
                        <input type="hidden" id="chapter_id"/>
                        <input class="form-control" id="address1" placeholder="Address 1"/>
                        <input class="form-control" id="address2" placeholder="Address 2"/>
                        <input class="form-control" id="city" placeholder="City"/>
                        <input class="form-control" id="postcode" placeholder="Postcode"/>
                        <input class="form-control" id="state" placeholder="State"/>
                        <input class="form-control" id="country" placeholder="Country"/>
                    </td></tr>
                    <tr><td>備註</td><td>
                        <textarea rows=5 class="form-control" id="remarks" placeholder="Remarks"></textarea>
                    </td></tr>
                </table>
                <table class="table display form-inline">
                    <tr><td>
                        真佛報 贈送: <input onClick="this.select();" class="form-control" id="tbnews_free" style="width:50px;"/>
                        訂閱: <input onClick="this.select();" class="form-control" id="tbnews_paid" style="width:50px;"/>
                    </td><td>
                        燃燈 贈送: <input onClick="this.select();" class="form-control" id="randeng_free" style="width:50px;"/>
                        訂閱: <input class="form-control" id="randeng_paid" style="width:50px;"/></tr>
                </table>
                
                <table class="table display table-bordered" id="form_packages">
                    <thead>
                        <tr class='success text-center'><td colspan=3><b>功德主方案</b></td></tr>
                        <tr class='success'><td><b>年份 & 方案</b></td><td><b>有效日期</b></td><td><b>Status</b></td></tr>
                    </thead>
                    <tbody id="package_tbody">
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" id="btn_modal_update" class="btn btn-success" onclick="post_data(0);" data-dismiss="modal"><i class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></i> Update</button>
                <button type="button" id="btn_modal_update_r" class="btn btn-success" onclick="post_data(1);" data-dismiss="modal"><i class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></i> Update & Refresh</button>
                <button type="button" id="btn_modal_cancel" class="btn btn-warning" data-dismiss="modal"><i class="glyphicon glyphicon-remove" aria-hidden="true"></i> Cancel</button>
            </div>
        </div>
    </div>
</div>