<script>
function load_box(me){

   subscriber = jQuery.parseJSON(me);

    $('#myModalLabel').html(subscriber.name_chinese);
    $('#btn_modal_update').show();

    $('#mishu_details').html("地址："+subscriber.mishu_address+"<br />通訊地址："+subscriber.mishu_mailing_address+"<br />聯絡人："+subscriber.mishu_contact);
    $('#wenxuan_id').val(subscriber.wenxuan_id);
    $('#chapter_id').val(subscriber.cid);
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
    $('#gmbook').val(subscriber.gmbook);
    $('#remarks').val(subscriber.remarks);
    $('#update_date').html(subscriber.update_date);
    $('#update_by').html(subscriber.update_by);
}

function post_data(refresh_page){

    var data = {
        chapter_id       : $('#chapter_id').val(),
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
        gmbook           : $('#gmbook').val()
    };

    //$('#btn_modal_update').hide();
    $.ajax({
        type:"POST",
        url: "<?= base_url('wenxuan/lists/ajax_chapter_update'); ?>",
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
                <h1>道場訂閱</h1>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="box">
            <div class="col-lg-10 col-lg-offset-1">
                <table id="example" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                    <thead>
                        <tr class="info">
                            <td rowspan=2><b>編號</b></td>
                            <td rowspan=2><b>名字<br /><small>** 點擊可查看地址及聯絡資料 / 更新資料 **</small></b></td>
                            <td colspan=2><b>真佛報</b></td>
                            <td colspan=2><b>燃燈</b></td>
                            <td rowspan=2><b>文集</b></td>
                        </tr>
                        <tr class="info">
                            <td><b>贈送</b></td>
                            <td><b>訂閱</b></td>
                            <td><b>贈送</b></td>
                            <td><b>訂閱</b></td>
                        </tr>
                        <tr class="success">
                            <td colspan=2><b>總數</b></td>
                            <td><b><?= $total['tbnews_free']; ?></b></td>
                            <td><b><?= $total['tbnews_paid']; ?></b></td>
                            <td><b><?= $total['randeng_free']; ?></b></td>
                            <td><b><?= $total['randeng_paid']; ?></b></td>
                            <td><b><?= $total['gmbook']; ?></b></td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($list as $c): ?>
                            <tr>
                                <td><?= $c['membership_id']; ?></td>
                                <td><input type=hidden value='<?= json_encode($c); ?>' />
                                    <a href='javascript:void(0)' onclick="load_box($(this).prev().val())" data-toggle="modal" data-target="#myModal">
                                    <?= $c['name_chinese']; ?></a></td>
                                <td><?= $c['tbnews_free']; ?></td>
                                <td><?= $c['tbnews_paid']; ?></td>
                                <td><?= $c['randeng_free']; ?></td>
                                <td><?= $c['randeng_paid']; ?></td>
                                <td><?= $c['gmbook']; ?></td>
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
                    <tr><td nowrap>秘書處資料</td><td><span id="mishu_details"></span></td></tr>
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
                        訂閱: <input class="form-control" id="randeng_paid" style="width:50px;"/>
                    </td><td>文集: <input onClick="this.select();" class="form-control" id="gmbook" style="width:50px;"/></td></tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" id="btn_modal_update" class="btn btn-success" onclick="post_data(0);" data-dismiss="modal"><i class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></i> Update</button>
                <button type="button" id="btn_modal_update" class="btn btn-success" onclick="post_data(1);" data-dismiss="modal"><i class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></i> Update & Refresh</button>
                <button type="button" id="btn_modal_cancel" class="btn btn-warning" data-dismiss="modal"><i class="glyphicon glyphicon-remove" aria-hidden="true"></i> Cancel</button>
            </div>
        </div>
    </div>
</div>