<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-10 col-lg-offset-1">
            <h1 class="page-header"><?= lang('title_add'); ?></h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-10 col-lg-offset-1">
            <?php if (isset($error) && !empty($error)): ?>
                <div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <?= $error; ?>
                </div>
            <?php endif; ?>

            <div class="panel panel-primary">
                <div class="panel-heading">
                    <i class="fa fa-user-plus"></i> <?= lang('title_add'); ?>
                </div>
                <div class="panel-body">
                    <form role="form" method="post" action="<?= base_url('admin/user/add'); ?>">
                        
                        <!-- Email Input -->
                        <div class="form-group">
                            <label for="email"><b><?= lang('lbl_email'); ?> *</b></label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="e.g. user@example.com" value="<?= isset($email) ? html_escape($email) : ''; ?>" required>
                        </div>

                        <!-- Chapters Checkboxes -->
                        <div class="form-group">
                            <label><b><?= lang('lbl_chapter'); ?> *</b></label>
                            
                            <!-- Super Admin Checkbox -->
                            <div class="checkbox" style="margin-top: 0; margin-bottom: 15px;">
                                <label style="font-weight: bold; color: #337ab7;">
                                    <input type="checkbox" id="all_chapters" name="all_chapters" value="1" <?= isset($all_chapters) && $all_chapters ? 'checked' : ''; ?>>
                                    <i class="fa fa-cogs"></i> <?= lang('lbl_all_chapter'); ?>
                                </label>
                            </div>

                            <!-- Individual Chapters Scrollable Container -->
                            <div class="panel panel-default">
                                <div class="panel-heading" style="padding: 8px 15px;">
                                    <span class="text-muted"><i class="fa fa-list"></i> 選擇個別道場</span>
                                    <span class="pull-right">
                                        <a href="javascript:void(0);" id="select_all_individual" class="btn btn-xs btn-link" style="padding: 0 5px; font-weight: bold; text-decoration: none;"><i class="fa fa-check-square"></i> 全選</a>
                                        <span class="text-muted">|</span>
                                        <a href="javascript:void(0);" id="deselect_all_individual" class="btn btn-xs btn-link" style="padding: 0 5px; font-weight: bold; text-decoration: none;"><i class="fa fa-square"></i> 全不選</a>
                                    </span>
                                </div>
                                <div class="panel-body" style="max-height: 350px; overflow-y: auto; background-color: #fafafa;">
                                    <div class="row">
                                        <?php if (!empty($chapters)): ?>
                                            <?php foreach ($chapters as $ch): ?>
                                                <?php 
                                                    $is_checked = isset($selected_chapters) && in_array($ch['url_name'], $selected_chapters);
                                                ?>
                                                <div class="col-md-4 col-sm-6">
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox" class="chapter-checkbox" name="chapters[]" value="<?= html_escape($ch['url_name']); ?>" <?= $is_checked ? 'checked' : ''; ?>>
                                                            <?= html_escape($ch['name_chinese']); ?>
                                                            <small class="text-muted" style="display: block; font-size: 85%;"><?= html_escape($ch['url_name']); ?></small>
                                                        </label>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <div class="col-xs-12 text-center text-danger">
                                                無法載入道場列表
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row" style="margin-top: 20px;">
                            <div class="col-xs-12 text-right">
                                <a href="<?= base_url('admin/user/index'); ?>" class="btn btn-default">
                                    <i class="fa fa-times"></i> <?= lang('btn_cancel'); ?>
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save"></i> <?= lang('btn_save'); ?>
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    function toggleChapters() {
        var isChecked = $('#all_chapters').is(':checked');
        $('.chapter-checkbox').prop('disabled', isChecked);
        if (isChecked) {
            $('.chapter-checkbox').prop('checked', true);
            // Add a subtle visually disabled effect
            $('.chapter-checkbox').closest('.checkbox').css('opacity', '0.5');
        } else {
            $('.chapter-checkbox').closest('.checkbox').css('opacity', '1');
            // If it was checked automatically, uncheck when all_chapters is unchecked
            <?php if (!isset($selected_chapters) || empty($selected_chapters)): ?>
            $('.chapter-checkbox').prop('checked', false);
            <?php endif; ?>
        }
    }

    // Run on page load to handle validation redirects
    toggleChapters();

    // Trigger on change
    $('#all_chapters').change(function() {
        toggleChapters();
    });

    // Select all individual chapters
    $('#select_all_individual').click(function(e) {
        e.preventDefault();
        if (!$('#all_chapters').is(':checked')) {
            $('.chapter-checkbox').prop('checked', true);
        }
    });

    // Deselect all individual chapters
    $('#deselect_all_individual').click(function(e) {
        e.preventDefault();
        if (!$('#all_chapters').is(':checked')) {
            $('.chapter-checkbox').prop('checked', false);
        }
    });
});
</script>
