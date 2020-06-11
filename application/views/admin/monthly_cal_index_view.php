<script>

function _(the_id){
    return document.getElementById(the_id);
}
function uploadFile(){
    $('#loaded_n_total').html('<div class="text-center"><i class="fa fa-2x fa-spinner fa-pulse"></i></div>');
    
    var formdata = new FormData();
    formdata.append("file1", _("file1").files[0]);
    formdata.append("year", _("year").options[ _("year").selectedIndex].value);
    formdata.append("month", _("month").options[ _("month").selectedIndex].value);
    var ajax = new XMLHttpRequest();
    ajax.upload.addEventListener("progress", progressHandler, false);
    ajax.addEventListener("load", completeHandler, false);
    ajax.addEventListener("error", errorHandler, false);
    ajax.addEventListener("abort", abortHandler, false);
    ajax.open("POST", "monthly_cal/upload");
    ajax.send(formdata);
}
function progressHandler(event){
    var percent = (event.loaded / event.total) * 100;
    $('#bar-upload-text2').html(Math.round(percent)+"% uploaded... please wait");
    $('#bar-upload-percent2').css('width',Math.round(percent) + '%');

    
}
function completeHandler(event){
    $('#loaded_n_total').html(this.responseText);
    $('#tbsn_live').html("<iframe src='<?=base_url('api/monthly_calendar');?>' style='border:0px;width:100%;height:300px;'></iframe>");
    $('#bar-upload-text2').html("Upload Completed!");
    $('#bar-upload-percent2').removeClass('progress-bar-info');
    $('#bar-upload-percent2').addClass('progress-bar-success');
}
function errorHandler(event){
    $('#bar-upload-text2').html("Upload Failed!");
    $('#bar-upload-percent2').removeClass('progress-bar-info');
    $('#bar-upload-percent2').addClass('progress-bar-danger');
}
function abortHandler(event){
    $('#bar-upload-text2').html("Upload Aborted!");
    $('#bar-upload-percent2').removeClass('progress-bar-info');
    $('#bar-upload-percent2').addClass('progress-bar-danger');
}
</script>


<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header"><?= lang('menu_title'); ?></h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-6">

            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-6">
                            <div><?= lang('menu_main'); ?></div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <form class='form-inline' id="upload_form" enctype="multipart/form-data" method="post">
                        <div class='form-group'>
                            <?= form_dropdown('year', next5years(), date('Y'), 'class="form-control" id="year"'); ?>
                            <?= form_dropdown('month', getMonth(), date('m'), 'class="form-control" id="month"'); ?>
                            <input class='form-control' type="file" name="file1" id="file1"> 
                            <input class='btn btn-primary' type="button" value="Upload File" onclick="uploadFile()">
                        </div>
                    </form>
                    <br />
                    <div class="progress">
                        <div class="progress-bar progress-bar-info" style="width: 0%" id='bar-upload-percent2'>
                            <span id='bar-upload-text2'></span>
                        </div>
                    </div>

                    <h3 id="status"></h3>
                </div>
            </div>
        </div>

        <div class="col-lg-6">

            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-6">
                            <div><?= lang('menu_result'); ?></div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer" style='min-height:150px;'>
                    <p id="loaded_n_total"></p>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-12">

            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-12">
                            <div><?= lang('menu_live_view'); ?></div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer" id="tbsn_live">
                    <iframe src='<?= base_url('api/monthly_calendar');?>' style='border:0px;width:100%;height:300px;'></iframe>
                </div>
            </div>
        </div>

    </div>
    
    <!-- /.row -->
</div>