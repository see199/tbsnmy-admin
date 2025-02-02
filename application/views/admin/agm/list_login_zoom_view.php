<?php unset($members['G']['chapter']['0']); //Remove Chapter Without chapter_id ?>
<div id="page-wrapper">

    <div class="row">
        <div class="box">
            <div class="col-lg-8 col-lg-offset-2 text-center form-inline">
                <h1>AGM 簽到記錄統計</h1>
                <a href="<?=base_url('agm/stats');?>">簽到即時更新</a><br /><br />
            </div>
        </div>
    </div>

    <div class="row">
        <div class="box">
            <div class="col-lg-4 col-lg-offset-4">
                <table id="example" class="display table table-striped table-bordered" cellspacing="0" width="100%">
                    <tr><td>團體會員出席：</td><td><?= $members['G']['total']['chuxi']; ?> (現場：<?= $members['G']['total']['xianchang']; ?>)</td></tr>
                    <tr><td>個人會員出席：</td><td><?= sizeof($members['P']['chuxi']); ?> (現場：<?= $members['P']['total']['xianchang']; ?>)</td></tr>
                    <tr><th>總出席人數：(合法人數 > 80人)  </th><th><?= $members['G']['total']['chuxi'] + sizeof($members['P']['chuxi']); ?> (現場：<?= $members['G']['total']['xianchang'] + $members['P']['total']['xianchang']; ?>)</th></tr>
                    <tr><td>總出席道場：  </td><td><?= sizeof($members['G']['chapter']); ?></td></tr>
                    <tr><td>列席：       </td><td><?= $members['G']['total']['liexi']; ?></td></tr>
                </table>
            </div><div class="col-lg-10 col-lg-offset-1">
                <table width="100%"><tr>
                    <td width=50% valign=top style="padding:5px">出席<table id="example" class="display table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead><tr class='info'><td>道場</td><td>代表</td><td>職位</td><td>NRIC</td><td>聯絡號碼</td><td>簽到日期</td></tr></thead>
                        <tbody><?php foreach($members['G']['chuxi'] as $chapter_id => $list):foreach($list as $r): ?>
                        <tr <?= ($r['zoom_link'] == '現場出席') ? "class='warning'" : ""; ?>><td><?= $r['first_name']; ?></td><td><?= $r['last_name']; ?></td><td><?= $r['position']; ?></td><td><?= $r['nric']; ?></td><td><?= $r['phone_mobile']; ?></td><td><?= $r['login_time']; ?></td></tr>
                        <?php endforeach;endforeach; ?></tbody>
                    </table></td>
                    
                    <td width=50% valign=top style="padding:5px">個人出席<table id="example" class="display table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead><tr class='info'><td>姓名</td><td>NRIC</td><td>聯絡號碼</td><td>簽到日期</td></tr></thead>
                        <tbody><?php foreach($members['P']['chuxi'] as $r): ?>
                         <tr <?= ($r['zoom_link'] == '現場出席') ? "class='warning'" : ""; ?>><td><?= $r['membership_id']; ?>-<?= $r['name_chinese']; ?></td><td><?= $r['nric']; ?></td><td><?= $r['phone_mobile']; ?></td><td><?= $r['login_time']; ?></td></tr>
                        <?php endforeach; ?></tbody>
                    </table>
                    <br />列席<table id="example" class="display table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead><tr class='info'><td>道場</td><td>代表</td><td>職位</td><td>NRIC</td><td>聯絡號碼</td><td>簽到日期</td></tr></thead>
                        <tbody><?php foreach($members['G']['liexi'] as $chapter_id => $list):foreach($list as $r): ?>
                        <tr><td><?= $r['first_name']; ?></td><td><?= $r['last_name']; ?></td><td><?= $r['position']; ?></td><td><?= $r['nric']; ?></td><td><?= $r['phone_mobile']; ?></td><td><?= $r['login_time']; ?></td></tr>
                        <?php endforeach;endforeach; ?></tbody>
                    </table></td>
                </tr></table>
            </div>
        </div>
    </div>
    
    <!-- /.row -->
</div>