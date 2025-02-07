<script>
$( document ).ready(function() {
    $( "#country" ).combobox();
});
</script>
<style>
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
                            <div class='text-center h3'><?= lang('title_new');?></div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">

                    <form class='form-horizontal' id="upload_form" enctype="multipart/form-data" method="post" action="<?= base_url('admin/contact/add');?>">

                        <div class='row'>&nbsp;</div>

                        <div class='row col-xs-10 col-xs-offset-1'>
                            <div class='h3 strong_txt'><?= lang('col_title_info');?></div>
                        </div>

                        <?php $loop = array("name_chinese","name_malay","name_dharma","nric","phone_mobile","phone_home","phone_office","email"); ?>
                        <?php foreach($loop as $col_name): ?>
                        <div class='row row-data col-xs-10 col-xs-offset-1'>
                            <div class='col-xs-3 strong_txt'><?= lang('col_'.$col_name);?>:</div>
                            <div class='col-xs-9'>
                                <div class='form form-group'>
                                    <?= form_input([
                                        'name'  => "contact[${col_name}]",
                                        'class' => 'form-control col-xs-8' . ($col_name == 'nric' ? ' nric-input' : '')
                                    ]);?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach;?>

                        <div class='row row-data col-xs-10 col-xs-offset-1'>
                            <div class='col-xs-3 strong_txt'><?= lang('col_dob');?>:</div>
                            <div class='col-xs-9'>
                                <div class='form form-group'><input type='date' class='form-control col-xs-8' name='contact[dob]' value='' /></div>
                            </div>
                        </div>

                        <div class='row row-data col-xs-10 col-xs-offset-1'>
                            <div class='col-xs-3 strong_txt'><?= lang('col_gender');?>:</div>
                            <div class='col-xs-9'>
                                <div class='form form-group'>
                                    <label class="radio-inline"><input type="radio" value="M" name="contact[gender]">男</label>
                                    <label class="radio-inline"><input type="radio" value="F" name="contact[gender]">女</label>
                                </div>
                            </div>
                        </div>

                        <div class='row row-data col-xs-10 col-xs-offset-1'>
                            <div class='col-xs-3 strong_txt'><?= lang('col_country');?>:</div>
                            <div class='col-xs-9'>
                                <div class='form form-group'>
                                    <?= form_dropdown("contact[country]",$this->config->item('countries')['name'],($contact['country']) ? $contact['country'] : "MY",array("class"=>"form-control", "id" => "country")); ?>
                                </div>
                            </div>
                        </div>

                       <div class='row row-data col-xs-10 col-xs-offset-1'>
                            <div class='col-xs-3 strong_txt'><?= lang('col_address');?>:</div>
                            <div class='col-xs-9'>
                                <div class='form form-group'><textarea class='form-control col-xs-8' name='contact[address]'></textarea></div>
                            </div>
                        </div>

                        <div class='row row-data col-xs-10 col-xs-offset-1'>
                            <div class='col-xs-3 strong_txt'><?= lang('col_address_office');?>:</div>
                            <div class='col-xs-9'>
                                <div class='form form-group'><textarea class='form-control col-xs-8' name='contact[address_office]'></textarea></div>
                            </div>
                        </div>

                        <div class='row row-data col-xs-10 col-xs-offset-1'>
                            <div class='col-xs-3 strong_txt'>簡介:</div>
                            <div class='col-xs-9'>
                                <div class='form form-group'><textarea class='form-control col-xs-8' name='contact[description]'></textarea></div>
                            </div>
                        </div>


                        <div class='row'>
                            <div class='col-xs-2 col-xs-offset-1'></div>
                            <div class='col-xs-8'>
                                <div class='form form-group text-right'>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fa fa-floppy-o"></i> Add
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