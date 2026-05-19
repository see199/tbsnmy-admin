<div id="page-wrapper">
    <div class="row">
        <div class="box">
            <div class="col-lg-10 col-lg-offset-1">
                <div style="text-align: center; margin-top: 15px; margin-bottom: 15px;">
                    <h1 style="margin: 0; font-weight: bold; color: #333;">全馬道場列表</h1>
                </div>
                <div style="text-align: right; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 1px solid #eee;">
                    <a href="<?= base_url('admin/chapter/export_excel'); ?>" class="btn btn-success">
                        <i class="fa fa-download"></i> 匯出 Excel (政府用途)
                    </a>
                </div>
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
                            <td><b>馬密總<br />會員編號</b></td>
                            <td><b>真佛<br />編號</b></td>
                            <td><b>分堂<br />代碼</b></td>
                            <td><b>續證截止</b></td>
                            <td><b>理事會</b></td>
                            <td><b>狀況</b></td>
                            <td><b>備註</b></td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($contact as $state => $clist): ?>
                        <tr class="success"><td colspan="8"><strong><?=$state . ' (' . count($clist) . ')';?></strong></td></tr>
                            <?php foreach($clist as $chapter_id => $c): ?>
                            <tr>
                                <td nowrap>
                                    <a href="<?= base_url("admin/index/update_default_chapter/".$c['url_name']."/chapter_page"); ?>"><?= $c['name_chinese'];?></a>
                                </td>
                                <td nowrap><?= ($c['membership_id']) ? $c['membership_id'] : '非會員'; ?></td>
                                <td nowrap><?= $c['tb_id']; ?></td>
                                <td nowrap><?= $c['tb_chapter_id']; ?></td>
                                <td nowrap><?= $c['tb_cert_renew']; ?></td>
                                <td nowrap><?= $c['ajk_session']; ?></td>
                                <td nowrap><?= $this->config->item('tbs')['chapter_status'][$c['status']]; ?></td>
                                <td width=70%><?php if($c['remarks']): ?><textarea class='form-control' rows=5 readonly><?= $c['remarks'];?></textarea><?php endif;?></td>
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