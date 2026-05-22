<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $submission['chapter_name']; ?> - 理事會改選申報</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Noto+Sans+TC:wght@300;400;500;700&display=swap" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        :root {
            --primary: #df731b;
            --primary-hover: #c56111;
            --bg-dark: #0f172a;
            --bg-card: #ffffff;
            --text-main: #334155;
            --text-light: #64748b;
            --border-color: #e2e8f0;
            --success: #10b981;
            --danger: #ef4444;
            --info: #3b82f6;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Outfit', 'Noto Sans TC', sans-serif;
            background-color: #f8fafc;
            color: var(--text-main);
            line-height: 1.6;
            padding: 40px 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        header {
            background: linear-gradient(135deg, #df731b, #f97316);
            padding: 40px;
            border-radius: 20px;
            color: white;
            margin-bottom: 30px;
            box-shadow: 0 10px 25px -5px rgba(223, 115, 27, 0.3);
            position: relative;
            overflow: hidden;
        }

        header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -30%;
            width: 100%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 60%);
            transform: rotate(-15deg);
            pointer-events: none;
        }

        header h1 {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .card {
            background: var(--bg-card);
            border-radius: 16px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            border: 1px solid var(--border-color);
        }

        .card-title {
            font-size: 1.4rem;
            font-weight: 600;
            margin-bottom: 20px;
            color: #1e293b;
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 2px solid #f1f5f9;
            padding-bottom: 12px;
        }

        .card-title i {
            color: var(--primary);
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-size: 0.95rem;
            font-weight: 500;
            margin-bottom: 8px;
            color: #475569;
        }

        input[type="text"],
        input[type="email"],
        select,
        textarea {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 0.95rem;
            font-family: inherit;
            color: var(--text-main);
            transition: all 0.3s ease;
            background-color: #f8fafc;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: var(--primary);
            background-color: white;
            box-shadow: 0 0 0 3px rgba(223, 115, 27, 0.15);
        }

        .badge-info {
            background-color: rgba(59, 130, 246, 0.1);
            color: var(--info);
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-left: 10px;
        }

        /* Responsive Table */
        .table-responsive {
            overflow-x: auto;
            margin-bottom: 20px;
            border-radius: 12px;
            border: 1px solid var(--border-color);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        th {
            background-color: #f1f5f9;
            color: #475569;
            font-weight: 600;
            padding: 14px 16px;
            font-size: 0.9rem;
            border-bottom: 2px solid var(--border-color);
        }

        td {
            padding: 12px 16px;
            border-bottom: 1px solid var(--border-color);
            vertical-align: middle;
            background: white;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 24px;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
        }

        .btn-primary {
            background-color: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            box-shadow: 0 4px 12px rgba(223, 115, 27, 0.2);
        }

        .btn-secondary {
            background-color: #f1f5f9;
            color: #475569;
            border: 1px solid #cbd5e1;
        }

        .btn-secondary:hover {
            background-color: #e2e8f0;
        }

        .btn-danger {
            background-color: rgba(239, 68, 68, 0.1);
            color: var(--danger);
            padding: 8px;
            border-radius: 8px;
        }

        .btn-danger:hover {
            background-color: var(--danger);
            color: white;
        }

        .position-select-wrapper {
            display: flex;
            flex-direction: column;
            gap: 6px;
            min-width: 140px;
        }

        .custom-pos-input {
            display: none;
            margin-top: 4px;
        }

        /* Contact Selector Card */
        .contact-picker-wrapper {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
        }

        .contact-card {
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 20px;
            background-color: #f8fafc;
            display: flex;
            flex-direction: column;
            gap: 12px;
            transition: all 0.3s ease;
        }

        .contact-card.active {
            border-color: var(--primary);
            background-color: rgba(223, 115, 27, 0.02);
            box-shadow: 0 0 0 3px rgba(223, 115, 27, 0.1);
        }

        .contact-card-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #1e293b;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .form-footer {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            margin-top: 20px;
        }

        /* Custom Alert Dialog */
        .alert-box {
            padding: 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .alert-box-warning {
            background-color: #fef3c7;
            color: #d97706;
            border: 1px solid #fde68a;
        }

        /* NRIC prefilling status */
        .nric-status {
            font-size: 0.8rem;
            margin-top: 4px;
            font-weight: 500;
            min-height: 18px;
        }

        /* Mobile adaptation */
        @media (max-width: 768px) {
            body {
                padding: 15px 10px;
            }
            header {
                padding: 20px;
            }
            header h1 {
                font-size: 1.6rem;
            }
            .card {
                padding: 15px;
            }
            th, td {
                padding: 8px;
            }
            input[type="text"], select {
                padding: 8px 10px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <header>
        <h1><?= $submission['chapter_name']; ?></h1>
        <p><i class="fa-solid fa-file-invoice"></i> 真佛宗道場理事會改選申報表</p>
        <div style="margin-top: 15px; font-size: 0.9rem; opacity: 0.85;">
            <span>真佛編號: <?= $submission['tb_id'] ? $submission['tb_id'] : 'C00000'; ?></span> |
            <span>會員代碼: <?= $submission['membership_id'] ? $submission['membership_id'] : '0000'; ?></span>
        </div>
    </header>

    <?= form_open(base_url('election/submit/' . $token), array('id' => 'election_form')); ?>
    
    <!-- Term Session Card -->
    <div class="card">
        <div class="card-title">
            <i class="fa-solid fa-calendar-days"></i> 理事會屆次與任期
        </div>
        <div class="form-group" style="max-width: 400px;">
            <label for="ajk_session">新一屆理事會任期年份 <span style="color:var(--danger)">*</span></label>
            <input type="text" name="ajk_session" id="ajk_session" required placeholder="例如: 2026-2028" value="<?= $ajk_session; ?>" />
            <p style="font-size: 0.85rem; color: var(--text-light); margin-top: 6px;">請依照貴堂理事會章程填寫，例如「2026-2028」或「2026-2029」。</p>
        </div>
    </div>

    <!-- Board Members Card -->
    <div class="card">
        <div class="card-title" style="justify-content: space-between;">
            <div><i class="fa-solid fa-users-line"></i> 理事成員申報名單</div>
            <button type="button" class="btn btn-secondary" id="add_member_btn" style="padding: 8px 16px; font-size: 0.9rem;">
                <i class="fa-solid fa-user-plus"></i> 新增理事成員
            </button>
        </div>
        
        <div class="alert-box alert-box-warning">
            <i class="fa-solid fa-circle-info"></i>
            <div><strong>小提示：</strong> 填入 <strong>NRIC (身分證號)</strong> 後，若系統偵測到其曾填寫過，會自動載入該成員之通訊細節以簡化填寫流程。</div>
        </div>

        <div class="table-responsive">
            <table id="members_table">
                <thead>
                    <tr>
                        <th width="120px">職位 <span style="color:var(--danger)">*</span></th>
                        <th width="150px">NRIC (身分證號) <span style="color:var(--danger)">*</span></th>
                        <th width="160px">姓名 (中/英) <span style="color:var(--danger)">*</span></th>
                        <th width="140px">手機電話 <span style="color:var(--danger)">*</span></th>
                        <th width="180px">電郵地址</th>
                        <th width="60px">刪除</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $standard_positions = array('主席', '署理主席', '副主席', '秘書', '副秘書', '財政', '副財政', '理事', '堂主', '顧問');
                    $index = 0;
                    if (!empty($members)):
                        foreach ($members as $m): 
                            $is_custom_pos = !in_array($m['position'], $standard_positions);
                    ?>
                        <tr class="member-row" data-index="<?= $index; ?>">
                            <td>
                                <div class="position-select-wrapper">
                                    <select name="members[<?= $index; ?>][position]" class="position-selector" required>
                                        <?php foreach ($standard_positions as $p): ?>
                                            <option value="<?= $p; ?>" <?= ($m['position'] === $p) ? 'selected' : ''; ?>><?= $p; ?></option>
                                        <?php endforeach; ?>
                                        <option value="其他" <?= $is_custom_pos ? 'selected' : ''; ?>>其他 (自訂)</option>
                                    </select>
                                    <input type="text" name="members[<?= $index; ?>][position_custom]" class="custom-pos-input" placeholder="請輸入自訂職位" value="<?= $is_custom_pos ? $m['position'] : ''; ?>" <?= $is_custom_pos ? 'style="display:block"' : ''; ?> />
                                </div>
                            </td>
                            <td>
                                <input type="text" name="members[<?= $index; ?>][nric]" class="nric-input" required placeholder="XXXXXX-XX-XXXX" value="<?= $m['nric']; ?>" />
                                <div class="nric-status" style="color: var(--success)"></div>
                            </td>
                            <td>
                                <input type="text" name="members[<?= $index; ?>][name_chinese]" required placeholder="中文名" value="<?= $m['name_chinese']; ?>" style="margin-bottom: 5px;" />
                                <input type="text" name="members[<?= $index; ?>][name_malay]" required placeholder="英文名" value="<?= $m['name_malay']; ?>" />
                            </td>
                            <td>
                                <input type="text" name="members[<?= $index; ?>][phone_mobile]" class="phone-input" required placeholder="0123456789" value="<?= $m['phone_mobile']; ?>" />
                            </td>
                            <td>
                                <input type="text" name="members[<?= $index; ?>][email]" placeholder="example@mail.com" value="<?= isset($m['email']) ? $m['email'] : ''; ?>" />
                            </td>
                            <td>
                                <input type="hidden" name="members[<?= $index; ?>][name_dharma]" value="<?= isset($m['name_dharma']) ? $m['name_dharma'] : ''; ?>">
                                <input type="hidden" name="members[<?= $index; ?>][address]" value="<?= isset($m['address']) ? $m['address'] : ''; ?>">
                                <button type="button" class="btn-danger remove-row-btn"><i class="fa-solid fa-trash-can"></i></button>
                            </td>
                        </tr>
                    <?php 
                            $index++;
                        endforeach;
                    else: 
                        // Prefill empty template with basic roles if none exist
                        $default_roles = array('主席', '副主席', '秘書', '財政', '理事', '理事', '理事');
                        foreach ($default_roles as $role):
                    ?>
                        <tr class="member-row" data-index="<?= $index; ?>">
                            <td>
                                <div class="position-select-wrapper">
                                    <select name="members[<?= $index; ?>][position]" class="position-selector" required>
                                        <?php foreach ($standard_positions as $p): ?>
                                            <option value="<?= $p; ?>" <?= ($p === $role) ? 'selected' : ''; ?>><?= $p; ?></option>
                                        <?php endforeach; ?>
                                        <option value="其他">其他 (自訂)</option>
                                    </select>
                                    <input type="text" name="members[<?= $index; ?>][position_custom]" class="custom-pos-input" placeholder="請輸入自訂職位" />
                                </div>
                            </td>
                            <td>
                                <input type="text" name="members[<?= $index; ?>][nric]" class="nric-input" required placeholder="XXXXXX-XX-XXXX" />
                                <div class="nric-status" style="color: var(--success)"></div>
                            </td>
                            <td>
                                <input type="text" name="members[<?= $index; ?>][name_chinese]" required placeholder="中文名" style="margin-bottom: 5px;" />
                                <input type="text" name="members[<?= $index; ?>][name_malay]" required placeholder="英文名" />
                            </td>
                            <td>
                                <input type="text" name="members[<?= $index; ?>][phone_mobile]" class="phone-input" required placeholder="0123456789" />
                            </td>
                            <td>
                                <input type="text" name="members[<?= $index; ?>][email]" placeholder="example@mail.com" />
                            </td>
                            <td>
                                <input type="hidden" name="members[<?= $index; ?>][name_dharma]" value="">
                                <input type="hidden" name="members[<?= $index; ?>][address]" value="">
                                <button type="button" class="btn-danger remove-row-btn"><i class="fa-solid fa-trash-can"></i></button>
                            </td>
                        </tr>
                    <?php 
                            $index++;
                        endforeach; 
                    endif;
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Official Contacts Card -->
    <div class="card">
        <div class="card-title">
            <i class="fa-solid fa-address-book"></i> 官方聯絡人指派 (供秘書處與馬密總通訊使用)
        </div>
        
        <p style="font-size: 0.95rem; color: var(--text-light); margin-bottom: 20px;">
            請更新道場的官方聯絡人。(一般上是<strong>主席、秘書或財政</strong>)。
        </p>

        <div class="contact-picker-wrapper">
            <!-- Form inputs -->
            <div class="form-group">
                <label for="contact_name">主要聯絡人 <span style="color:var(--danger)">*</span></label>
                <input type="text" name="contact_name" id="contact_name" required placeholder="請輸入主要聯絡人" value="<?= isset($contact['name']) ? $contact['name'] : ''; ?>" />
            </div>

            <div class="form-group">
                <label for="contact_phone">其他聯絡人 <span style="color:var(--danger)">*</span></label>
                <input type="text" name="contact_phone" id="contact_phone" required placeholder="請輸入其他聯絡人" value="<?= isset($contact['phone']) ? $contact['phone'] : ''; ?>" />
            </div>

            <div class="form-group" style="grid-column: 1 / -1; max-width: 450px;">
                <label for="contact_email">道場/聯絡人電郵</label>
                <input type="text" name="contact_email" id="contact_email" placeholder="example@mail.com, other@mail.com" value="<?= isset($contact['email']) ? $contact['email'] : ''; ?>" />
            </div>
        </div>
    </div>

    <!-- Actions Footer -->
    <div class="form-footer">
        <button type="button" class="btn btn-secondary" onclick="window.close();"><i class="fa-solid fa-xmark"></i> 關閉頁面</button>
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-cloud-arrow-up"></i> 確認並提交申報名單</button>
    </div>

    </form>
</div>

<script>
$(document).ready(function() {
    var token = '<?= $token; ?>';
    var nextRowIndex = <?= $index; ?>;
    
    // Regular expression for Malaysian NRIC validation
    var nricRegex = /^\d{6}-?\d{2}-?\d{4}$/;


    // Toggle custom position text box
    $(document).on('change', '.position-selector', function() {
        var $select = $(this);
        var $customInput = $select.next('.custom-pos-input');
        if ($select.val() === '其他') {
            $customInput.slideDown().prop('required', true);
        } else {
            $customInput.slideUp().prop('required', false).val('');
        }
    });



    // NRIC AJAX prefill validation
    $(document).on('keyup change', '.nric-input', function() {
        var $input = $(this);
        var val = $input.val().trim();
        var $row = $input.closest('.member-row');
        var idx = $row.data('index');
        var $status = $input.next('.nric-status');
        
        // Auto format 12 digit NRIC on the fly
        var cleaned = val.replace(/\D/g, '');
        if (cleaned.length === 12 && !val.includes('-')) {
            $input.val(cleaned.substring(0,6) + '-' + cleaned.substring(6,8) + '-' + cleaned.substring(8,12));
            val = $input.val();
        }

        if (nricRegex.test(val)) {
            $status.css('color', '#3b82f6').html('<i class="fa-solid fa-spinner fa-spin"></i> 比對資料中...');
            $.ajax({
                url: '<?= base_url("election/ajax_get_contact"); ?>',
                method: 'POST',
                data: { nric: val, token: token },
                dataType: 'json',
                success: function(response) {
                    if (response && response.contact_id) {
                        $status.css('color', '#10b981').html('<i class="fa-solid fa-circle-check"></i> 已帶出系統舊資料');
                        
                        // Prefill fields
                        if (response.name_chinese) $row.find('input[name="members['+idx+'][name_chinese]"]').val(response.name_chinese);
                        if (response.name_malay) $row.find('input[name="members['+idx+'][name_malay]"]').val(response.name_malay);
                        if (response.phone_mobile) $row.find('input[name="members['+idx+'][phone_mobile]"]').val(response.phone_mobile);
                        if (response.email) $row.find('input[name="members['+idx+'][email]"]').val(response.email);
                        if (response.name_dharma) $row.find('input[name="members['+idx+'][name_dharma]"]').val(response.name_dharma);
                        if (response.address) $row.find('input[name="members['+idx+'][address]"]').val(response.address);
                        
                        updateContactPicker();
                    } else {
                        $status.css('color', '#64748b').html('<i class="fa-solid fa-circle-plus"></i> 全新理事資料');
                    }
                },
                error: function() {
                    $status.html('');
                }
            });
        } else {
            $status.html('');
        }
    });

    // Auto format phone number on blur
    $(document).on('blur', '.phone-input', function() {
        var $input = $(this);
        var val = $input.val().trim();
        var digits = val.replace(/\D/g, '');
        if (digits) {
            if (digits.substring(0, 3) === '601') {
                $input.val('+'+digits);
            } else if (digits.substring(0, 2) === '01') {
                $input.val('+6'+digits);
            } else if (digits.substring(0, 1) === '1') {
                $input.val('+60'+digits);
            }
        }
    });

    // Add member row
    $('#add_member_btn').on('click', function() {
        var newIndex = nextRowIndex++;
        var rowHtml = `
            <tr class="member-row" data-index="${newIndex}">
                <td>
                    <div class="position-select-wrapper">
                        <select name="members[${newIndex}][position]" class="position-selector" required>
                            <?php foreach ($standard_positions as $p): ?>
                                <option value="<?= $p; ?>" <?= ($p === '理事') ? 'selected' : ''; ?>><?= $p; ?></option>
                            <?php endforeach; ?>
                            <option value="其他">其他 (自訂)</option>
                        </select>
                        <input type="text" name="members[${newIndex}][position_custom]" class="custom-pos-input" placeholder="請輸入自訂職位" />
                    </div>
                </td>
                <td>
                    <input type="text" name="members[${newIndex}][nric]" class="nric-input" required placeholder="XXXXXX-XX-XXXX" />
                    <div class="nric-status" style="color: var(--success)"></div>
                </td>
                <td>
                    <input type="text" name="members[${newIndex}][name_chinese]" required placeholder="中文名" style="margin-bottom: 5px;" />
                    <input type="text" name="members[${newIndex}][name_malay]" required placeholder="英文名" />
                </td>
                <td>
                    <input type="text" name="members[${newIndex}][phone_mobile]" class="phone-input" required placeholder="0123456789" />
                </td>
                <td>
                    <input type="text" name="members[${newIndex}][email]" placeholder="example@mail.com" />
                </td>
                <td>
                    <input type="hidden" name="members[${newIndex}][name_dharma]" value="">
                    <input type="hidden" name="members[${newIndex}][address]" value="">
                    <button type="button" class="btn-danger remove-row-btn"><i class="fa-solid fa-trash-can"></i></button>
                </td>
            </tr>
        `;
        $('#members_table tbody').append(rowHtml);
        updateContactPicker();
    });

    // Remove member row
    $(document).on('click', '.remove-row-btn', function() {
        var $row = $(this).closest('tr');
        if ($('#members_table tbody tr').length > 1) {
            $row.fadeOut(300, function() {
                $row.remove();
                updateContactPicker();
            });
        } else {
            alert('名單至少需保留一名成員！');
        }
    });

    // Confirm before submit
    $('#election_form').on('submit', function(e) {
        if (!confirm('您確定要提交此改選理事名單嗎？\n提交後系統將自動優化與格式化名單，並送交馬密總秘書處進行審核。')) {
            e.preventDefault();
        }
    });

    // Initialize Picker
    updateContactPicker();
});
</script>

</body>
</html>
