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
                <h1>Verify User 系統設定</h1>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="box">
            <div class="col-lg-6 col-lg-offset-3">
                <form class='form-horizontal' method="post" action="<?= base_url('admin/api/verify_setting');?>">
                <table id="example" class="display table table-striped table-bordered" cellspacing="0" width="100%">
                    <tr><td width=30%>
                        List：<br />Each data = 1 row
                        <br />Format: Email,Name (without space)
                    </td>
                    <td><textarea rows=20 name='list' class='form-control'></textarea></td></tr>
                   <tr><td colspan=2 align='right'>
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-floppy-o"></i> Save
                        </button>
                        <a class="btn btn-success"  href='<?= base_url();?>admin/api/verify_list'><i class="fa fa-table"></i> Back to List</a>
                    </td></tr>
                </table>
                </form>
            </div>
        </div>
    </div>


</div>