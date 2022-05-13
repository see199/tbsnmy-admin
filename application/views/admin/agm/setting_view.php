<script>
    function delete_all_registrant(){
        $.ajax({
            url: '<?= base_url('admin/agm/ajax_del_all_reg'); ?>',
            type: 'post',
            success: function( data, textStatus, jQxhr ){
                me = JSON.parse(data);
                if(me.success){
                    alert(me.msg);
                }
            }
        });
    }

    function reset_login_date(){
        $.ajax({
            url: '<?= base_url('admin/agm/ajax_reset_login_date'); ?>',
            type: 'post',
            success: function( data, textStatus, jQxhr ){
                me = JSON.parse(data);
                if(me.success){
                    alert(me.msg);
                }
            }
        });
    }
</script>

<div id="page-wrapper">

    <div class="row">
        <div class="box">
            <div class="col-lg-8 col-lg-offset-2 text-center form-inline">
                <h1>會員大會系統設定</h1>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="box">
            <div class="col-lg-6 col-lg-offset-3">
                <form class='form-horizontal' method="post" action="<?= base_url('admin/agm/setting');?>">
                <table id="example" class="display table table-striped table-bordered" cellspacing="0" width="100%">
                    <tr><td>第幾屆：</td><td><input type='text' class='form-control' name="session" value="<?= $setting['session']; ?>" /></td></tr>
                    <tr><td>年份：</td><td><input type='text' class='form-control' name="year" value="<?= $setting['year']; ?>" /></td></tr>
                    <tr><td>會員大會日期：</td><td><input type='text' class='form-control' name="date" value="<?= $setting['date']; ?>" /></td></tr>
                    <tr><td>會員大會時間：</td><td><input type='text' class='form-control' name="time" value="<?= $setting['time']; ?>" /></td></tr>
                    <tr><td>ZOOM ID：</td><td><input type='text' class='form-control' name="zoom_id" value="<?= $setting['zoom_id']; ?>" /></td></tr>
                    <tr><td>Access Token：</td><td><input type='text' class='form-control' name="access_token" value="<?= $setting['access_token']; ?>" /><br /><small>Generate at: <a href="https://marketplace.zoom.us/develop/apps/hpkMCtQGQa2t0MAGvIutEA/credentials" target="_blank">https://marketplace.zoom.us/develop/apps/hpkMCtQGQa2t0MAGvIutEA/credentials</a><br />Zoom Account: tbsdade@gmail.com</small></td></tr>
                    <tr><td>Token Expiry：<small><br />*reference only</small></td><td><input type='text' class='form-control' name="token_expiry" value="<?= $setting['token_expiry']; ?>" /></td></tr>
                    <tr><td colspan=2 align='right'>
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-floppy-o"></i> Save
                        </button>
                        <button type="button" onclick="delete_all_registrant()" class="btn btn-danger">
                            <i class="fa fa-times"></i> Reset Pass Years' Attendance
                        </button>
                        <button type="button" onclick="reset_login_date()" class="btn btn-danger">
                            <i class="fa fa-times"></i> Reset Zoom Login Date
                        </button>
                    </td></tr>
                </table>
                </form>
            </div>
        </div>
    </div>


</div>