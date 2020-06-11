<div id="page-wrapper">
    <div class="row">
        <div class="box">
            <div class="col-lg-8 col-lg-offset-2 text-center">
                <h1>Chapter's Phone Contact List</h1>
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
                            <td><b>聯絡號碼</b></td>
                            <td><b>聯絡人</b></td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($contact as $state => $clist): ?>
                        <tr class="success"><td colspan="4"><strong><?=$state . ' (' . count($clist) . ')';?></strong></td></tr>
                            <?php foreach($clist as $chapter_id => $c): ?>
                            <tr>
                                <td nowrap>
                                    <a href="<?= base_url("admin/index/update_default_chapter/".$c['url_name']."/chapter_page"); ?>"><?= $c['name_chinese'];?></a>
                                </td>
                                <td><?php if($c['phone']):?>
                                    <?= $c['phone'];?>
                                <?php endif; ?></td>
                                <td><?php if($c['contact_person']):?>
                                    <?= $c['contact_person'];?>
                                <?php endif; ?></td>
                            </tr>
                            <?php endforeach;?>
                        <?php endforeach;?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="row">&nbsp;</div>
    
    <!-- /.row -->
</div>