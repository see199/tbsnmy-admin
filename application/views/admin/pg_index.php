<?php
// Pre-processing metrics for display
$tbs_config = isset($tbs_config) ? $tbs_config : array();

// 1. Chapters pre-processing
$active_chapters = 0;
$total_chapters = 0;
$chapters_by_status = array();
if (isset($stats['chap_status'])) {
    foreach ($stats['chap_status'] as $cs) {
        $status_code = $cs['status'];
        $count = (int)$cs['count'];
        $total_chapters += $count;
        if (in_array($status_code, array('A', '1', '2'))) {
            $active_chapters += $count;
        }
        $chapters_by_status[$status_code] = $count;
    }
}

// 2. Filter Dharma Staff to specified positions: 上師 (SS), 教授師 (JSS), 法師 (FS), 講師 (JS), 助教 (ZJ)
$pos_list = array(
    "SS"   => "上師",
    "JSS"  => "教授師",
    "FS"   => "法師",
    "JS"   => "講師",
    "ZJ"   => "助教",
);

$staff_status_map = array(
    'Normal'  => '正常 (Active)',
    'Leaved'  => '離開 (Left)',
    'Dead'    => '往生 (Deceased)',
    'Rest'    => '修養 (Resting)',
    'Pending' => '留職待聘 (Pending)',
    'Retired' => '卸任 (Retired)',
);

$active_dharma = 0;
$total_dharma = 0;
$dharma_matrix = array();
$pos_totals = array();
$stat_totals = array();

// Initialize matrix using staff_status_map keys to ensure no undefined index errors occur
foreach ($pos_list as $pos_key => $pos_name) {
    $pos_totals[$pos_key] = 0;
    foreach ($staff_status_map as $stat_key => $stat_name) {
        $dharma_matrix[$pos_key][$stat_key] = 0;
        $stat_totals[$stat_key] = 0;
    }
}

if (isset($stats['dharma'])) {
    foreach ($stats['dharma'] as $d) {
        $pos = $d['dharma_position'];
        $stat = $d['status'];
        $count = (int)$d['count'];
        
        // Sum total active (Normal) across all registered dharma staff
        if ($stat === 'Normal') {
            $active_dharma += $count;
        }
        $total_dharma += $count;

        // Populate matrix only for the 5 filtered positions and status keys that exist in the map
        if (isset($pos_list[$pos]) && isset($staff_status_map[$stat])) {
            $dharma_matrix[$pos][$stat] = $count;
            $pos_totals[$pos] += $count;
            $stat_totals[$stat] += $count;
        }
    }
}

// Recalculate total_dharma_filtered for matrix summary
$total_dharma_filtered = array_sum($pos_totals);

// 3. Member pre-processing
$active_personal = 0;
$total_personal = 0;
$personal_by_status = array();
if (isset($stats['member'])) {
    foreach ($stats['member'] as $m) {
        $stat = $m['status'];
        $count = (int)$m['count'];
        $total_personal += $count;
        if ($stat === 'A') {
            $active_personal = $count;
        }
        $personal_by_status[$stat] = $count;
    }
}

$active_group = 0;
$total_group = 0;
$group_by_status = array();
if (isset($stats['group'])) {
    foreach ($stats['group'] as $g) {
        $stat = $g['status'];
        $count = (int)$g['count'];
        $total_group += $count;
        if ($stat === 'A') {
            $active_group = $count;
        }
        $group_by_status[$stat] = $count;
    }
}

// 4. Status mapping dictionaries
$member_status_map = array(
    'A' => '正常 (Active)',
    'P' => '留職待聘 (Pending)',
    'D' => '往生 (Deceased)',
    'R' => '卸任 (Retired)',
    'I' => '離開/非會員 (Left)'
);

