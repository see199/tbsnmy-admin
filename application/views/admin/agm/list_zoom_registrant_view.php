<script>
    function update_registrant(form_id){
        $('#msg_'+form_id).show();
        $('#msg_'+form_id).html("Updating...");
        $('#first_name_'+form_id).prop('readonly',true);
        $('#last_name_'+form_id).prop('readonly',true);
        $('#zoom_link_'+form_id).prop('readonly',true);

        $.ajax({
            url: '<?= base_url('admin/agm/ajax_add_registrant'); ?>',
            type: 'post',
            data: {
                'first_name': $('#first_name_'+form_id).val(),
                'last_name': $('#last_name_'+form_id).val(),
                'email': $('#email_'+form_id).val()
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

    function update_onsite(form_id){
        $('#msg_'+form_id).show();
        $('#msg_'+form_id).html("Updating...");
        $('#first_name_'+form_id).prop('readonly',true);
        $('#last_name_'+form_id).prop('readonly',true);
        $('#zoom_link_'+form_id).prop('readonly',true);

        $.ajax({
            url: '<?= base_url('admin/agm/ajax_chg_to_onsite'); ?>',
            type: 'post',
            data: {
                'first_name': $('#first_name_'+form_id).val(),
                'last_name': $('#last_name_'+form_id).val(),
                'registrant_id': $('#registrant_id_'+form_id).val(),
                'email': $('#email_'+form_id).val()
            },
            success: function( data, textStatus, jQxhr ){
                me = JSON.parse(data);
                
                if(me.success){
                    $('#zoom_link_'+form_id).val('現場出席');
                }
                $('#msg_'+form_id).html(me.msg);
                $('#first_name_'+form_id).prop('readonly',false);
                $('#last_name_'+form_id).prop('readonly',false);
                $('#zoom_link_'+form_id).prop('readonly',false);
            }
        });
    }

    function toggle_chuxi_liexi(form_id){
        $('#msg_'+form_id).show();
        $('#msg_'+form_id).html("Updating...");
        $('#first_name_'+form_id).prop('readonly',true);
        $('#last_name_'+form_id).prop('readonly',true);
        $('#zoom_link_'+form_id).prop('readonly',true);

        $.ajax({
            url: '<?= base_url('admin/agm/ajax_toggle_liexi_chuxi'); ?>',
            type: 'post',
            data: {
                'first_name': $('#first_name_'+form_id).val(),
                'last_name': $('#last_name_'+form_id).val(),
                'registrant_id': $('#registrant_id_'+form_id).val(),
                'membership_id': $('#membership_id_'+form_id).val(),
                'email': $('#email_'+form_id).val()
            },
            success: function( data, textStatus, jQxhr ){
                me = JSON.parse(data);
                
                if(me.success){
                    $('#first_name_'+form_id).val(me.first_name);
                    $('#zoom_link_'+form_id).val(me.zoom_link);
                }
                $('#msg_'+form_id).html(me.msg);
                $('#first_name_'+form_id).prop('readonly',false);
                $('#last_name_'+form_id).prop('readonly',false);
                $('#zoom_link_'+form_id).prop('readonly',false);
            }
        });
    }
</script>
<div id="page-wrapper">

    <div class="row">
        <div class="box">
            <div class="col-lg-8 col-lg-offset-2 text-center form-inline">
                <h1>會員大會 ZOOM 登記記錄<br />團體會員</h1>
                總道場：<?= $total['chapter'];?> | ZOOM 總人數：<?= $total['chapter_member'];?> | 現場總人數：<?= $total['chapter_member_offline'];?>
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
                            <td><b>道場</b></td>
                            <td><b>出席</b></td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($chapter as $chapter_id => $c): ?>
                            <tr>
                                <td width="20%" nowrap><?= $c['name_chinese'];?>
                                    <?= ($c['status'] == 'A') ? "" : " (".$this->config->item('tbs')['chapter_status'][$c['status']].")"; ?>
                                    <br />會員編號：<?= $c['membership_id'];?>
                                </td>
                                <td><?php if(isset($c['registrant'])):?>
                                <table class='table table-striped table-bordered table-hover'>
                                    <thead><tr class='info'><th>代表資料</th><th colspan=2>Zoom Details</th></tr></thead><tbody>
                                <?php foreach($c['registrant'] as $key => $r): $form_id = $chapter_id."_".$key; ?>
                                    <tr>
                                        <td width="20%" nowrap class="<?= ($r['membership_id'] == '列席') ? "" : (($r['zoom_link'] == '現場出席') ? "warning" : "success") ;?>">
                                            <?= $r['name_chinese']; ?>
                                            <br /><?= $r['name_malay']; ?>
                                            <br /><?= $r['nric']; ?>
                                            <br /><a href='https://wa.me/<?= preg_replace('/[+-]/', '', $r['phone_mobile']); ?>' target='wa'><?= $r['phone_mobile']; ?></a>
                                            <br /><?= $r['position']; ?><?= is_numeric($r['membership_id']) ? "" : " (".$r['membership_id'].")"; ?>
                                            <small><br />登記：<?= $r['reg_date']; ?></small>
                                            <?php if(isset($r['qr'])) echo "<br /><img src='".base_url('asset/img/qr_contact/').$r['qr'].".png' height=100>"; ?>
                                        </td>
                                        <td width="10%" nowrap style="line-height: 2.45;">
                                            First Name:
                                            <br />Last Name:
                                            <br />Email:
                                            <br />Zoom Link:
                                        </td>
                                        <td>
                                            <input type='text' class='form-control' name='first_name' id='first_name_<?=$form_id;?>' value='<?=$r['first_name'];?>'>
                                            <input type='text' class='form-control' name='last_name' id='last_name_<?=$form_id;?>' value='<?=$r['last_name'];?>'>
                                            <input type='text' class='form-control' name='email' id='email_<?=$form_id;?>' value='<?=$r['email'];?>' readonly>
                                            <input type='hidden' class='form-control' name='registrant_id' id='registrant_id_<?=$form_id;?>' value='<?=$r['registrant_id'];?>'>
                                            <input type='hidden' class='form-control' name='membership_id' id='membership_id_<?=$form_id;?>' value='<?=$c['membership_id'];?>'>
                                            <input type='text' class='form-control' name='zoom_link' id='zoom_link_<?=$form_id;?>' value='<?=$r['zoom_link'];?>'>
                                            <button class='btn btn-warning' onclick="update_registrant('<?=$form_id;?>')">更新&轉為Zoom</button>
                                            <button class='btn btn-warning' onclick="update_onsite('<?=$form_id;?>')">轉為現場出席</button>
                                            <button class='btn btn-warning' onclick="toggle_chuxi_liexi('<?=$form_id;?>')"><?= ($r['membership_id'] == '列席') ? "列席轉出席" : "出席轉列席" ?></button>
                                            <button class='btn btn-danger' onclick="delete_registrant('<?=$form_id;?>')">Delete</button>
                                            <span id="msg_<?=$form_id;?>" class='badge' style='display:none;'> </span>

                                        </td>
                                    </tr></tbody>
                                <?php endforeach;?>
                                </table>
                                <?php endif; ?></td>
                            </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- /.row -->
</div>