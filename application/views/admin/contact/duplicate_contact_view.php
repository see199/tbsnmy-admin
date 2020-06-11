<div id="page-wrapper">
    <div class="row"><div class="col-lg-12">&nbsp;</div></div>
    <div class="row">
        <div class="col-lg-12">
            

            <div class="alert alert-danger" role="alert">
                <h4 class="alert-heading">資料重複！ Duplicate Found!</h4>
                <p>以下的個人資料有重疊:
                <br /><?= $contact['name_chinese']; ?>
                <br /><?= $contact['name_malay']; ?>
                <br /><?= $contact['name_dharma']; ?>
                <br />可能重疊資料：
                <br /><strong><?= $contact['nric']; ?></strong>
                <br /><strong><?= $contact['phone_mobile']; ?></strong>
                <br /><strong><?= $contact['email']; ?></strong>
                </p>
                <hr>
                <p class="mb-0">
                    <a href="<?= base_url("admin/contact/details/".$contact['contact_id']); ?>">點擊這裡到相關個人資料</a>
                    <br /><a href="javascript:window.history.back();">返回</a>
                </p>
            </div>

        </div>
        <!-- /.col-lg-12 -->
    </div>
    
    <!-- /.row -->
</div>