$chapter_status_map = array(
    'A' => '正常 (Active)',
    'S' => '無活動 (Inactive)',
    'I' => '解散 (Dissolved)',
    'P' => '未獲政府認可 (Pending Government Approval)',
    '1' => '宗委會第一階段認可 (Stage 1 Recognition)',
    '2' => '宗委會第二階段認可 (Stage 2 Recognition)'
);

?>

<style>
    /* Styling Dashboard Elements */
    .dashboard-header {
        display: flex;
        align-content: center;
        justify-content: space-between;
        flex-wrap: wrap;
        padding-bottom: 15px;
        margin-bottom: 20px;
        border-bottom: 1px solid #e3e6f0;
    }
    
    .dashboard-title h2 {
        margin: 0;
        font-weight: 700;
        color: #333333;
        font-size: 28px;
    }
    
    .dashboard-title p {
        margin: 5px 0 0 0;
        color: #858796;
        font-size: 14px;
    }
    
    .btn-refresh {
        background-color: #4e73df;
        color: #ffffff;
        border: none;
        border-radius: 5px;
        padding: 8px 16px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 6px rgba(78, 115, 223, 0.2);
        transition: all 0.2s ease-in-out;
        text-decoration: none;
    }
    
    .btn-refresh:hover {
        background-color: #2e59d9;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(78, 115, 223, 0.35);
        transform: translateY(-2px);
        text-decoration: none;
    }

    .btn-refresh:active {
        transform: translateY(0);
    }
    
    /* Welcome Banner Card */
    .welcome-card {
        background-color: #ffffff;
        border-left: 5px solid #4e73df;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        padding: 15px 25px;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    
    .welcome-text h3 {
        margin: 0 0 5px 0;
        font-weight: 600;
        color: #4e73df;
    }
    
    .welcome-text p {
        margin: 0;
        color: #666666;
        font-size: 15px;
    }
    
    .welcome-logo img {
        height: 60px;
        width: auto;
        border-radius: 4px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    /* Metric Cards Grid */
    .metric-card-container {
        margin-bottom: 25px;
    }
    
    .metric-card-link {
        text-decoration: none !important;
        display: block;
        margin-bottom: 15px;
    }
    
    .metric-card {
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        overflow: hidden;
        border: none;
        color: #ffffff;
        position: relative;
        padding: 20px;
        min-height: 120px;
        cursor: pointer;
    }
    
    .metric-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }
    
    /* Metric Gradients */
    .bg-personal-member {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
    }
    
    .bg-group-member {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    }
    
    .bg-chapters {
        background: linear-gradient(135deg, #f12711 0%, #f5af19 100%);
    }
    
    .bg-propagation {
        background: linear-gradient(135deg, #8A2387 0%, #E94057 50%, #F27121 100%);
    }
    
    .metric-icon {
        position: absolute;
        right: 15px;
        bottom: 10px;
        font-size: 55px;
        opacity: 0.18;
        transition: all 0.3s ease-in-out;
    }
    
    .metric-card:hover .metric-icon {
        transform: scale(1.15) rotate(-5deg);
        opacity: 0.25;
    }
    
    .metric-label {
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 600;
        opacity: 0.85;
        margin-bottom: 5px;
    }
    
    .metric-value {
        font-size: 36px;
        font-weight: 700;
        line-height: 1.1;
        margin-bottom: 5px;
    }
    
    .metric-subtitle {
        font-size: 12px;
        opacity: 0.75;
    }

    /* Panels & Tables Styling */
    .dashboard-panel {
        background-color: #ffffff;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        border: 1px solid #e3e6f0;
        margin-bottom: 25px;
        overflow: hidden;
    }
    
    .dashboard-panel-header {
        background-color: #f8f9fc;
        border-bottom: 1px solid #e3e6f0;
        padding: 15px 20px;
        font-weight: 600;
        color: #4e73df;
        font-size: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .dashboard-panel-body {
        padding: 20px;
    }
    
    .table-custom {
        margin-bottom: 0;
    }
    
    .table-custom th {
        background-color: #f8f9fc;
        color: #495057;
        font-weight: 600;
        border-bottom: 2px solid #e3e6f0 !important;
        text-align: center;
        vertical-align: middle !important;
        font-size: 13px;
    }
    
    .table-custom td {
        vertical-align: middle !important;
        font-size: 13px;
        text-align: center;
        border-top: 1px solid #e3e6f0;
    }
    
    .table-custom tbody tr {
        transition: background-color 0.15s ease-in-out;
    }
    
    .table-custom tbody tr:hover {
        background-color: rgba(78, 115, 223, 0.04);
    }
    
    .table-custom td.text-left-aligned, .table-custom th.text-left-aligned {
        text-align: left;
    }

    /* State Progress Bar Styling */
    .state-list-container {
        max-height: 380px;
        overflow-y: auto;
        padding-right: 5px;
    }

    .state-row {
        margin-bottom: 12px;
    }

    .state-label-row {
        display: flex;
        justify-content: space-between;
        font-size: 12px;
        font-weight: 600;
        color: #4e73df;
        margin-bottom: 4px;
    }

    .state-progress-bar {
        height: 8px;
        background-color: #eaecf4;
        border-radius: 4px;
        overflow: hidden;
    }

    .state-progress-fill {
        height: 100%;
        background-color: #f6c23e; /* default yellow */
        border-radius: 4px;
        transition: width 0.6s ease;
    }

    .state-progress-fill-top {
        background-color: #4e73df; /* top states get primary blue */
    }

    .state-progress-fill-mid {
        background-color: #36b9cc; /* mid states get teal */
    }
</style>

<div id="page-wrapper" style="background-color: #f8f9fc; min-height: 100vh; padding: 20px;">
    
    <!-- Outer Centering Grid Container (col-10 layout instead of col-12) -->
    <div class="row">
        <div class="col-lg-10 col-lg-offset-1">
            
            <!-- Success Flash Alert -->
            <?php if ($this->session->flashdata('success_message')): ?>
                <div class="alert alert-success alert-dismissible" role="alert" style="border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); margin-top: 10px;">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <i class="fa fa-check-circle" style="margin-right: 5px; font-size: 16px; vertical-align: middle;"></i> 
                    <span style="vertical-align: middle; font-weight: 600;"><?= $this->session->flashdata('success_message'); ?></span>
                </div>
            <?php endif; ?>

            <!-- Dashboard Control Header -->
            <div class="dashboard-header">
                <div class="dashboard-title">
                    <h2>控制台 <span style="font-weight: 300; font-size: 24px; color: #858796;">Dashboard</span></h2>
                    <p>最後更新時間 Last Updated: <strong style="color: #5a5c69;"><?= isset($last_updated) ? $last_updated : date('Y-m-d H:i:s'); ?></strong></p>
                </div>
                <div>
                    <a href="<?= base_url('admin/index/refresh_stats'); ?>" class="btn-refresh">
                        <i class="fa-solid fa-arrows-rotate"></i> 刷新數據 Refresh Data
                    </a>
                </div>
            </div>

            <!-- Welcome Banner Card -->
            <div class="welcome-card">
                <div class="welcome-text">
                    <h3>歡迎登入馬密總管理後台</h3>
                    <p>您好，系統已為您載入最新的會務數據分析。本面板數據將於每月 <strong>1日</strong> 及 <strong>15日</strong> 自動更新，您亦可點擊上方按鈕進行手動即時刷新。</p>
                </div>
                <div class="welcome-logo hidden-xs">
                    <img src="http://admin.tbsn.my/asset/img/tbsn-my-logo.jpg" alt="TBSN MY LOGO" />
                </div>
            </div>

            <!-- Metric Cards Grid -->
            <div class="row metric-card-container">
                <!-- Personal Members (Linked to admin/email/member) -->
                <div class="col-sm-6 col-md-3">
                    <a href="<?= base_url('admin/email/member'); ?>" class="metric-card-link">
                        <div class="metric-card bg-personal-member">
                            <div class="metric-label">個人會員 (Active)</div>
                            <div class="metric-value"><?= $active_personal; ?></div>
                            <div class="metric-subtitle">總會員數 Total: <?= $total_personal; ?> 人</div>
                            <div class="metric-icon"><i class="fa-solid fa-user-check"></i></div>
                        </div>
                    </a>
                </div>
                
                <!-- Group Members (Linked to admin/email/chapter) -->
                <div class="col-sm-6 col-md-3">
                    <a href="<?= base_url('admin/email/chapter'); ?>" class="metric-card-link">
                        <div class="metric-card bg-group-member">
                            <div class="metric-label">團體會員 (Active)</div>
                            <div class="metric-value"><?= $active_group; ?></div>
                            <div class="metric-subtitle">總道場會員 Total: <?= $total_group; ?> 間</div>
                            <div class="metric-icon"><i class="fa-solid fa-hotel"></i></div>
                        </div>
                    </a>
                </div>
                
                <!-- Total Chapters (Linked to admin/chapter/list_all) -->
                <div class="col-sm-6 col-md-3">
                    <a href="<?= base_url('admin/chapter/list_all'); ?>" class="metric-card-link">
                        <div class="metric-card bg-chapters">
                            <div class="metric-label">全馬道場 (Active)</div>
                            <div class="metric-value"><?= $active_chapters; ?></div>
                            <div class="metric-subtitle">系統登記總道場 Total: <?= $total_chapters; ?> 間</div>
                            <div class="metric-icon"><i class="fa-solid fa-place-of-worship"></i></div>
                        </div>
                    </a>
                </div>
                
                <!-- Propagation Personnel (Active Propagation Clergy) -->
                <div class="col-sm-6 col-md-3">
                    <div class="metric-card bg-propagation">
                        <div class="metric-label">弘法人員 (Active)</div>
                        <div class="metric-value"><?= $active_dharma; ?></div>
                        <div class="metric-subtitle">登記弘法人員 Total: <?= $total_dharma; ?> 人</div>
                        <div class="metric-icon"><i class="fa-solid fa-dharmachakra"></i></div>
                    </div>
                </div>
            </div>

            <!-- Detailed Statistics Grid -->
            <div class="row">
                <!-- Left Side: Membership & Chapter Distribution -->
                <div class="col-lg-12">
                    
                    <!-- 1. Membership Status Breakdown (Fully separated status columns for Personal vs Group) -->
                    <div class="dashboard-panel">
                        <div class="dashboard-panel-header">
                            <i class="fa-solid fa-users"></i> 會員狀態分析統計 (Membership Status Breakdown)
                        </div>
                        <div class="dashboard-panel-body">
                            <div class="row">
                                <!-- Personal Members Breakdown -->
                                <div class="col-md-6" style="border-right: 1px solid #e3e6f0;">
                                    <h5 style="font-weight: 700; margin-top: 0; margin-bottom: 15px; color: #1e3c72; font-size: 14px;">個人會員狀態 (Individual Members)</h5>
                                    <div class="table-responsive">
                                        <table class="table table-custom table-condensed">
                                            <thead>
                                                <tr>
                                                    <th class="text-left-aligned">狀態 Status</th>
                                                    <th>數量 Count</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($member_status_map as $s_code => $s_name): 
                                                    $p_cnt = isset($personal_by_status[$s_code]) ? (int)$personal_by_status[$s_code] : 0;
                                                ?>
                                                <tr>
                                                    <td class="text-left-aligned" style="font-weight: 600; font-size:12px;">
                                                        <?= $s_name; ?>
                                                    </td>
                                                    <td style="font-weight: 700; color: #2a5298;"><?= $p_cnt; ?> 人</td>
                                                </tr>
                                                <?php endforeach; ?>
                                                <tr style="background-color: #f8f9fc; font-weight: 700;">
                                                    <td class="text-left-aligned">總人數 Total</td>
                                                    <td style="font-weight: 800; color: #2a5298;"><?= $total_personal; ?> 人</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Group Members Breakdown -->
                                <div class="col-md-6">
                                    <h5 style="font-weight: 700; margin-top: 0; margin-bottom: 15px; color: #11998e; font-size: 14px;">團體會員狀態 (Group Members / Chapters)</h5>
                                    <div class="table-responsive">
                                        <table class="table table-custom table-condensed">
                                            <thead>
                                                <tr>
                                                    <th class="text-left-aligned">狀態 Status</th>
                                                    <th>數量 Count</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($chapter_status_map as $c_code => $c_name): 
                                                    $g_cnt = isset($group_by_status[$c_code]) ? (int)$group_by_status[$c_code] : 0;
                                                ?>
                                                <tr>
                                                    <td class="text-left-aligned" style="font-weight: 600; font-size:12px;">
                                                        <?= $c_name; ?>
                                                    </td>
                                                    <td style="font-weight: 700; color: #11998e;"><?= $g_cnt; ?> 間</td>
                                                </tr>
                                                <?php endforeach; ?>
                                                <tr style="background-color: #f8f9fc; font-weight: 700;">
                                                    <td class="text-left-aligned">總道場數 Total</td>
                                                    <td style="font-weight: 800; color: #11998e;"><?= $total_group; ?> 間</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <!-- 2. Chapter Statistics (Status & State) -->
                    <div class="dashboard-panel">
                        <div class="dashboard-panel-header">
                            <i class="fa-solid fa-place-of-worship"></i> 道場狀態與州屬分佈統計 (Chapter Status & Distribution)
                        </div>
                        <div class="dashboard-panel-body">
                            <div class="row">
                                <!-- Chapters by Status -->
                                <div class="col-md-5" style="border-right: 1px solid #e3e6f0; margin-bottom: 15px;">
                                    <h5 style="font-weight: 700; margin-top: 0; margin-bottom: 15px; color: #5a5c69; font-size: 14px;">登記道場類別狀態 (Chapter Status)</h5>
                                    <div class="table-responsive">
                                        <table class="table table-custom table-condensed">
                                            <thead>
                                                <tr>
                                                    <th class="text-left-aligned">狀態 Status</th>
                                                    <th>數量 Count</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($chapter_status_map as $c_code => $c_name): 
                                                    $c_count = isset($chapters_by_status[$c_code]) ? $chapters_by_status[$c_code] : 0;
                                                ?>
                                                <tr>
                                                    <td class="text-left-aligned" style="font-weight: 600; font-size:12px;">
                                                        <?= $c_name; ?>
                                                    </td>
                                                    <td style="font-weight: 700; color: #e74c3c;"><?= $c_count; ?></td>
                                                </tr>
                                                <?php endforeach; ?>
                                                <tr style="background-color: #f8f9fc; font-weight: 700;">
                                                    <td class="text-left-aligned">登記道場總數 Total</td>
                                                    <td style="font-weight: 800; color: #e74c3c;"><?= $total_chapters; ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Chapters by State -->
                                <div class="col-md-7">
                                    <h5 style="font-weight: 700; margin-top: 0; margin-bottom: 15px; color: #5a5c69; font-size: 14px;">各州屬道場分佈 (States Distribution)</h5>
                                    <div class="state-list-container">
                                        <?php
                                        $max_state_count = 1;
                                        if (isset($stats['chap_state']) && !empty($stats['chap_state'])) {
                                            $max_state_count = (int)$stats['chap_state'][0]['count'];
                                        }
                                        
                                        $state_idx = 0;
                                        if (isset($stats['chap_state'])):
                                            foreach ($stats['chap_state'] as $st):
                                                $state_name = $st['state'] ? $st['state'] : "未指定 (Unassigned)";
                                                $state_count = (int)$st['count'];
                                                $percentage = round(($state_count / $max_state_count) * 100);
                                                
                                                // Pick bar color based on rank
                                                $bar_class = "state-progress-fill";
                                                if ($state_idx < 3) {
                                                    $bar_class .= " state-progress-fill-top"; // Top 3 get deep blue
                                                } else if ($state_idx < 7) {
                                                    $bar_class .= " state-progress-fill-mid"; // Mid ones get teal
                                                }
                                                $state_idx++;
                                        ?>
                                        <div class="state-row">
                                            <div class="state-label-row">
                                                <span><?= $state_name; ?></span>
                                                <span><?= $state_count; ?> 間</span>
                                            </div>
                                            <div class="state-progress-bar">
                                                <div class="<?= $bar_class; ?>" style="width: <?= $percentage; ?>%;"></div>
                                            </div>
                                        </div>
                                        <?php 
                                            endforeach;
                                        endif; 
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Right Side: Dharma Propagation Personnel Matrix (Filtered to SS, JSS, FS, JS, ZJ) -->
                <div class="col-lg-12">
                    <div class="dashboard-panel">
                        <div class="dashboard-panel-header">
                            <i class="fa-solid fa-dharmachakra"></i> 弘法人員職級與狀態分佈矩陣 (Personnel Position Matrix)
                        </div>
                        <div class="dashboard-panel-body" style="padding: 0;">
                            <div class="table-responsive">
                                <table class="table table-custom table-bordered" style="border: none;">
                                    <thead>
                                        <tr>
                                            <th class="text-left-aligned" style="padding-left: 15px; border-left: none; width: 150px;">職級 Position</th>
                                            <?php foreach ($staff_status_map as $s_key => $s_name): ?>
                                            <th style="font-size: 11px; font-weight: 600;"><?= $s_name; ?></th>
                                            <?php endforeach; ?>
                                            <th style="padding-right: 15px; border-right: none; font-weight: 700; background-color: #eaecf4; width: 100px;">總計 Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($pos_list as $p_key => $p_name): ?>
                                        <tr>
                                            <td class="text-left-aligned" style="padding-left: 15px; font-weight: 600; border-left: none;">
                                                <?= $p_name; ?> <span style="font-weight: 300; font-size: 11px; color:#858796;">(<?= $p_key; ?>)</span>
                                            </td>
                                            <?php 
                                            foreach ($staff_status_map as $s_key => $s_name): 
                                                $val = $dharma_matrix[$p_key][$s_key];
                                                $style = ($val > 0) ? 'font-weight: 600; color: #4e73df;' : 'color: #d1d3e2;';
                                            ?>
                                            <td style="<?= $style; ?>"><?= $val ?: '-'; ?></td>
                                            <?php endforeach; ?>
                                            <td style="padding-right: 15px; border-right: none; font-weight: 700; background-color: #f8f9fc; color: #2e59d9;">
                                                <?= $pos_totals[$p_key] ?: '-'; ?>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                        
                                        <!-- Columns Total row -->
                                        <tr style="background-color: #f8f9fc; font-weight: 700;">
                                            <td class="text-left-aligned" style="padding-left: 15px; border-left: none; color: #333333; font-size: 12px;">總計 Column Total</td>
                                            <?php 
                                            foreach ($staff_status_map as $s_key => $s_name): 
                                                $col_tot = $stat_totals[$s_key];
                                                $color = ($s_key === 'Normal') ? 'color: #1cc88a;' : 'color: #5a5c69;';
                                            ?>
                                            <td style="font-weight: 700; <?= $color; ?>"><?= $col_tot ?: '-'; ?></td>
                                            <?php endforeach; ?>
                                            <td style="padding-right: 15px; border-right: none; font-weight: 800; color: #4e73df; font-size: 14px; background-color: #eaecf4;">
                                                <?= $total_dharma_filtered; ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>