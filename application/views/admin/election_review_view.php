<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">審核改選申報：<?= $submission['chapter_name']; ?></h1>
        </div>
    </div>
    
    <?php if($submission['status'] == 'approved'): ?>
    <div class="alert alert-success">
        <i class="fa fa-check-circle"></i> 此申報已於 <?= $submission['approved_at']; ?> 審核並更新生效。以下資料僅供檢視，無法再次修改。
    </div>
    <?php else: ?>
    <div class="alert alert-warning">
        <i class="fa fa-info-circle"></i> 請比對左側（系統現有資料）與右側（道場提交資料）。您可以在右側直接修改錯字，確認無誤後點擊「一鍵核准並更新」。
    </div>
    <?php endif; ?>

    <form action="<?= base_url('admin/chapter/approve_election_submission/'.$submission['id']); ?>" method="post">
        
        <!-- 任期 -->
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading"><i class="fa fa-calendar"></i> 理事會任期</div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <label>目前系統任期</label>
                                <p class="form-control-static"><?= $submission['ajk_session'] ? $submission['ajk_session'] : '無'; ?></p>
                            </div>
                            <div class="col-md-6">
                                <label>新提交任期</label>
                                <input type="text" name="ajk_session" class="form-control" value="<?= $submitted_session; ?>" <?= $submission['status'] == 'approved' ? 'readonly' : ''; ?>>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 官方聯絡人 -->
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading"><i class="fa fa-address-book"></i> 官方聯絡人 (將更新至道場主資料)</div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h4>目前系統聯絡人</h4>
                                <table class="table table-bordered">
                                    <tr><th width="30%">主要聯絡人</th><td><?= $submission['current_contact_person']; ?></td></tr>
                                    <tr><th>其他聯絡人</th><td><?= $submission['current_phone']; ?></td></tr>
                                    <tr><th>道場/聯絡人電郵</th><td><?= $submission['current_email']; ?></td></tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h4>新提交聯絡人 (可修改)</h4>
                                <table class="table table-bordered">
                                    <tr><th width="30%">主要聯絡人</th><td><input type="text" name="contact_name" class="form-control" value="<?= isset($submitted_contact['name']) ? $submitted_contact['name'] : ''; ?>" <?= $submission['status'] == 'approved' ? 'readonly' : ''; ?>></td></tr>
                                    <tr><th>其他聯絡人</th><td><input type="text" name="contact_phone" class="form-control" value="<?= isset($submitted_contact['phone']) ? $submitted_contact['phone'] : ''; ?>" <?= $submission['status'] == 'approved' ? 'readonly' : ''; ?>></td></tr>
                                    <tr><th>道場/聯絡人電郵</th><td><input type="text" name="contact_email" class="form-control" value="<?= isset($submitted_contact['email']) ? $submitted_contact['email'] : ''; ?>" <?= $submission['status'] == 'approved' ? 'readonly' : ''; ?>></td></tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 理事名單 -->
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading"><i class="fa fa-users"></i> 理事會名單對比</div>
                    <div class="panel-body">
                        
                        <div class="row">
                            <div class="col-lg-12">
                                <h4>目前系統理事名單 (參考)</h4>
                                <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                                    <table class="table table-bordered table-striped table-condensed">
                                        <thead>
                                            <tr>
                                                <th>職位</th>
                                                <th>中文名</th>
                                                <th>法號</th>
                                                <th>英文名</th>
                                                <th>身分證</th>
                                                <th>電話</th>
                                                <th>電郵</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($current_ajk as $c): ?>
                                                <?php if($c['position'] !== '會員'): ?>
                                                <tr>
                                                    <td><?= $c['position']; ?></td>
                                                    <td><?= $c['name_chinese']; ?></td>
                                                    <td><?= $c['name_dharma']; ?></td>
                                                    <td><?= $c['name_malay']; ?></td>
                                                    <td><?= $c['nric']; ?></td>
                                                    <td><?= $c['phone_mobile']; ?></td>
                                                    <td><?= $c['email']; ?></td>
                                                </tr>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-lg-12">
                                <h4>新提交理事名單 (可修改)</h4>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>職位</th>
                                                <th>身分證</th>
                                                <th>中文名</th>
                                                <th>英文名</th>
                                                <th>電話</th>
                                                <th>電郵</th>
                                                <th>操作</th>
                                            </tr>
                                        </thead>
                                        <tbody id="submitted-members-tbody">
                                            <?php 
                                            $idx = 0;
                                            foreach($submitted_members as $m): 
                                                $is_custom = !in_array($m['position'], $standard_positions);
                                            ?>
                                            <tr class="member-row">
                                                <td>
                                                    <?php if($submission['status'] == 'approved'): ?>
                                                        <input type="text" class="form-control input-sm" value="<?= $m['position']; ?>" readonly>
                                                    <?php else: ?>
                                                        <select name="members[<?= $idx; ?>][position]" class="form-control input-sm pos-sel">
                                                            <?php foreach($standard_positions as $p): ?>
                                                                <option value="<?= $p; ?>" <?= $m['position'] == $p ? 'selected' : ''; ?>><?= $p; ?></option>
                                                            <?php endforeach; ?>
                                                            <option value="其他" <?= $is_custom ? 'selected' : ''; ?>>其他</option>
                                                        </select>
                                                        <input type="text" name="members[<?= $idx; ?>][position_custom]" class="form-control input-sm mt-1 custom-pos" value="<?= $is_custom ? $m['position'] : ''; ?>" <?= $is_custom ? '' : 'style="display:none;"'; ?>>
                                                    <?php endif; ?>
                                                </td>
                                                <td><input type="text" name="members[<?= $idx; ?>][nric]" class="form-control input-sm" value="<?= $m['nric']; ?>" <?= $submission['status'] == 'approved' ? 'readonly' : ''; ?>></td>
                                                <td><input type="text" name="members[<?= $idx; ?>][name_chinese]" class="form-control input-sm" value="<?= $m['name_chinese']; ?>" <?= $submission['status'] == 'approved' ? 'readonly' : ''; ?>></td>
                                                <td><input type="text" name="members[<?= $idx; ?>][name_malay]" class="form-control input-sm" value="<?= $m['name_malay']; ?>" <?= $submission['status'] == 'approved' ? 'readonly' : ''; ?>></td>
                                                <td><input type="text" name="members[<?= $idx; ?>][phone_mobile]" class="form-control input-sm" value="<?= $m['phone_mobile']; ?>" <?= $submission['status'] == 'approved' ? 'readonly' : ''; ?>></td>
                                                <td><input type="text" name="members[<?= $idx; ?>][email]" class="form-control input-sm" value="<?= isset($m['email']) ? $m['email'] : ''; ?>" <?= $submission['status'] == 'approved' ? 'readonly' : ''; ?>></td>
                                                <td>
                                                    <!-- Hidden fields for other data -->
                                                    <input type="hidden" name="members[<?= $idx; ?>][name_dharma]" value="<?= isset($m['name_dharma']) ? $m['name_dharma'] : ''; ?>">
                                                    <input type="hidden" name="members[<?= $idx; ?>][address]" value="<?= isset($m['address']) ? $m['address'] : ''; ?>">
                                                    <?php if($submission['status'] != 'approved'): ?>
                                                    <button type="button" class="btn btn-danger btn-sm remove-row"><i class="fa fa-trash"></i></button>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <?php 
                                            $idx++;
                                            endforeach; 
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <?php if($submission['status'] != 'approved'): ?>
        <div class="row mb-5" style="margin-bottom: 50px;">
            <div class="col-lg-12 text-center">
                <a href="<?= base_url('admin/chapter/election_submissions'); ?>" class="btn btn-default btn-lg"><i class="fa fa-arrow-left"></i> 取消返回</a>
                <button type="submit" class="btn btn-success btn-lg" style="margin-left: 15px;" onclick="return confirm('確定要核准此申報嗎？系統將自動覆蓋道場聯絡人資訊，並更新所有成員資料！');"><i class="fa fa-check-circle"></i> 一鍵核准並更新理事會</button>
            </div>
        </div>
        <?php else: ?>
        <div class="row mb-5" style="margin-bottom: 50px;">
            <div class="col-lg-12 text-center">
                <a href="<?= base_url('admin/chapter/election_submissions'); ?>" class="btn btn-default btn-lg"><i class="fa fa-arrow-left"></i> 返回列表</a>
            </div>
        </div>
        <?php endif; ?>
    </form>
</div>

<script>
$(document).ready(function() {
    $(document).on('change', '.pos-sel', function() {
        var val = $(this).val();
        var $customInput = $(this).siblings('.custom-pos');
        if(val === '其他') {
            $customInput.show();
        } else {
            $customInput.hide().val('');
        }
    });

    $(document).on('click', '.remove-row', function() {
        if(confirm('確定要刪除這筆成員嗎？')) {
            $(this).closest('tr').remove();
        }
    });
});
</script>

<style>
.mt-1 { margin-top: 5px; }
</style>
