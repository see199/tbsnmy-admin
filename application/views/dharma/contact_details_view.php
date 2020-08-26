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
        <div class="col-lg-10 col-lg-offset-1">
            <h2 class="page-header">個人資料</h1>
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
                            <div class='text-center h3'><?= $contact['name_dharma'];?></div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">

                    <form class='form-horizontal' id="upload_form" enctype="multipart/form-data" method="post" action="<?= base_url('dharma/index/update');?>">
                        <div class='row'>
                            <div class='col-xs-12 text-right'>
                                <a class='btn btn-info' href='javascript: void(0);' onclick='toggle_form();'><i class="fa fa-pencil-square-o"></i> <?= lang('title_update'); ?></a>
                            </div>
                        </div>

                        <div class='row'>&nbsp;</div>

                        <div class='row col-xs-10 col-xs-offset-1'>
                            <div class='h3 strong_txt'><?= lang('col_title_info');?></div>
                        </div>
                        
                        <?php $loop = array("name_dharma","name_chinese","name_malay","nric","phone_mobile","phone_home","phone_office","email"); ?>
                        <?php foreach($loop as $col_name): ?>
                        <div class='row row-data col-xs-10 col-xs-offset-1'>
                            <div class='col-xs-3 strong_txt'><?= lang('col_'.$col_name);?>:</div>
                            <div class='col-xs-9'>
                                <div class='display'><?= $contact[$col_name]; ?></div>
                                <div class='form form-group'><input type='text' class='form-control col-xs-8' name='contact[<?=$col_name;?>]' value='<?= $contact[$col_name]; ?>' /></div>
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
                                <div class='display'><pre><?= $contact['address']; ?></pre></div>
                                <div class='form form-group'><textarea class='form-control col-xs-8' name='contact[address]'><?= $contact['address']; ?></textarea></div>
                            </div>
                        </div>

                        <div class='row row-data col-xs-10 col-xs-offset-1'>
                            <div class='col-xs-3 strong_txt'><?= lang('col_address_office');?>:</div>
                            <div class='col-xs-9'>
                                <div class='display'><pre><?= $contact['address_office']; ?></pre></div>
                                <div class='form form-group'><textarea class='form-control col-xs-8' name='contact[address_office]'><?= $contact['address_office']; ?></textarea></div>
                            </div>
                        </div>

                        <div class='row row-data col-xs-10 col-xs-offset-1'>
                            <div class='col-xs-3 strong_txt'>簡介:</div>
                            <div class='col-xs-9'>
                                <div class='display'><pre><?= $contact['description']; ?></pre></div>
                                <div class='form form-group'><textarea class='form-control col-xs-8' name='contact[description]'><?= $contact['description']; ?></textarea></div>
                            </div>
                        </div>


                        <div class='row'>&nbsp;</div>
                        <div class='row'>&nbsp;</div>


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