<script>
    function update_registrant(form_id){
        $('#msg_'+form_id).show();
        $('#msg_'+form_id).html("Updating...");
        $('#first_name_'+form_id).prop('readonly',true);
        $('#last_name_'+form_id).prop('readonly',true);
        $('#zoom_link_'+form_id).prop('readonly',true);

        $.ajax({
            url: '<?= base_url('admin/agm/ajax_add_registrant_personal'); ?>',
            type: 'post',
            data: {
                'first_name': $('#first_name_'+form_id).val(),
                'last_name': $('#last_name_'+form_id).val(),
                'email': $('#email_'+form_id).val(),
                'nric': $('#nric_'+form_id).val(),
                'contact_id': $('#contact_id_'+form_id).val(),
                'name_chinese': $('#name_chinese_'+form_id).val(),
                'name_malay': $('#name_malay_'+form_id).val(),
                'position': $('#position_'+form_id).val(),
                'membership_id': '列席',
            },
            success: function( data, textStatus, jQxhr ){
                me = JSON.parse(data);
                
                if(me.success){
                    $('#zoom_link_'+form_id).val(me.zoom_link);
                }
                $('#msg_'+form_id).html(me.msg);
                $('#first_name_'+form_id).prop('readonly',false);
                $('#last_name_'+form_id).prop('readonly',false);
                $('#zoom_link_'+form_id).prop('readonly',false);

                if(form_id == 'new') location.reload();
            }
        });
    }

    function delete_registrant(form_id){
        $('#msg_'+form_id).show();
        $('#msg_'+form_id).html("Removing...");
        $('#first_name_'+form_id).prop('readonly',true);
        $('#last_name_'+form_id).prop('readonly',true);
        $('#zoom_link_'+form_id).prop('readonly',true);

        $.ajax({
            url: '<?= base_url('admin/agm/ajax_del_registrant'); ?>',
            type: 'post',
            data: {
                'registrant_id': $('#registrant_id_'+form_id).val(),
                'email': $('#email_'+form_id).val()
            },
            success: function( data, textStatus, jQxhr ){
                me = JSON.parse(data);
                
                if(me.success){
                    $('#zoom_link_'+form_id).val('removed');
                }
                $('#msg_'+form_id).html(me.msg);
                $('#first_name_'+form_id).val('removed');
                $('#last_name_'+form_id).val('removed');
                $('#zoom_link_'+form_id).val('removed');
            }
        });
    }

    $(document).ready(function(){
        $( "#name_chinese_new" ).keyup(function() {
            $( "#last_name_new" ).val(this.value);
        });
    });
</script>

<div id="page-wrapper">

    <div class="row">
        <div class="box">
            <div class="col-lg-8 col-lg-offset-2 text-center form-inline">
                <h1>會員大會 ZOOM 登記記錄<br />列席</h1>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="box">
            <div class="col-lg-10 col-lg-offset-1">
                <br />
                <table id="example" class="display table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead><tr class="info">
                        <td><b>列席者資料</b></td>
                        <td><b>Zoom 資料</b></td>
                        <td></td>
                    </tr></thead>
                    <tbody><?php foreach($list as $r): 
                            $form_id = $r['nric'];?><tr>
                        <td>
                            <table class='table table-striped table-bordered table-hover'>
                                <tr><td>姓名</td><td>英文姓名</td><td>NRIC</td><td>備註</td></tr>
                                <tr>
                                    <td><input type='text' class='form-control' name='name_chinese' id='name_chinese_<?=$form_id;?>' value='<?=$r['name_chinese'];?>'></td>
                                    <td><input type='text' class='form-control' name='name_malay' id='name_malay_<?=$form_id;?>' value='<?=$r['name_malay'];?>'></td>
                                    <td><input type='text' class='form-control' name='nric' id='nric_<?=$form_id;?>' value='<?=$r['nric'];?>'></td>
                                    <td><input type='text' class='form-control' name='position' id='position_<?=$form_id;?>' value='<?=$r['position'];?>'></td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table class='table table-striped table-bordered table-hover'>
                                <tr>
                                    <td class='col-lg-2'>First Name</td>
                                    <td class='col-lg-2'>Last Name</td>
                                    <td class='col-lg-4'>Email</td>
                                    <td class='col-lg-4'>Zoom Link <small>登記：<?= $r['reg_date']; ?></small></td>
                                </tr>
                                <tr>
                                    <td><input type='text' class='form-control' name='first_name' id='first_name_<?=$form_id;?>' value='<?=$r['first_name'];?>'></td>
                                    <td><input type='text' class='form-control' name='last_name' id='last_name_<?=$form_id;?>' value='<?=$r['last_name'];?>'></td>
                                    <td><input type='text' class='form-control' name='email' id='email_<?=$form_id;?>' value='<?=$r['email'];?>' <?= $r['registrant_id'] ? " readonly" : ""; ?>></td>
                                    <td><input type='text' class='form-control' name='zoom_link' id='zoom_link_<?=$form_id;?>' value='<?=$r['zoom_link'];?>'></td>
                                </tr>
                            </table>
                            <span id="msg_<?=$form_id;?>" class='badge' style='display:none;'> </span>
                            <input type='hidden' class='form-control' name='registrant_id' id='registrant_id_<?=$form_id;?>' value='<?=$r['registrant_id'];?>'>
                            <input type='hidden' class='form-control' name='contact_id' id='contact_id_<?=$form_id;?>' value='<?=$r['contact_id'];?>'>
                            
                        </td>
                        <td>
                            <button style="margin:5px;" class='btn btn-warning' onclick="update_registrant('<?=$form_id;?>')">Update</button>
                            <button style="margin:5px;" class='btn btn-danger' onclick="delete_registrant('<?=$form_id;?>')">Delete</button>
                        </td>
                    </tr><?php endforeach;?></tbody>

                    <tfoot><tr class='success'>
                        <td>
                            <table class='table table-striped table-bordered table-hover'>
                                <tr><td>姓名</td><td>英文姓名</td><td>NRIC</td><td>備註</td></tr>
                                <tr>
                                    <td><input type='text' class='form-control' name='name_chinese' id='name_chinese_new'></td>
                                    <td><input type='text' class='form-control' name='name_malay' id='name_malay_new'></td>
                                    <td><input type='text' class='form-control' name='nric' id='nric_new'></td>
                                    <td><input type='text' class='form-control' name='position' id='position_new'></td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table class='table table-striped table-bordered table-hover'>
                                <tr>
                                    <td class='col-lg-2'>First Name</td>
                                    <td class='col-lg-2'>Last Name</td>
                                    <td class='col-lg-4'>Email</td>
                                    <td class='col-lg-4'>Zoom Link</td>
                                </tr>
                                <tr>
                                    <td><input type='text' class='form-control' name='first_name' id='first_name_new' value='列席-'></td>
                                    <td><input type='text' class='form-control' name='last_name' id='last_name_new'></td>
                                    <td><input type='text' class='form-control' name='email' id='email_new'></td>
                                    <td><input readonly type='text' class='form-control' name='zoom_link' id='zoom_link_new'></td>
                                </tr>
                            </table>
                            <span id="msg_new" class='badge' style='display:none;'> </span>
                            <input type='hidden' class='form-control' name='registrant_id' id='registrant_id_new'>
                            <input type='hidden' class='form-control' name='contact_id' id='contact_id_new'>
                            
                        </td>
                        <td>
                            <button style="margin:5px;" class='btn btn-success' onclick="update_registrant('new')">Register</button>
                        </td>
                    </tr></tfoot>
                </table>
            </div>
        </div>
    </div>
    
    <!-- /.row -->
</div>