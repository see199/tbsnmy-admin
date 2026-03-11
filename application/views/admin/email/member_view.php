<div id="page-wrapper">
    <div class="row">
        <div class="box">
            <div class="col-lg-8 col-lg-offset-2 text-center">
                <h1>馬密總個人會員 Email List</h1>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="box">
            <div class="col-lg-10 col-lg-offset-1">
                <table id="example" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                    <thead>
                        <tr class="info">
                            <td><b>會員編號</b></td>
                            <td><b>法號</b></td>
                            <td><b>電話</b></td>
                            <td><b>電郵</b></td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td colspan="4">
                            <input onclick="check_all_box($(this))" type="checkbox" id="all_box" class="form-check-input">
                            <label for="all_box" class='form-check-label'>All</label>
                        </td></tr>
                        <?php foreach($email as $c): ?>
                        <?php 
                            $email  = ($c['email']) ? 1 : 0; 
                        ?>
                        <tr>
                            <td><?= $c['membership_id'];?></td>
                            <td nowrap>
                                <a href="<?= base_url("admin/contact/details/".$c['contact_id']); ?>"><?= $c['name_dharma'];?></a>
                            </td>
                            <td nowrap><?= $c['phone_mobile'];?></a>
                            </td>
                            <td><?php if($c['email']):?>
                                <input onclick="check();" type="checkbox" id="email_<?=$c['contact_id'];?>" class="form-check-input email_in_list" value="<?= $c['email'];?>">
                                <label for="email_<?=$c['contact_id'];?>" class='form-check-label'><?= $c['email'];?></label>
                            <?php endif; ?></td>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="box">
            <div class="col-lg-10 col-lg-offset-1">
                <textarea rows=20 id="all_email" class='form-control' onclick="$(this).select();"></textarea>
            </div>
        </div>
    </div>
    
    <div class="row">&nbsp;</div>
    
    <!-- /.row -->
</div>

<script>
    $('.noemail').parent().parent().addClass("warning");

    function check(){
        var list = [];
        $.each($("input:checked.email_in_list"), function(){
             list.push($(this).val());
        });
        $('#all_email').text(list.join(", "));
    }

    function check_all_box(me){
        $("input[type=checkbox]").prop("checked", me.prop("checked"));
        check();
    }

    $(document).ready(function() {
        $('#all_box').prop("checked", true);
        check_all_box($('#all_box'));
    });
</script>