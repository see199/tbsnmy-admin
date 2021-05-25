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
                'membership_id': $('#membership_id_'+form_id).val(),
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
</script>

<div id="page-wrapper">

    <div class="row">
        <div class="box">
            <div class="col-lg-8 col-lg-offset-2 text-center form-inline">
                <h1>會員大會 ZOOM 登記記錄<br />個人會員</h1>
                出席：<?= $total_attends;?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="box">
            <div class="col-lg-10 col-lg-offset-1">
                <br />
                <table id="example" class="display table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr class="info">
                            <td><b>會員資料</b></td>
                            <td><b>Zoom Details</b></td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($members as $m): 
                            $form_id = $m['member_id'];
                            $r = $m['registrant'];
                        ?>
                            <tr>
                                <td width="20%" nowrap><?= $m['name_chinese'];?>
                                    <br /><?= $m['name_malay']; ?>
                                    <br /><?= $m['nric']; ?>
                                    <br />會員編號：<?= $m['membership_id'];?>
                                    <small><br /><br />登記：<?= $r['reg_date']; ?></small>
                                </td>
                                <td>
                                <table class='table table-striped table-bordered table-hover'>
                                    <tbody>
                                    <tr>
                                        <td width="10%" nowrap style="line-height: 2.3;" <?= $r['registrant_id'] ? "class='success'" : ""; ?>>
                                            First Name:
                                            <br />Last Name:
                                            <br />Email:
                                            <br />Zoom Link:
                                        </td>
                                        <td>
                                            <input type='text' class='form-control' name='first_name' id='first_name_<?=$form_id;?>' value='<?=$r['first_name'];?>'>
                                            <input type='text' class='form-control' name='last_name' id='last_name_<?=$form_id;?>' value='<?=$r['last_name'];?>'>
                                            <input type='text' class='form-control' name='email' id='email_<?=$form_id;?>' value='<?=$r['email'];?>' <?= $r['registrant_id'] ? " readonly" : ""; ?>>
                                            <input type='hidden' class='form-control' name='registrant_id' id='registrant_id_<?=$form_id;?>' value='<?=$r['registrant_id'];?>'>

                                            <input type='hidden' class='form-control' name='nric' id='nric_<?=$form_id;?>' value='<?=$m['nric'];?>'>
                                            <input type='hidden' class='form-control' name='contact_id' id='contact_id_<?=$form_id;?>' value='<?=$m['member_id'];?>'>
                                            <input type='hidden' class='form-control' name='name_chinese' id='name_chinese_<?=$form_id;?>' value='<?=$m['name_chinese'];?>'>
                                            <input type='hidden' class='form-control' name='name_malay' id='name_malay_<?=$form_id;?>' value='<?=$m['name_malay'];?>'>
                                            <input type='hidden' class='form-control' name='membership_id' id='membership_id_<?=$form_id;?>' value='<?=$m['membership_id'];?>'>

                                            <input type='text' class='form-control' name='zoom_link' id='zoom_link_<?=$form_id;?>' value='<?=$r['zoom_link'];?>'>
                                            <?php if($r['registrant_id']) : ?>
                                            <button class='btn btn-warning' onclick="update_registrant('<?=$form_id;?>')">Update</button>
                                            <button class='btn btn-danger' onclick="delete_registrant('<?=$form_id;?>')">Delete</button>
                                            <?php else: ?>
                                            <button class='btn btn-success' onclick="update_registrant('<?=$form_id;?>')">Register</button>
                                            <?php endif; ?>
                                            <span id="msg_<?=$form_id;?>" class='badge' style='display:none;'> </span>

                                        </td>
                                    </tr></tbody>
                                </table>
                                </td>
                            </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- /.row -->
</div>