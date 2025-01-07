<script src="https://cdn.tiny.cloud/1/z9rl7jtpy5yw8ykt504dw1z6o3xn2lx1dxs6tdlsek0xansi/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<script>tinymce.init({
    selector:'textarea.additional_info',
    content_css: "https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css",
    


    plugins: 'autolink lists media table',
      toolbar: 'casechange checklist code formatpainter pageembed tablebullist numlist | outdent indent blockquote | undo redo | link unlink image | forecolor | table | hr removeformat ',
      toolbar_mode: 'floating',
      tinycomments_mode: 'embedded',


    image_advtab: false,
    height:'300px',
});</script>
<script src="<?= base_url(); ?>asset/js/colpick.js" type="text/javascript"></script>
<link rel="stylesheet" href="<?= base_url(); ?>asset/css/colpick.css" type="text/css"/>
<script>
$(document).ready(function() {
    $('#picker').colpick({
        layout:'hex',
        submit:0,
        color:'<?= $chapter['minisite']['bgcolor']; ?>',
        onChange:function(hsb,hex,rgb,el,bySetColor) {
            $(el).css('border-color','#'+hex);
            // Fill the text box just if the color was set using the picker, and not the colpickSetColor function.
            if(!bySetColor) $(el).val(hex);
        }
    }).keyup(function(){
        $(this).colpickSetColor(this.value);
    });
});

function toggle_form(){
    $('.form').toggle();
    $('.display').toggle();
}
</script>
<style>
    .form{display:none;}
    .form>.form-group>input{width:-webkit-fill-available;font-size: 16px;}
    #additional_info{
        height:300px;
    }
    .strong_txt{
        font-weight:bold;
    }
    .row-data{
        border-top:1px solid #CCC;
        padding:5px 0;
    }
    #picker {
        border-right:35px solid <?= $chapter['minisite']['bgcolor']; ?>;
    }
</style>

