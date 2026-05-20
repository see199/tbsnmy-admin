<!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="<?= base_url('asset/css/jquery.dataTables.css'); ?>">
<!-- DataTables -->
<script type="text/javascript" charset="utf8" src="<?= base_url('asset/js/jquery.dataTables.js'); ?>"></script>

<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">理事會改選申報管理</h1>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-plus fa-fw"></i> 產生新的改選申報連結
                </div>
                <div class="panel-body">
                    <form action="<?= base_url('admin/chapter/generate_election_link'); ?>" method="post" class="form-inline">
                        <div class="form-group" style="margin-right: 15px;">
                            <label for="chapter_id" style="margin-right: 5px;">選擇道場：</label>
                            <select name="chapter_id" id="chapter_id" class="form-control" required style="width: 250px;">
                                <option value="">請選擇道場...</option>
                                <?php foreach($all_chapters as $c): ?>
                                    <option value="<?= $c['chapter_id']; ?>"><?= $c['name_chinese']; ?> (<?= $c['url_name']; ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group" style="margin-right: 15px;">
                            <label for="ajk_session" style="margin-right: 5px;">新任期：</label>
                            <input type="text" name="ajk_session" id="ajk_session" class="form-control" placeholder="例: <?= date('Y') . '-' . (date('Y') + 2); ?>" value="<?= date('Y') . '-' . (date('Y') + 2); ?>" required>
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-magic"></i> 產生連結</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-list fa-fw"></i> 申報記錄列表
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>道場名稱</th>
                                    <th>任期</th>
                                    <th>狀態</th>
                                    <th>建立時間</th>
                                    <th>提交時間</th>
                                    <th>操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($submissions as $s): ?>
                                <tr>
                                    <td><?= $s['id']; ?></td>
                                    <td><?= $s['chapter_name']; ?></td>
                                    <td><?= $s['ajk_session']; ?></td>
                                    <td>
                                        <?php if($s['status'] == 'pending'): ?>
                                            <span class="label label-default">等待填寫</span>
                                        <?php elseif($s['status'] == 'submitted'): ?>
                                            <span class="label label-warning">待審核</span>
                                        <?php elseif($s['status'] == 'approved'): ?>
                                            <span class="label label-success">已核准</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= $s['created_at']; ?></td>
                                    <td><?= $s['submitted_at'] ? $s['submitted_at'] : '-'; ?></td>
                                    <td>
                                        <?php $link = base_url('election/form/' . $s['token']); ?>
                                        <button class="btn btn-xs btn-info copy-link-btn" data-link="<?= $link; ?>"><i class="fa fa-copy"></i> 複製連結</button>
                                        
                                        <?php if($s['status'] == 'submitted' || $s['status'] == 'approved'): ?>
                                            <a href="<?= base_url('admin/chapter/review_election_submission/' . $s['id']); ?>" class="btn btn-xs <?= $s['status'] == 'approved' ? 'btn-default' : 'btn-success'; ?>">
                                                <i class="fa <?= $s['status'] == 'approved' ? 'fa-eye' : 'fa-check-circle'; ?>"></i> <?= $s['status'] == 'approved' ? '查看' : '審核比對'; ?>
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    
    $('#dataTables-example').dataTable({
        responsive: true,
        order: [[0, 'desc']]
    });

    $(document).on('click', '.copy-link-btn', function(e) {
        e.preventDefault();
        var link = $(this).data('link');
        var btn = $(this);
        var originalText = btn.html();
        
        var copySuccess = function() {
            btn.html('<i class="fa fa-check"></i> 已複製');
            setTimeout(function() {
                btn.html(originalText);
            }, 2000);
        };

        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(link).then(copySuccess).catch(function() {
                fallbackCopyTextToClipboard(link, copySuccess);
            });
        } else {
            fallbackCopyTextToClipboard(link, copySuccess);
        }
    });

    function fallbackCopyTextToClipboard(text, callback) {
        var textArea = document.createElement("textarea");
        textArea.value = text;
        
        // Avoid scrolling to bottom
        textArea.style.top = "0";
        textArea.style.left = "0";
        textArea.style.position = "fixed";
        textArea.style.opacity = "0";

        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();

        try {
            var successful = document.execCommand('copy');
            if (successful) {
                callback();
            } else {
                prompt("請手動複製以下連結：", text);
            }
        } catch (err) {
            prompt("請手動複製以下連結：", text);
        }

        document.body.removeChild(textArea);
    }
});
</script>
