<div id="page-wrapper">

    <div class="row">
        <div class="box">
            <div class="col-lg-8 col-lg-offset-2 text-center">
                <h1><?= lang('glb_nav_import'); ?></h1>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 col-lg-offset-2">

            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class='text-center h3'><?= lang('glb_nav_import');?></div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">

                    <?php echo form_open_multipart(base_url('admin/index/import_db'));?>

                        <div class='row'>&nbsp;</div>

                        <div class='row row-data col-xs-10 col-xs-offset-1'>
                            <div class="form-group">
                                <label for="exampleFormControlFile1">File (SQL Format)</label>
                                <input type="file" class="form-control-file" name="userfile">
                            </div>
                        </div>


                        <div class='row'>
                            <div class='col-xs-2 col-xs-offset-1'></div>
                            <div class='col-xs-8'>
                                <div class='form form-group text-right'>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fa fa-floppy-o"></i> Import
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