<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><?= lang('title_index'); ?></h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-8 col-lg-offset-2">

            <?php echo form_open_multipart(base_url('admin/chapter/update'),array('class' => 'form-horizontal', 'id' => 'upload_form'));?>
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class='col-xs-12 h3 text-center form-inline'>
                                <div class='display'>
                                    <?= $chapter['name_chinese']; ?>
                                    <?php if(isset($chapter['membership_id'])) echo ' (' . $chapter['membership_id'] . ') '; ?>
                                    <br /><?= $chapter['name_malay']; ?>
                                </div>
                                <div class='form' >
                                    <div class='form-group col-xs-4'>
                                        <input type='text' class='form-control'  name='chapter[name_chinese]' value='<?= $chapter['name_chinese']; ?>' />
                                    </div>
                                    <div class='form-group col-xs-8'>
                                        <input type='text' class='form-control' name='chapter[name_malay]' value='<?= $chapter['name_malay']; ?>' />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">

                    <div class='row'>
                        <div class='col-xs-12 text-right'>
                            <a class='btn btn-info' href='http://chapter.tbsn.my/<?= $chapter['url_name']; ?>' target='_blank'><i class="fa fa-globe"></i> <?= lang('link_view_minisite'); ?></a>
                            <a class='btn btn-info' href='javascript: void(0);' onclick='toggle_form();'><i class="fa fa-pencil-square-o"></i> <?= lang('link_edit_chapter'); ?></a>
                        </div>
                    </div>

                    <div class='row'>&nbsp;</div>

                    <div class='row col-xs-10 col-xs-offset-1'>
                        <div class='h3 strong_txt'><?= lang('col_title_info'); ?></div>
                    </div>

                    <div class='row row-data col-xs-10 col-xs-offset-1'>
                        <div class='col-xs-3 strong_txt'><?= lang('col_gov_register_number'); ?>:</div>
                        <div class='col-xs-9'>
                            <div class='display'><?= $chapter['gov_register_number']; ?></div>
                            <div class='form form-group'><input type='text' class='form-control col-xs-8' name='chapter[gov_register_number]' value='<?= $chapter['gov_register_number']; ?>' /></div>
                        </div>
                    </div>

                    <div class='row row-data col-xs-10 col-xs-offset-1'>
                        <div class='col-xs-3 strong_txt'><?= lang('col_address'); ?>:</div>
                        <div class='col-xs-9'>
                            <div class='display'><?= $chapter['address']; ?></div>
                            <div class='form form-group'><textarea class='form-control col-xs-8' name='chapter[address]'><?= $chapter['address']; ?></textarea></div>
                        </div>
                    </div>
                    <div class='row row-data col-xs-10 col-xs-offset-1'>
                        <div class='col-xs-3 strong_txt'><?= lang('col_postcode'); ?>:</div>
                        <div class='col-xs-9'>
                            <div class='display'><?= $chapter['postcode']; ?></div>
                            <div class='form form-group'><input type='text' class='form-control col-xs-8' name='chapter[postcode]' value='<?= $chapter['postcode']; ?>' /></div>
                        </div>
                    </div>
                    <div class='row row-data col-xs-10 col-xs-offset-1'>
                        <div class='col-xs-3 strong_txt'><?= lang('col_city'); ?>:</div>
                        <div class='col-xs-9'>
                            <div class='display'><?= $chapter['city']; ?></div>
                            <div class='form form-group'><input type='text' class='form-control col-xs-8' name='chapter[city]' value='<?= $chapter['city']; ?>' /></div>
                        </div>
                    </div>
                    <div class='row row-data col-xs-10 col-xs-offset-1'>
                        <div class='col-xs-3 strong_txt'><?= lang('col_state'); ?>:</div>
                        <div class='col-xs-9'>
                            <div class='display'><?= $chapter['state']; ?></div>
                            <div class='form form-group'><?= $form_state; ?></div>
                        </div>
                    </div>
                    <div class='row row-data col-xs-10 col-xs-offset-1'>
                        <div class='col-xs-3 strong_txt'><?= lang('col_gov_daerah'); ?>:</div>
                        <div class='col-xs-9'>
                            <div class='display'><?= $chapter['gov_daerah']; ?></div>
                            <div class='form form-group'><input type='text' class='form-control col-xs-8' name='chapter[gov_daerah]' value='<?= $chapter['gov_daerah']; ?>' /></div>
                        </div>
                    </div>
                    <div class='row row-data col-xs-10 col-xs-offset-1'>
                        <div class='col-xs-3 strong_txt'><?= lang('col_gov_majlis'); ?>:</div>
                        <div class='col-xs-9'>
                            <div class='display'><?= $chapter['gov_majlis']; ?></div>
                            <div class='form form-group'><input type='text' class='form-control col-xs-8' name='chapter[gov_majlis]' value='<?= $chapter['gov_majlis']; ?>' /></div>
                        </div>
                    </div>
                    <div class='row row-data col-xs-10 col-xs-offset-1'>
                        <div class='col-xs-3 strong_txt'><?= lang('col_mailing_address'); ?>:</div>
                        <div class='col-xs-9'>
                            <div class='display'><?= $chapter['mailing_address']; ?></div>
                            <div class='form form-group'><textarea class='form-control col-xs-8' name='chapter[mailing_address]'><?= $chapter['mailing_address']; ?></textarea></div>
                        </div>
                    </div>
                    <div class='row row-data col-xs-10 col-xs-offset-1'>
                        <div class='col-xs-3 strong_txt'><?= lang('col_contact_person'); ?>:</div>
                        <div class='col-xs-9'>
                            <div class='display'><?= $chapter['contact_person']; ?></div>
                            <div class='form form-group'><input type='text' class='form-control col-xs-8' name='chapter[contact_person]' value='<?= $chapter['contact_person']; ?>' /></div>
                        </div>
                    </div>
                    <div class='row row-data col-xs-10 col-xs-offset-1'>
                        <div class='col-xs-3 strong_txt'><?= lang('col_phone'); ?>:</div>
                        <div class='col-xs-9'>
                            <div class='display'><?= $chapter['phone']; ?></div>
                            <div class='form form-group'><input type='text' class='form-control col-xs-8' name='chapter[phone]' value='<?= $chapter['phone']; ?>' /></div>
                        </div>
                    </div>
                    <div class='row row-data col-xs-10 col-xs-offset-1'>
                        <div class='col-xs-3 strong_txt'><?= lang('col_fax'); ?>:</div>
                        <div class='col-xs-9'>
                            <div class='display'><?= $chapter['fax']; ?></div>
                            <div class='form form-group'><input type='text' class='form-control col-xs-8' name='chapter[fax]' value='<?= $chapter['fax']; ?>' /></div>
                        </div>
                    </div>
                    <div class='row row-data col-xs-10 col-xs-offset-1'>
                        <div class='col-xs-3 strong_txt'><?= lang('col_email'); ?>:</div>
                        <div class='col-xs-9'>
                            <div class='display'><?= $chapter['email']; ?></div>
                            <div class='form form-group'><input type='text' class='form-control col-xs-8' name='chapter[email]' value='<?= $chapter['email']; ?>' /></div>
                        </div>
                    </div>
                    <div class='row row-data col-xs-10 col-xs-offset-1'>
                        <div class='col-xs-3 strong_txt'><?= lang('col_contact_email'); ?>:</div>
                        <div class='col-xs-9'>
                            <div class='display'><?= $chapter['contact_email']; ?></div>
                            <div class='form form-group'><input type='text' class='form-control col-xs-8' name='chapter[contact_email]' value='<?= $chapter['contact_email']; ?>' /></div>
                        </div>
                    </div>
                    <div class='row row-data col-xs-10 col-xs-offset-1'>
                        <div class='col-xs-3 strong_txt'><?= lang('col_website'); ?>:</div>
                        <div class='col-xs-9'>
                            <div class='display'><?= $chapter['weburl']; ?></div>
                            <div class='form form-group'><input type='text' class='form-control col-xs-8' name='chapter[weburl]' value='<?= $chapter['weburl']; ?>' /></div>
                        </div>
                    </div>
                    <div class='row row-data col-xs-10 col-xs-offset-1'>
                        <div class='col-xs-3 strong_txt'><?= lang('col_fb_page'); ?>:</div>
                        <div class='col-xs-9'>
                            <div class='display'><?= $chapter['fb_page']; ?></div>
                            <div class='form form-group'><input type='text' class='form-control col-xs-8' name='chapter[fb_page]' value='<?= $chapter['fb_page']; ?>' /></div>
                        </div>
                    </div>
                    <div class='row row-data col-xs-10 col-xs-offset-1'>
                        <div class='col-xs-3 strong_txt'><?= lang('col_dharma_staff'); ?>:</div>
                        <div class='col-xs-9'>
                            <div class='display'><?= $chapter['dharma_staff']; ?></div>
                            <div class='form form-group'><input type='text' class='form-control col-xs-8' name='chapter[dharma_staff]' value='<?= $chapter['dharma_staff']; ?>' /></div>
                        </div>
                    </div>
                    <div class='row row-data col-xs-10 col-xs-offset-1'>
                        <div class='col-xs-3 strong_txt'><?= lang('col_status'); ?>:</div>
                        <div class='col-xs-9'>
                            <div class='display'><?= $this->config->item('tbs')['chapter_status'][$chapter['status']]; ?></div>
                            <div class='form form-group'><?= form_dropdown("chapter[status]",$this->config->item('tbs')['chapter_status'],($chapter['status']) ? $chapter['status'] : "S",array("class"=>"form-control")); ?></div>
                        </div>
                    </div>
                    <div class='row row-data col-xs-10 col-xs-offset-1'>
                        <div class='col-xs-3 strong_txt'>NAS Location:</div>
                        <div class='col-xs-9'>
                            <div class='display'><input type='text' class='form-control col-xs-8' value='<?= $chapter['nas_location']; ?>' /></div>
                            <div class='form form-group'><input type='text' class='form-control col-xs-8' value='<?= $chapter['nas_location']; ?>' /></div>
                        </div>
                    </div>
                    <div class='row row-data col-xs-10 col-xs-offset-1'>
                        <div class='col-xs-3 strong_txt'>產業:</div>
                        <div class='col-xs-9'>
                            <div class='display'><?= $this->config->item('tbs')['properties'][$chapter['properties']]; ?></div>
                            <div class='form form-group'><?= form_dropdown("chapter[properties]",$this->config->item('tbs')['properties'],($chapter['properties']) ? $chapter['properties'] : "U",array("class"=>"form-control")); ?></div>
                        </div>
                    </div>
                    <div class='row row-data col-xs-10 col-xs-offset-1'>
                        <div class='col-xs-3 strong_txt'>產業備註:</div>
                        <div class='col-xs-9'>
                            <div class='display'><textarea class='form-control col-xs-8'readonly rows=3><?= $chapter['properties_remarks']; ?></textarea></div>
                            <div class='form form-group'><textarea class='form-control col-xs-8' rows=3 name='chapter[properties_remarks]'><?= $chapter['properties_remarks']; ?></textarea></div>
                        </div>
                    </div>
                    <div class='row row-data col-xs-10 col-xs-offset-1'>
                        <div class='col-xs-3 strong_txt'><?= lang('col_remarks'); ?>:</div>
                        <div class='col-xs-9'>
                            <div class='display'><textarea class='form-control col-xs-8'readonly rows=5><?= $chapter['remarks']; ?></textarea></div>
                            <div class='form form-group'><textarea class='form-control col-xs-8' rows=5 name='chapter[remarks]'><?= $chapter['remarks']; ?></textarea></div>
                        </div>
                    </div>


                    <div class='row'>&nbsp;</div>
                    <div class='row'>&nbsp;</div>

                    <div class='row col-xs-10 col-xs-offset-1'>
                        <div class='h3 strong_txt'><?= lang('col_title_minisite'); ?></div>
                    </div>

                    <div class='row row-data col-xs-10 col-xs-offset-1'>
                        <div class='col-xs-3 strong_txt'><?= lang('col_url_name'); ?>:</div>
                        <div class='col-xs-9'>
                            <div class='display'><?= $chapter['url_name']; ?></div>
                            <div class='form form-group'><input type='text' class='form-control col-xs-8' name='chapter[url_name]' value='<?= $chapter['url_name']; ?>' /></div>
                        </div>
                    </div>


                    <div class='row row-data col-xs-10 col-xs-offset-1'>
                        <div class='col-xs-3 strong_txt'><?= lang('col_4square_page'); ?>:</div>
                        <div class='col-xs-9'>
                            <div class='display'><?= $chapter['4square_page']; ?></div>
                            <div class='form form-group'><input type='text' class='form-control col-xs-8' name='chapter[4square_page]' value='<?= $chapter['4square_page']; ?>' /></div>
                        </div>
                    </div>
                    <div class='row row-data col-xs-10 col-xs-offset-1'>
                        <div class='col-xs-3 strong_txt'><?= lang('col_coordinate'); ?>:</div>
                        <div class='col-xs-9'>
                            <div class='display'><?= $chapter['coordinate']; ?></div>
                            <div class='form form-group'><input type='text' class='form-control col-xs-8' name='chapter[coordinate]' value='<?= $chapter['coordinate']; ?>' /></div>
                        </div>
                    </div>
                    <div class='row row-data col-xs-10 col-xs-offset-1'>
                        <div class='col-xs-3 strong_txt'><?= lang('col_minisite_bgcolor'); ?>:</div>
                        <div class='col-xs-9'>
                            <div class='display'><?= $chapter['minisite']['bgcolor']; ?><div style='margin-left:10px;display:inline-block;width:20px;height:20px;background-color:<?= $chapter['minisite']['bgcolor']; ?>;'>&nbsp;</div></div>
                            <div class='form form-group'><input id="picker" type='text' class='form-control col-xs-8' name='chapter[minisite][bgcolor]' value='<?= $chapter['minisite']['bgcolor']; ?>' /></div>
                        </div>
                    </div>
                    <div class='row row-data col-xs-10 col-xs-offset-1'>
                        <div class='col-xs-3 strong_txt'><?= lang('col_minisite_bgimg'); ?>:</div>
                        <div class='col-xs-9'>
                            <div class='display'><?php if($chapter['minisite']['bgimg'] == 'show'): ?><img src='/admin/<?= $chapter['bgimgurl'].'?'.date('His'); ?>' height="100px" /><?php else: ?>-N/A-<?php endif;?></div>
                            <div class='form form-group'>
                                <img src='/admin/<?= $chapter['bgimgurl'].'?'.date('His'); ?>' height="100px" />
                                <label class="radio-inline"><input type="radio" name="chapter[minisite][bgimg]" value="show" <?php if($chapter['minisite']['bgimg'] == 'show') echo 'checked="checked"' ?> /><?= lang('radio_show'); ?></label>
                                <label class="radio-inline"><input type="radio" name="chapter[minisite][bgimg]" value="hide" <?php if($chapter['minisite']['bgimg'] == 'hide') echo 'checked="checked"' ?> /><?= lang('radio_hide'); ?></label>
                                <input type='file' class='form-control' name='bgimg' accept="image/jpeg">
                            </div>
                        </div>
                    </div>
                    <div class='row row-data col-xs-10 col-xs-offset-1'>
                        <div class='col-xs-3 strong_txt'><?= lang('col_minisite_banner'); ?>:</div>
                        <div class='col-xs-9'>
                            <div class='display'><?php if($chapter['minisite']['bannerimg'] == 'own'): ?><img src='/admin/<?= $chapter['bannerimgurl'].'?'.date('His'); ?>' height="100px" /><?php else: echo lang('radio_banner_default'); ?><?php endif;?></div>
                            <div class='form form-group'>
                                <img src='/admin/<?= $chapter['bannerimgurl'].'?'.date('His'); ?>' height="100px" />
                                <label class="radio-inline"><input type="radio" name="chapter[minisite][bannerimg]" value="default" <?php if($chapter['minisite']['bannerimg'] == 'default') echo 'checked="checked"'; ?> /><?= lang('radio_banner_default'); ?></label>
                                <label class="radio-inline"><input type="radio" name="chapter[minisite][bannerimg]" value="own" <?php if($chapter['minisite']['bannerimg'] == 'own') echo 'checked="checked"'; ?> /><?= lang('radio_banner_own'); ?></label>
                                <input type='file' class='form-control' name='bannerimg' accept="image/jpeg">
                            </div>
                        </div>
                    </div>

                    <div class='row row-data col-xs-10 col-xs-offset-1'>
                        <div class='col-xs-3 strong_txt'><?= lang('col_additional_info'); ?>:</div>
                    </div>
                    <div class='row' style='margin:0 10px;'>
                        <div class='col-xs-10 col-xs-offset-1' style='border:1px solid #CCC;'>
                            <div class='display'><?= $chapter['additional_info']; ?></div>
                            <div class='form form-group'><textarea id='additional_info' class='form-control col-xs-8 additional_info' name='chapter[additional_info]'><?= $chapter['additional_info']; ?></textarea>
                            </div>
                        </div>
                    </div>

                    <div class='row'>&nbsp;</div>
                    <div class='row'>&nbsp;</div>

                    <div class='row col-xs-10 col-xs-offset-1'>
                        <div class='h3 strong_txt'><?= lang('col_title_mizong_member'); ?></div>
                    </div>

                    <div class='row row-data col-xs-10 col-xs-offset-1'>
                        <div class='col-xs-3 strong_txt'><?= lang('col_membership_id'); ?>:</div>
                        <div class='col-xs-9'>
                            <div class='display'><?= $chapter['membership_id']; ?></div>
                            <div class='form form-group'><input type='text' class='form-control col-xs-8' name='chapter[membership_id]' value='<?= $chapter['membership_id']; ?>' /></div>
                        </div>
                    </div>

                    <div class='row row-data col-xs-10 col-xs-offset-1'>
                        <div class='col-xs-3 strong_txt'><?= lang('col_membership_date'); ?>:</div>
                        <div class='col-xs-9'>
                            <div class='display'><?= $chapter['membership_date']; ?></div>
                            <div class='form form-group'><input type='text' class='form-control col-xs-8' name='chapter[membership_date]' value='<?= $chapter['membership_date']; ?>' /></div>
                        </div>
                    </div>

                    <div class='row row-data col-xs-10 col-xs-offset-1'>
                        <div class='col-xs-3 strong_txt'><?= lang('col_meeting_id'); ?>:</div>
                        <div class='col-xs-9'>
                            <div class='display'><?= $chapter['meeting_id']; ?></div>
                            <div class='form form-group'><input type='text' class='form-control col-xs-8' name='chapter[meeting_id]' value='<?= $chapter['meeting_id']; ?>' /></div>
                        </div>
                    </div>

                    <div class='row row-data col-xs-10 col-xs-offset-1'>
                        <div class='col-xs-3 strong_txt'><?= lang('col_meeting_pinyin'); ?>:</div>
                        <div class='col-xs-9'>
                            <div class='display'><?= $chapter['meeting_pinyin']; ?></div>
                            <div class='form form-group'><input type='text' class='form-control col-xs-8' name='chapter[meeting_pinyin]' value='<?= $chapter['meeting_pinyin']; ?>' /></div>
                        </div>
                    </div>

                    <div class='row row-data col-xs-10 col-xs-offset-1'>
                        <div class='col-xs-3 strong_txt'><?= lang('col_meeting_fpinyin'); ?>:</div>
                        <div class='col-xs-9'>
                            <div class='display'><?= $chapter['meeting_fpinyin']; ?></div>
                            <div class='form form-group'><input type='text' class='form-control col-xs-8' name='chapter[meeting_fpinyin]' value='<?= $chapter['meeting_fpinyin']; ?>' /></div>
                        </div>
                    </div>

                    <div class='row'>&nbsp;</div>
                    <div class='row'>&nbsp;</div>

                    <div class='row col-xs-10 col-xs-offset-1'>
                        <div class='h3 strong_txt'>宗委會相關</div>
                    </div>

                    <div class='row row-data col-xs-10 col-xs-offset-1'>
                        <div class='col-xs-3 strong_txt'>真佛編號:</div>
                        <div class='col-xs-9'>
                            <div class='display'><?= $chapter['tb_id']; ?></div>
                            <div class='form form-group'><input type='text' class='form-control col-xs-8' name='chapter[tb_id]' value='<?= $chapter['tb_id']; ?>' /></div>
                        </div>
                    </div>

                    <div class='row row-data col-xs-10 col-xs-offset-1'>
                        <div class='col-xs-3 strong_txt'>分堂代碼:</div>
                        <div class='col-xs-9'>
                            <div class='display'><?= $chapter['tb_chapter_id']; ?></div>
                            <div class='form form-group'><input type='text' class='form-control col-xs-8' name='chapter[tb_chapter_id]' value='<?= $chapter['tb_chapter_id']; ?>' /></div>
                        </div>
                    </div>

                    <div class='row row-data col-xs-10 col-xs-offset-1'>
                        <div class='col-xs-3 strong_txt'>續證截止:</div>
                        <div class='col-xs-9'>
                            <div class='display'><?= $chapter['tb_cert_renew']; ?></div>
                            <div class='form form-group'><input type='text' class='form-control col-xs-8' name='chapter[tb_cert_renew]' value='<?= $chapter['tb_cert_renew']; ?>' /></div>
                        </div>
                    </div>

                    <div class='row'>&nbsp;</div>
                    <div class='row'>&nbsp;</div>

                    <div class='row col-xs-10 col-xs-offset-1'>
                        <div class='h3 strong_txt'><?= lang('col_title_chapter_member'); ?></div>
                    </div>

                    <div class='row row-data col-xs-10 col-xs-offset-1'>
                        <div class='col-xs-3 strong_txt'><?= lang('col_ajk_session'); ?>:</div>
                        <div class='col-xs-9'>
                            <div class='display'><?= $chapter['ajk_session']; ?></div>
                            <div class='form form-group'><input type='text' class='form-control col-xs-8' name='chapter[ajk_session]' value='<?= $chapter['ajk_session']; ?>' /></div>
                        </div>
                    </div>

                    <div class='row row-data col-xs-10 col-xs-offset-1'>
                        <table class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                            <thead><tr class='info'>
                                <td><?= lang('col_contact_name');?></td>
                                <td><?= lang('col_contact_contact');?></td>
                                <td><?= lang('col_contact_position');?></td>
                                <td><?= lang('col_contact_mizong_agm');?></td>
                                <td><?= lang('col_contact_view');?></td>
                            </tr></thead>
                            <tbody><?php foreach($chapter_member as $c):?>
                                <tr>
                                    <td><?= $c['name_chinese'] . "<br />" . $c['name_dharma']; ?></td>
                                    <td><?= $c['phone_mobile']."<br />".$c['email']; ?></td>
                                    <td><?= $c['position']; ?><?= isset($c['bday_cal'])?$c['bday_cal']:""; ?></td>
                                    <td><?= ($c['mizong_agm']) ? "是" : "否"; ?></td>
                                    <td><a href="<?= base_url("admin/contact/details/".$c['contact_id']); ?>"><?= lang('col_contact_view');?></a></td>
                                </tr>
                            <?php endforeach;?></tbody>
                        </table>
                    </div>

                    <div class='row'>&nbsp;</div>

                    <div class='row'>
                        <div class='col-xs-2 col-xs-offset-1'></div>
                        <div class='col-xs-8'>
                            <div class='form form-group text-right'>
                                <button type="submit" class="btn btn-success">
                                    <i class="fa fa-floppy-o"></i> Save
                                </button>
                                <button type="reset" class="btn btn-danger" onclick='toggle_form();'>
                                    <i class="fa fa-times"></i> Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
    
    <!-- /.row -->
</div>