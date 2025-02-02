<script>
function toggle_form(){
    $('.form').toggle();
    $('.display').toggle();
}
function toggle_form_dharma(){
    $('.form_dharma').toggle();
    $('.display_dharma').toggle();
}
function toggle_form_member(){
    $('.form_member').toggle();
    $('.display_member').toggle();
}
function confirm_delete(me){
    if(me.prop('checked')){
        var res = confirm("Confirmed delete?");
        me.prop("checked", res);
    }
}
$( document ).ready(function() {
    $( "#country" ).combobox();
    $( ".chapter_list" ).combobox();


});
</script>
<style>
    .form{display:none;}
    .form_dharma{display:none;}
    .form_member{display:none;}
    .strong_txt{
        font-weight:bold;
    }
    .row-data{
        border-top:1px solid #CCC;
        padding:5px 0;
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

            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class='text-center h3'><?= $contact['name_chinese'] . " (".$contact['name_dharma'].")";?></div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">

                    <form class='form-horizontal' id="upload_form" enctype="multipart/form-data" method="post" action="<?= base_url('admin/contact/update');?>">
                        <div class='row'>
                            <div class='col-xs-12 text-right'>
                                <a class='btn btn-info' href='javascript: void(0);' onclick='toggle_form();'><i class="fa fa-pencil-square-o"></i> <?= lang('title_update'); ?></a>
                            </div>
                        </div>

                        <div class='row'>&nbsp;</div>

                        <div class='row col-xs-10 col-xs-offset-1'>
                            <div class='h3 strong_txt'><?= lang('col_title_info');?></div>
                        </div>
                        
                        <?php $loop = array("name_chinese","name_malay","name_dharma","nric","phone_mobile","phone_home","phone_office","email"); ?>
                        <?php foreach($loop as $col_name):
                                $input_box = form_input(array(
                                    'name' => "contact[${col_name}]",
                                    'value' => $contact[$col_name],
                                    'class' => 'form-control col-xs-8' . ($col_name == 'nric' ? ' nric-input' : ''),
                                ));
                        ?>
                        <div class='row row-data col-xs-10 col-xs-offset-1'>
                            <div class='col-xs-3 strong_txt'><?= lang('col_'.$col_name);?>:</div>
                            <div class='col-xs-9'>
                                <div class='display'><?= $contact[$col_name]; ?></div>
                                <div class='form form-group'><?= $input_box; ?></div>
                            </div>
                        </div>
                        <?php endforeach;?>

                        <div class='row row-data col-xs-10 col-xs-offset-1'>
                            <div class='col-xs-3 strong_txt'><?= lang('col_dob');?>:</div>
                            <div class='col-xs-9'>
                                <div class='display'><?= $contact['dob']; ?></div>
                                <div class='form form-group'><input type='date' class='form-control col-xs-8' name='contact[dob]' value='<?= $contact['dob']; ?>' /></div>
                            </div>
                        </div>

                        <div class='row row-data col-xs-10 col-xs-offset-1'>
                            <div class='col-xs-3 strong_txt'><?= lang('col_gender');?>:</div>
                            <div class='col-xs-9'>
                                <div class='display'><?= ($contact['gender'] == "M") ? "男" : "女"; ?></div>
                                <div class='form form-group'>
                                    <label class="radio-inline"><input type="radio" value="M" name="contact[gender]" <?php if($contact['gender'] == "M"): ?> checked <?php endif; ?>>男</label>
                                    <label class="radio-inline"><input type="radio" value="F" name="contact[gender]" <?php if($contact['gender'] == "F"): ?> checked <?php endif; ?>>女</label>
                                </div>
                            </div>
                        </div>

                        <div class='row row-data col-xs-10 col-xs-offset-1'>
                            <div class='col-xs-3 strong_txt'><?= lang('col_country');?>:</div>
                            <div class='col-xs-9'>
                                <div class='display'><?= $this->config->item('countries')['name'][$contact['country']]; ?></div>
                                <div class='form form-group'>
                                    <?= form_dropdown("contact[country]",$this->config->item('countries')['name'],($contact['country']) ? $contact['country'] : "MY",array("class"=>"form-control", "id" => "country")); ?>
                                </div>
                            </div>
                        </div>

                       <div class='row row-data col-xs-10 col-xs-offset-1'>
                            <div class='col-xs-3 strong_txt'><?= lang('col_address');?>:</div>
                            <div class='col-xs-9'>
                                <div class='display'><?= $contact['address']; ?></div>
                                <div class='form form-group'><textarea class='form-control col-xs-8' name='contact[address]'><?= $contact['address']; ?></textarea></div>
                            </div>
                        </div>

                        <div class='row row-data col-xs-10 col-xs-offset-1'>
                            <div class='col-xs-3 strong_txt'><?= lang('col_address_office');?>:</div>
                            <div class='col-xs-9'>
                                <div class='display'><?= $contact['address_office']; ?></div>
                                <div class='form form-group'><textarea class='form-control col-xs-8' name='contact[address_office]'><?= $contact['address_office']; ?></textarea></div>
                            </div>
                        </div>

                        <div class='row row-data col-xs-10 col-xs-offset-1'>
                            <div class='col-xs-3 strong_txt'>簡介:</div>
                            <div class='col-xs-9'>
                                <div class='display'><?= $contact['description']; ?></div>
                                <div class='form form-group'><textarea class='form-control col-xs-8' name='contact[description]'><?= $contact['description']; ?></textarea></div>
                            </div>
                        </div>


                        <div class='row'>&nbsp;</div>
                        <div class='row'>&nbsp;</div>

                        <div class='row col-xs-10 col-xs-offset-1'>
                            <div class='h3 strong_txt'><?= lang('col_title_chapter');?></div>
                        </div>

                        <div class='row row-data col-xs-10 col-xs-offset-1'>
                            <table class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                                <thead><tr class='info'>
                                    <td><?= lang('col_chapter_name_chinese');?></td>
                                    <td><?= lang('col_chapter_ajk_session');?></td>
                                    <td><?= lang('col_chapter_position');?></td>
                                    <td><?= lang('col_chapter_dharma');?></td>
                                    <td><?= lang('col_chapter_mizong_agm');?></td>
                                    <td><?= lang('col_chapter_view');?></td>
                                </tr></thead>
                                <tbody><?php foreach($chapter_member as $c):?>
                                    <tr>
                                        <td><?= $c['name_chinese']; ?></td>
                                        <td><?= $c['ajk_session']; ?></td>
                                        <td><?= $c['position']; ?></td>
                                        <td><?= ($c['dharma']) ? "是" : "否"; ?></td>
                                        <td><?= ($c['mizong_agm']) ? "是" : "否"; ?></td>
                                        <td><a href="<?= base_url("admin/index/update_default_chapter/".$c['url_name']."/chapter_page"); ?>"><?= lang('col_chapter_view');?></a></td>
                                    </tr>
                                <?php endforeach;?></tbody>
                            </table>

                            <table class="form table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                                <thead><tr class='info'>
                                    <td><?= lang('col_chapter_name_chinese');?></td>
                                    <td><?= lang('col_chapter_position');?></td>
                                    <td><?= lang('col_chapter_dharma');?></td>
                                    <td><?= lang('col_chapter_mizong_agm');?></td>
                                    <td><?= lang('col_chapter_delete');?></td>
                                </tr></thead>
                                <tbody><?php foreach($chapter_member as $c): $cmid = $c['cm_id'];?>
                                    <tr>
                                        <td><?= form_dropdown("chapter_member[$cmid][chapter_id]",$chapter_list,$c['chapter_id'],array("class"=>"chapter_list form-control")); ?></td>
                                        <td><input class='form-control' name='chapter_member[<?= $cmid; ?>][position]' value='<?= $c['position']; ?>' /></td>
                                        <td><?= form_dropdown("chapter_member[$cmid][dharma]",array("否","是"),$c['dharma'],array("class"=>"form-control")); ?></td>
                                        <td><?= form_dropdown("chapter_member[$cmid][mizong_agm]",array("否","是"),$c['mizong_agm'],array("class"=>"form-control")); ?></td>
                                        <td><input type="checkbox" name="chapter_member[<?= $cmid; ?>][delete]" onclick="confirm_delete($(this));"></td>
                                        <input type="hidden" name="chapter_member[<?= $cmid; ?>][contact_id]" value="<?= $c['contact_id'];?>">
                                    </tr>
                                <?php endforeach;?></tbody>
                                <tfoot><!-- New Adding -->
                                    <tr>
                                        <td><?= form_dropdown("chapter_member[0][chapter_id]",$chapter_list,"",array("class"=>"chapter_list form-control")); ?></td>
                                        <td><input class='form-control' name='chapter_member[0][position]' value='會員' /></td>
                                        <td><?= form_dropdown("chapter_member[0][dharma]",array("否","是"),"",array("class"=>"form-control")); ?></td>
                                        <td><?= form_dropdown("chapter_member[0][mizong_agm]",array("否","是"),"",array("class"=>"form-control")); ?></td>
                                        <td>新增</td>
                                        <input type="hidden" name="chapter_member[0][contact_id]" value="<?= $contact['contact_id'];?>">
                                    </tr>
                                </tfoot>
                            </table>
                        </div>


                        <div class='row'>&nbsp;</div>
                        <div class='row'>&nbsp;</div>

                        <div class='row col-xs-10 col-xs-offset-1'>
                            <div class='h3 strong_txt'><?= lang('col_title_dharma');?></div>
                        </div>

                        <?php if(isset($contact['dharma_position'])): ?>
                        <div class='row row-data col-xs-10 col-xs-offset-1'>
                            <div class='col-xs-3 strong_txt'><?= lang('col_dharma_position');?>:</div>
                            <div class='col-xs-9'>
                                <div class='display'><?= $this->config->item('tbs')['dharma_staff'][$contact['dharma_position']]; ?></div>
                                <div class='form form-group'>
                                    <?= form_dropdown("dharma[dharma_position]",array_merge(array(""=>"-N/A-"),$this->config->item('tbs')['dharma_staff']),isset($contact['dharma_position']) ? $contact['dharma_position']:"",array("class"=>"form-control")); ?>
                                </div>
                            </div>
                        </div>

                        <div class='row row-data col-xs-10 col-xs-offset-1'>
                            <div class='col-xs-3 strong_txt'><?= lang('col_chapter_from');?>:</div>
                            <div class='col-xs-9'>
                                <div class='display'><?= (isset($contact['chapter_id']) && $contact['chapter_id'] != 0) ? $chapter_list[$contact['chapter_id']] : "-N/A-"; ?></div>
                                <div class='form form-group'>
                                    <?= form_dropdown("dharma[chapter_id]",$chapter_list,$contact['chapter_id'],array("class"=>"chapter_list form-control")); ?>
                                </div>
                            </div>
                        </div>

                        <div class='row row-data col-xs-10 col-xs-offset-1'>
                            <div class='col-xs-3 strong_txt'><?= lang('col_event');?>:</div>
                            <div class='col-xs-9'>
                                <div class='display'><?= $contact['event']; ?></div>
                                <div class='form form-group'>
                                    <input type='text' class='form-control col-xs-8' name='dharma[event]' value='<?= $contact['event']; ?>' />
                                </div>
                            </div>
                        </div>

                        <div class='row row-data col-xs-10 col-xs-offset-1'>
                            <div class='col-xs-3 strong_txt'><?= lang('col_status');?>:</div>
                            <div class='col-xs-9'>
                                <div class='display'><?= $this->config->item('tbs')['staff_status'][$contact['status']]; ?></div>
                                <div class='form form-group'>
                                    <?= form_dropdown("dharma[status]",$this->config->item('tbs')['staff_status'],$contact['status'],array("class"=>"form-control")); ?>
                                </div>
                            </div>
                        </div>
                        <?php else: ?>
                        <div class='display_dharma row row-data col-xs-10 col-xs-offset-1'>
                            <div class='text-center h3'>
                                <?= lang('msg_non_dharma'); ?>
                                <br />
                                <br />
                                <a class='btn btn-info' href='javascript: void(0);' onclick='toggle_form_dharma();'>
                                    <i class="fa fa-pencil-square-o"></i> <?= lang('msg_add_dharma'); ?>
                                </a>
                            </div>
                        </div>

                        <div class='form_dharma row row-data col-xs-10 col-xs-offset-1'>
                            <div class='col-xs-3 strong_txt'><?= lang('col_dharma_position');?>:</div>
                            <div class='col-xs-9'>
                                <div class='form-group'>
                                    <?= form_dropdown("dharma[dharma_position]",array_merge(array(""=>"-N/A-"),$this->config->item('tbs')['dharma_staff']),"",array("class"=>"form-control", "id" => "dharma_position")); ?>
                                </div>
                            </div>
                        </div>

                        <div class='form_dharma row row-data col-xs-10 col-xs-offset-1'>
                            <div class='col-xs-3 strong_txt'><?= lang('col_chapter_from');?>:</div>
                            <div class='col-xs-9'>
                                <div class='form-group'>
                                    <?= form_dropdown("dharma[chapter_id]",$chapter_list,"",array("class"=>"chapter_list form-control")); ?>
                                </div>
                            </div>
                        </div>

                        <div class='form_dharma row row-data col-xs-10 col-xs-offset-1'>
                            <div class='col-xs-3 strong_txt'><?= lang('col_event');?>:</div>
                            <div class='col-xs-9'>
                                <div class='form-group'><input type='text' class='form-control col-xs-8' name='dharma[event]' /></div>
                            </div>
                        </div>

                        <div class='form_dharma row row-data col-xs-10 col-xs-offset-1'>
                            <div class='col-xs-3 strong_txt'><?= lang('col_status');?>:</div>
                            <div class='col-xs-9'>
                                <div class='form-group'>
                                    <?= form_dropdown("dharma[status]",$this->config->item('tbs')['staff_status'],"Normal",array("class"=>"form-control")); ?>
                                </div>
                            </div>
                        </div>

                        <div class='form_dharma row'>
                            <div class='col-xs-2 col-xs-offset-1'></div>
                            <div class='col-xs-8'>
                                <div class='form-group text-right'>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fa fa-floppy-o"></i> Save
                                    </button>
                                    <button type="reset" class="btn btn-danger" onclick='toggle_form_dharma();'>
                                        <i class="fa fa-times"></i> Cancel
                                    </button>
                                </div>
                            </div>
                        </div>

                        <?php endif; ?>

                        <div class='row'>&nbsp;</div>
                        <div class='row'>&nbsp;</div>

                        <div class='row col-xs-10 col-xs-offset-1'>
                            <div class='h3 strong_txt'><?= lang('col_title_member');?></div>
                        </div>

                        <?php if(isset($contact_member['member_id'])): ?>

                        <div class='row row-data col-xs-10 col-xs-offset-1'>
                            <div class='col-xs-3 strong_txt'><?= lang('col_membership_id');?>:</div>
                            <div class='col-xs-9'>
                                <div class='display'><?= $contact_member['membership_id']; ?></div>
                                <div class='form form-group'>
                                    <input type='text' class='form-control col-xs-8' name='contact_member[membership_id]' value='<?= $contact_member['membership_id']; ?>' />
                                </div>
                            </div>
                        </div>

                        <div class='row row-data col-xs-10 col-xs-offset-1'>
                            <div class='col-xs-3 strong_txt'><?= lang('col_status');?>:</div>
                            <div class='col-xs-9'>
                                <div class='display'><?= $this->config->item('tbs')['member_status'][$contact_member['status']]; ?></div>
                                <div class='form form-group'>
                                    <?= form_dropdown("contact_member[status]",$this->config->item('tbs')['member_status'],isset($contact_member['status']) ? $contact_member['status']:"I",array("class"=>"form-control")); ?>
                                </div>
                            </div>
                        </div>

                        <div class='row row-data col-xs-10 col-xs-offset-1'>
                            <div class='col-xs-3 strong_txt'><?= lang('col_status_remarks');?>:</div>
                            <div class='col-xs-9'>
                                <div class='display'><?= $contact_member['status_remarks']; ?></div>
                                <div class='form form-group'>
                                    <input type='text' class='form-control col-xs-8' name='contact_member[status_remarks]' value='<?= $contact_member['status_remarks']; ?>' />
                                </div>
                            </div>
                        </div>

                        <div class='row row-data col-xs-10 col-xs-offset-1'>
                            <div class='col-xs-3 strong_txt'><?= lang('col_meeting_id');?>:</div>
                            <div class='col-xs-9'>
                                <div class='display'><?= $contact_member['meeting_id']; ?></div>
                                <div class='form form-group'>
                                    <input type='text' class='form-control col-xs-8' name='contact_member[meeting_id]' value='<?= $contact_member['meeting_id']; ?>' />
                                </div>
                            </div>
                        </div>

                        <div class='row row-data col-xs-10 col-xs-offset-1'>
                            <div class='col-xs-3 strong_txt'><?= lang('col_meeting_pinyin');?>:</div>
                            <div class='col-xs-9'>
                                <div class='display'><?= $contact_member['meeting_pinyin']; ?></div>
                                <div class='form form-group'>
                                    <input type='text' class='form-control col-xs-8' name='contact_member[meeting_pinyin]' value='<?= $contact_member['meeting_pinyin']; ?>' />
                                </div>
                            </div>
                        </div>

                        <div class='row row-data col-xs-10 col-xs-offset-1'>
                            <div class='col-xs-3 strong_txt'><?= lang('col_meeting_fpinyin');?>:</div>
                            <div class='col-xs-9'>
                                <div class='display'><?= $contact_member['meeting_fpinyin']; ?></div>
                                <div class='form form-group'>
                                    <input type='text' class='form-control col-xs-8' name='contact_member[meeting_fpinyin]' value='<?= $contact_member['meeting_fpinyin']; ?>' />
                                </div>
                            </div>
                        </div>

                        <?php else: ?>
                        <div class='display_member row row-data col-xs-10 col-xs-offset-1'>
                            <div class='text-center h3'>
                                <?= lang('msg_non_member'); ?>
                                <br />
                                <br />
                                <a class='btn btn-info' href='javascript: void(0);' onclick='toggle_form_member();'>
                                    <i class="fa fa-pencil-square-o"></i> <?= lang('msg_add_member'); ?>
                                </a>
                            </div>
                        </div>

                        <div class='form_member row row-data col-xs-10 col-xs-offset-1'>
                            <div class='col-xs-3 strong_txt'><?= lang('col_membership_id');?>:</div>
                            <div class='col-xs-9'>
                                <div class='form-group'><input type='text' class='form-control col-xs-8' name='contact_member[membership_id]' /></div>
                            </div>
                        </div>

                        <div class='form_member row row-data col-xs-10 col-xs-offset-1'>
                            <div class='col-xs-3 strong_txt'><?= lang('col_status');?>:</div>
                            <div class='col-xs-9'>
                                <div class='form-group'>
                                    <?= form_dropdown("contact_member[status]",$this->config->item('tbs')['member_status'],"A",array("class"=>"form-control")); ?>
                                </div>
                            </div>
                        </div>

                        <div class='form_member row row-data col-xs-10 col-xs-offset-1'>
                            <div class='col-xs-3 strong_txt'><?= lang('col_status_remarks');?>:</div>
                            <div class='col-xs-9'>
                                <div class='form-group'><input type='text' class='form-control col-xs-8' name='contact_member[status_remarks]' /></div>
                            </div>
                        </div>

                        <div class='form_member row row-data col-xs-10 col-xs-offset-1'>
                            <div class='col-xs-3 strong_txt'><?= lang('col_meeting_id');?>:</div>
                            <div class='col-xs-9'>
                                <div class='form-group'><input type='text' class='form-control col-xs-8' name='contact_member[meeting_id]' /></div>
                            </div>
                        </div>

                        <div class='form_member row row-data col-xs-10 col-xs-offset-1'>
                            <div class='col-xs-3 strong_txt'><?= lang('col_meeting_pinyin');?>:</div>
                            <div class='col-xs-9'>
                                <div class='form-group'><input type='text' class='form-control col-xs-8' name='contact_member[meeting_pinyin]' /></div>
                            </div>
                        </div>

                        <div class='form_member row row-data col-xs-10 col-xs-offset-1'>
                            <div class='col-xs-3 strong_txt'><?= lang('col_meeting_fpinyin');?>:</div>
                            <div class='col-xs-9'>
                                <div class='form-group'><input type='text' class='form-control col-xs-8' name='contact_member[meeting_fpinyin]' /></div>
                            </div>
                        </div>

                        <div class='form_member row'>
                            <div class='col-xs-2 col-xs-offset-1'></div>
                            <div class='col-xs-8'>
                                <div class='form-group text-right'>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fa fa-floppy-o"></i> Save
                                    </button>
                                    <button type="reset" class="btn btn-danger" onclick='toggle_form_member();'>
                                        <i class="fa fa-times"></i> Cancel
                                    </button>
                                </div>
                            </div>
                        </div>

                        <?php endif; ?>

                        

                        <input type='hidden'name='contact[contact_id]' value='<?= $contact['contact_id']; ?>' />

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


                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- /.row -->
</div>