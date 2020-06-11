<div id="page-wrapper">
    <div class="row">
        <div class="box">
            <div class="col-lg-8 col-lg-offset-2 text-center">
                <h1>Chapter's Email List</h1>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="box">
            <div class="col-lg-10 col-lg-offset-1">
                <table id="example" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                    <thead>
                        <tr class="info">
                            <td><b>道場</b></td>
                            <td><b>道場電郵</b></td>
                            <td><b>聯絡電郵</b></td>
                            <td><b>主要理事電郵</b><br /><small>(主席,署理主席,副主席,秘書,總務,副秘書,副總務,堂主)</small></td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td colspan="4">
                            <input onclick="check_all_box($(this))" type="checkbox" id="all_box" class="form-check-input">
                            <label for="all_box" class='form-check-label'>All</label>
                        </td></tr>
                        <?php foreach($email as $state => $clist): ?>
                        <tr class="success"><td colspan="4"><strong><?=$state;?></strong></td></tr>
                            <?php foreach($clist as $chapter_id => $c): ?>
                            <?php 
                                $email  = ($c['email']) ? 1 : 0; 
                                $email += ($c['contact_email']) ? 1 : 0;
                            ?>
                            <tr>
                                <td nowrap>
                                    <input onclick="check_all_siblings_cousin($(this))" type="checkbox" id="all_<?=$c['chapter_id'];?>" class="form-check-input">
                                    <label for="all_<?=$c['chapter_id'];?>" class='form-check-label'>All</label>
                                    | <a href="<?= base_url("admin/index/update_default_chapter/".$c['url_name']."/chapter_page"); ?>"><?= $c['name_chinese'];?></a>
                                </td>
                                <td><?php if($c['email']):?>
                                    <input onclick="check();" type="checkbox" id="email_<?=$c['chapter_id'];?>" class="form-check-input email_in_list" value="<?= $c['email'];?>">
                                    <label for="email_<?=$c['chapter_id'];?>" class='form-check-label'><?= $c['email'];?></label>
                                <?php endif; ?></td>
                                <td><?php if($c['contact_email']):?>
                                    <input onclick="check();" type="checkbox" id="cemail_<?=$c['chapter_id'];?>" class="form-check-input email_in_list" value="<?= $c['contact_email'];?>">
                                    <label for="cemail_<?=$c['chapter_id'];?>" class='form-check-label'><?= $c['contact_email'];?></label>
                                <?php endif; ?></td>
                                <td nowrap>
                                    <?php if(count($c['vip']) > 0): ?>
                                        <input onclick="check_all_siblings($(this))" type="checkbox" id="vip_<?=$c['chapter_id'];?>" class="form-check-input" >
                                        <label for="vip_<?=$c['chapter_id'];?>" class="form-check-label">Check All</label>
                                    <?php endif;?>
                                    <?php foreach($c['vip'] as $k => $vip): $email += ($vip['email']) ? 1 : 0; ?>
                                    <br /><input onclick="check();" type="checkbox" id="vip_<?=$vip['contact_id'];?>" class="form-check-input email_in_list" value="<?= $vip['email'];?>">
                                    <label for="vip_<?=$vip['contact_id'];?>" class='form-check-label'><?= $vip['email'];?></label>
                                    <?= $vip['name_dharma'];?> (<?= $vip['position']; ?>)
                                <?php endforeach;?>
                                <?php if($email == 0): ?><input type='hidden' class="noemail"><?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach;?>
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

    function check_all_siblings(me){
        me.siblings().prop("checked", me.prop("checked"));
        check();
    }

    function check_all_siblings_cousin(me){
        me.parent().siblings().children().prop("checked", me.prop("checked"));
        check();
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