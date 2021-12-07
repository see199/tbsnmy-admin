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
        $('#wenxuan_email').val('');
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
        $('#wenxuan_email').val(subscriber.wenxuan_email);
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
            data:{package:subscriber.package,wenxuan_contact:subscriber.wenxuan_contact}
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
        wenxuan_email    : $('#wenxuan_email').val(),
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

function delete_data(){
    
    if(!confirm("確認要刪除嗎？")) return;
    $.ajax({
        type:"POST",
        url: "<?= base_url('wenxuan/lists/ajax_delete_contact'); ?>",
        data:{wenxuan_id : $('#wenxuan_id').val()}
    }).done(function(data) {
        var res = jQuery.parseJSON(data);
        setTimeout(function(){ location.reload();},200);
    });

}

</script>

<div id="page-wrapper">

    <div class="row">
        <div class="box">
            <div class="col-lg-8 col-lg-offset-2 text-center">
                <h1>文宣功德主名單（ <?=$year;?> 年）</h1>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="box">
            <div class="col-lg-8 col-lg-offset-2">

                
                <table id="example" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                    <thead>
                        <tr class="info">
                            <td rowspan=2><b>名字<br /><small>** 點擊可查看 / 更新資料 **</small></b></td>
                            <td rowspan=2><b>聯絡</b></td>
                            <td colspan=<?=sizeof($total);?>><b>功德主方案</b></td>
                            <td rowspan=2><b>已發贈品</b></td>
                            <td rowspan=2><b>完成付款</b></td>
                            <td rowspan=3><b><?=$year;?>年<br />報名表格</b></td>
                        </tr>
                        <tr class="info">
                            <?php foreach($total as $package_id => $t):?>
                            <td><b><?= $package[$package_id]['package_name'].' (RM '.$package[$package_id]['package_amount'].')'; ?></b></td>
                            <?php endforeach;?>
                        </tr>
                        <tr class="success">
                            <td colspan=2><b>總數</b></td>
                            <?php foreach($total as $package_id => $t):?>
                            <td><b><?= $t; ?></b></td>
                            <?php endforeach;?>
                            <td><b><?= $gift_sent; ?></b></td>
                            <td><b><?= $payment_done; ?></b></td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($list as $c): ?>
                            <tr>
                                <td><textarea style="display:none;"><?= json_encode($c); ?></textarea>
                                    <a href='javascript:void(0)' onclick="load_box($(this).prev().val())" data-toggle="modal" data-target="#myModal"><?= $c['wenxuan_name']; ?></a></td>
                                <td><?= $c['wenxuan_contact']; ?></td>
                                <?php foreach($total as $package_id => $t):?>
                                <td><b><?= ($package_id == $c['package'][$year]['package_id']) ? '<i class="fa fa-check" aria-hidden="true"></i>' : ""; ?></b></td>
                                <?php endforeach;?>
                                <td><b><?= $c['package'][$year]['gift_taken'] ? '<i class="fa fa-check" aria-hidden="true"></i>' : ""; ?></b></td>
                                <td><b><?= $c['package'][$year]['payment_done'] ? '<i class="fa fa-check" aria-hidden="true"></i>' : "" ?></b></td>
                                <td><?php foreach($c['package'] as $pyear => $p):?>
                                        <?php $form_full_url = ($pyear == $year ) ? $form_url.$p['md5_id'] : "" ?>
                                        <a href="<?= $form_full_url; ?>" target="_blank">view</a>
                                    <?php endforeach; ?>
                                </td>

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
                    <tr><td>電郵</td><td><input class="form-control" id="wenxuan_email"/></td></tr>
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
                        <tr class='success text-center'><td colspan=4><b>功德主方案</b></td></tr>
                        <tr class='success'><td style='width:25%'><b>年份 & 方案</b></td><td style='width:15%'><b>有效日期</b></td><td class='md-col-6'><b>備註</b></td><td style='width:10%'><b>Status</b></td></tr>
                    </thead>
                    <tbody id="package_tbody">
                    </tbody>
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