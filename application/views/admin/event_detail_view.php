<script>
$(function() {
    default_start_date = '<?= check_return_empty($event,'start_date'); ?>';
    $("#start_date").datepicker();
    $("#start_date").datepicker("option","dateFormat","yy-mm-dd");
    $("#start_date").datepicker("option","defaultDate",default_start_date);
    $("#start_date").val(default_start_date);

    default_end_date = '<?= check_return_empty($event,'end_date'); ?>';
    $("#end_date").datepicker();
    $("#end_date").datepicker("option","dateFormat","yy-mm-dd");
    $("#end_date").datepicker("option","defaultDate",default_end_date);
    $("#end_date").val(default_end_date);

  });
function toggle_form(){
    $('.form').toggle();
    $('.display').toggle();
}
function set_end_date(){
$("#end_date").val($("#start_date").val());
}
</script>
<style>
    .form{display:none;}
    #additional_info{
        height:300px;
    }
    .top-line{
        border-top:1px solid #CCC;
        padding:10px;
    }
</style>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><?= lang('title_detail'); ?></h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-8 col-lg-offset-2">

            <div class="panel panel-primary">
                
                <div class="panel-footer">

                    <form class='form-horizontal' id="upload_form" enctype="multipart/form-data" method="post" action="<?= base_url('admin/event/'.$action); ?>">
                        <?php if($action == 'update'): ?>
                        <input type='hidden' name='event[event_id]' value='<?= $event["event_id"]; ?>' />
                        <div class='row'>
                            <div class='col-xs-12 text-right'>
                                <a class='btn btn-primary' href='javascript: void(0);' onclick='toggle_form();'><?= lang('link_edit_chapter'); ?></a>
                            </div>
                        </div>
                        <?php endif; ?>

                        <div class='row'>&nbsp;</div>
                        
                        <div class='row'>
                            <div class='top-line col-xs-2 col-xs-offset-1'><?= lang('col_start_date'); ?></div>
                            <div class='top-line col-xs-8'>
                                <div class='display'><?= check_return_empty($event,'start_date'); ?></div>
                                <div class='form form-group'><input type='text' id='start_date' class='form-control col-xs-8' name='event[start_date]' onchange='set_end_date();' /></div>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='top-line col-xs-2 col-xs-offset-1'><?= lang('col_end_date'); ?></div>
                            <div class='top-line col-xs-8'>
                                <div class='display'><?= check_return_empty($event,'end_date'); ?></div>
                                <div class='form form-group'><input type='text' id='end_date' class='form-control col-xs-8' name='event[end_date]' /></div>
                            </div>
                        </div>

                        <div class='row'>
                            <div class='top-line col-xs-2 col-xs-offset-1'><?= lang('col_master_name'); ?></div>
                            <div class='top-line col-xs-8'>
                                <div class='display'><?= check_return_empty($event,'master_name'); ?></div>
                                <div class='form form-group'><?= $form_master; ?></div>
                            </div>
                        </div>


                        <div class='row'>
                            <div class='top-line col-xs-2 col-xs-offset-1'><?= lang('col_name'); ?></div>
                            <div class='top-line col-xs-8'>
                                <div class='display'><?= check_return_empty($event,'name'); ?></div>
                                <div class='form form-group'><input type='text' class='form-control col-xs-8' name='event[name]' value='<?= check_return_empty($event,'name'); ?>' /></div>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='top-line col-xs-2 col-xs-offset-1'><?= lang('col_location'); ?></div>
                            <div class='top-line col-xs-8'>
                                <div class='display'><?= check_return_empty($event,'location'); ?></div>
                                <div class='form form-group'><input type='text' class='form-control col-xs-8' name='event[location]' value='<?= check_return_empty($event,'location'); ?>' /></div>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='top-line col-xs-2 col-xs-offset-1'><?= lang('col_image'); ?></div>
                            <div class='top-line col-xs-8'>
                                <div class='display'><?= check_return_empty($event,'image'); ?></div>
                                <div class='form form-group'>
                                    <?= check_return_empty($event,'image'); ?><br />
                                    <input type='hidden' name='event[image_location]' value='<?= check_return_empty($event,'image_location'); ?>' />
                                    <input type='file' class='form-control col-xs-8' name='image' />
                                </div>
                            </div>
                        </div>

                        

                        <div class='row'>&nbsp;</div>

                        <div class='row'>
                            <div class='col-xs-2 col-xs-offset-1'></div>
                            <div class='col-xs-8'>
                                <div class='form form-group text-right'><input class='btn btn-primary' type="submit" value="<?= lang('btn_'.$action); ?>" ></div>
                            </div>
                        </div>


                    </form>
                </div>
            </div>

            <?php if($chapter_allowed[0] == 'all' && $action == 'update'): ?>
            <!-- Joomla Page Show -->
            <div class="panel panel-primary form">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-12">

                            <div class='text-center h3'>Joomla Setting</div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer form-horizontal">

                        
                        <div class='row'>
                            <div class='col-xs-2 col-xs-offset-1'>Title</div>
                            <div class='col-xs-8'>
                                <div class='form form-group'><input type='text' class='form-control col-xs-8' value='[<?php echo date('d/m/y',strtotime($event['start_date'])).' - '.$event['chapter_name']; ?>] <?= $event['name']; ?>' onclick='this.select();'  /></div>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-xs-2 col-xs-offset-1'>Thumb</div>
                            <div class='col-xs-8'>
                                <div class='form form-group'><input type='text' class='form-control col-xs-8' value='img/350x350/<?= substr($event['image_location'],15); ?>' onclick='this.select();'  /></div>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-xs-2 col-xs-offset-1'>Content Text</div>
                            <div class='col-xs-8'>
                                <div class='form form-group'>
                                    <textarea class='form-control col-xs-8' onclick='this.select();'><hr id="system-readmore" /><p><img src="<?= $event['image_location']; ?>" alt="<?= $event['chapter_name'].' - '.$event['name'] ; ?>" /></p></textarea>
                                </div>
                            </div>
                        </div>
                        <div class='row'>
                            <div class='col-xs-2 col-xs-offset-1'>Publish (30 days before)</div>
                            <div class='col-xs-8'>
                                <div class='form form-group'><input type='text' class='form-control col-xs-8' value='<?= date('Y-m-d H:i:s',strtotime($event['start_date'])-(30*24*3600)) ?>' onclick='this.select();'  /></div>
                            </div>
                        </div>

                </div>
            </div>
            <?php endif; ?>

        </div>
    </div>
    
    <!-- /.row -->
</div>

<?php if($action == 'insert'): ?>
<script>
$( document ).ready(function() {
    toggle_form();
});
</script>
<?php endif; ?>