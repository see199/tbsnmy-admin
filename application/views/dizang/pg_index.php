<!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="<?= base_url('asset/css/jquery.dataTables.css'); ?>">

<!-- DataTables -->
<script type="text/javascript" charset="utf8" src="<?= base_url('asset/js/jquery.dataTables.js'); ?>"></script>

<script type="text/javascript" language="javascript" class="init">

function load_box(id) {

    if(id){
        // If has Dizang ID, get details
        $.ajax({
            type:"POST",
            url:"<?= base_url('dizang/index/ajax_detail');?>",
            data:{dizang_id:id}
        }).done(function(data){
            var res=jQuery.parseJSON(data);
            for(const k in res) $('#'+`${k}`).val(`${res[k]}`);
            
        });
    }else{
        // If no ID, reset all input - for add new
        $('#deceased_type').val("");
        $('#remarks').val("");
        $('#myModalBody input').each(function(id){
            $(this).val("");
        });
    }
}

function post_data(){

    if(!$('#deceased_type').val()){
        alert("類別不可置空！請選擇類別。");
        return false;
    }

    var data = {
        deceased_type : $('#deceased_type').val(),
        remarks : $('#remarks').val(),
    };
    
    $('#myModalBody input').each(function(id){
        data[$(this).attr('id')] = $(this).val();
    });

    $.ajax({
        type:"POST",
        url: "<?= base_url('dizang/index/ajax_update'); ?>",
        data:data
    }).done(function(data) {
        get_data();
    });
    
}

function get_data(){
    var table = $('#example').dataTable( {
        processing: true,
        serverSide: true,
        destroy:true,
        iDisplayLength: 25,
        order: [[ 5, "desc" ]],
        ajax: {
            "url": "<?= base_url('dizang/index/ajax_get_list'); ?>",
            "type": "POST",
            "data":{
                table_name: "tbs_dizang",
                date_from: $('#date_from').val(),
                date_to: $('#date_to').val(),
            }
        },
        columns:[
            {data: 'reg_loc'},
            {data: '$.name'},
            {data: 'applicant_contact'},
            {data: 'deceased_type'},
            {data: '$.deceased'},
            {data: 'date'},
            {data: '$.viewmodal'}
        ],
        columnDefs:[
            {targets:[2,4,6],orderable: false}
        ],
    });

    $('#myDataTable_filter').children().children().after(" <span class=\"glyphicon glyphicon-search\" aria-hidden=\"true\"></span>");
}

function changeDate(){
    $('#date_from').val('0001-01-01');
    $('#date_to').val('0001-01-01');
    get_data();
}

$(document).ready(function() {

    get_data();

    $('#date_from').on('change',get_data);
    $('#date_to').on('change',get_data);

    $('#checkAll').on('click',function(){
        const allCheckbox = document.querySelectorAll('.cbox');
        console.log(allCheckbox);
        allCheckbox.forEach(checkbox => {
            checkbox.checked = true;}
        );
    });

});

document.addEventListener('DOMContentLoaded', () => {

});
</script>
<?php 
//Others can only view & search, cannot edit & save
//$cs_email = array('cs@tbsn.my','share@tbsn.my');
$admin_email = array('see199@gmail.com','tandy@tbsn.my','ahlian76@gmail.com','boyeone@gmail.com','xuan.lianxuan@gmail.com');
?>

<div id="page-wrapper">

    <div class="row">
        <div class="box">
            <div class="col-lg-8 col-lg-offset-2 text-center">
                <h1>地藏殿列表</h1>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="box">
            <div class="col-lg-10 col-lg-offset-1">

                <?php if(in_array($google_email,$admin_email)): ?>
                <div class='text-right'>
                    <a href="javascript:void(0)" onclick="load_box()" data-toggle="modal" data-target="#myModal" class='btn btn-success'><i class="fa fa-plus" aria-hidden="true"></i> 新增</a>
                </div>
                <hr />
                <?php endif; ?>
                
                <form method="post" class="form-inline">
                    安奉日期：<input class='form-control' type="date" id="date_from" name="date_from" value="<?= $date_from; ?>"> 至
                    <input class='form-control' type="date" id="date_to" name="date_to"value="<?= $date_to; ?>">
                    <a href="#" onclick="changeDate()">點擊這裡查詢沒有輸入【安奉日期】</a>
                </form>

                <form target="_print" class='form-inline' method="post" action="<?=base_url('dizang/index/print');?>">
                <table id="example" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                    <thead>
                        <tr class="info">
                            <td><b>牌位號碼</b></td>
                            <td><b>陽居報恩人</b></td>
                            <td><b>聯絡電話</b></td>
                            <td><b>類別</b></td>
                            <td><b>安奉</b></td>
                            <td><b>安奉日期</b></td>
                            <td><b><a href="#" id="checkAll">全選</a></b></td>
                        </tr>
                    </thead>
                </table>

                <?php if(in_array($google_email,$admin_email)): ?>
                打印：
                <input type="submit" class='btn btn-success' name="action" value="儀軌">
                <input type="submit" class='btn btn-success' name="action" value="備錄">
                <input type="submit" class='btn btn-success' name="action" value="牌位">
                <input type="submit" class='btn btn-success' name="action" value="地址">
                <?php endif; ?>
                
                </form>
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
                <h4 class="modal-title" id="myModalLabel">地藏王功德殿靈位供奉表格</h4>
            </div>
            <div class="modal-body" id="myModalBody">
                <table class="table display form-inline">
                    <tr><td nowrap>安奉日期</td><td><input class="form-control" id="date" type="date"/> 牌位 <input class="form-control" id="reg_loc"/> 供養金 <input class="form-control" id="amount"/></td></tr>
                    <tr><td>姓名</td><td><input class="form-control" id="applicant_name"/> <input class="form-control" id="applicant_name_2"/></td></tr>
                    <tr><td>聯絡號碼</td><td><input class="form-control" id="applicant_contact"/> <input class="form-control" id="applicant_contact_2"/> <input class="form-control" id="applicant_contact_3"/></td></tr>
                    <tr><td>地址</td><td>
                        <input type="hidden" id="dizang_id"/>
                        <input style="width: 100%;"class="form-control" id="address1" placeholder="Address 1"/>
                        <input style="width: 100%;"class="form-control" id="address2" placeholder="Address 2"/>
                        <input class="form-control" id="city" placeholder="City"/>
                        <input class="form-control" id="postcode" placeholder="Postcode"/>
                        <input class="form-control" id="state" placeholder="State"/>
                    </td></tr>
                    <tr><td>類別</td><td>
                        <?= form_dropdown('deceased_type', $deceased_type, '', array('id' => 'deceased_type', 'class' => 'form-control')); ?>
                        安奉 <input class="form-control" id="deceased_name"/> <input class="form-control" id="deceased_name_2"/></td></tr>
                    <tr><td>備註</td><td>
                        <textarea style="width: 100%;" rows=5 class="form-control" id="remarks" placeholder="Remarks"></textarea>
                    </td></tr>
                </table>
            </div>
            
            <?php if(in_array($google_email,$admin_email)): ?>
            <div class="modal-footer">
                <button type="button" id="btn_modal_update" class="btn btn-success" onclick="post_data(0);" data-dismiss="modal"><i class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></i> Update</button>
                <button type="button" id="btn_modal_cancel" class="btn btn-warning" data-dismiss="modal"><i class="glyphicon glyphicon-remove" aria-hidden="true"></i> Cancel</button>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>