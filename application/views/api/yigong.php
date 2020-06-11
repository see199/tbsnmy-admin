
<?php foreach($yigong as $y): ?>
<div class="container" style='page-break-after: always;font-size:16pt;<?= (!file_exists('asset/img/yigong/'.strtolower($y['email']).'.jpg')) ? '/*display:none;*/' : '' ;?>'>
    <div class="row">
        <div class="box">
            <div class="col-lg-10">
                <h1 class='text-center'>大幻化網金剛大法會 - 網站報名義工</h1>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="box">
            <div class="col-lg-10">
                <table class="table table-bordered">
                    <tr><td style='width:25%'></td><td style='width:25%'></td><td style='width:25%'></td><td style='width:25%'></td></tr>
                    <tr>
                        <td colspan=3>
                            姓名：<?= $y['cname'];?> (<?= $y['dname']; ?>)
                            <br />Name: <?= $y['ename']; ?>
                        </td>
                        <td rowspan=4><div style='height:200pt;'>
                            <?= (file_exists('asset/img/yigong/'.strtolower($y['email']).'.jpg')) ? "<img style='height:200pt;' src='".base_url('asset/img/yigong/'.strtolower($y['email']).'.jpg')."' />" : "" ?>
                        </div></td>
                    </tr>
                    <tr><td colspan=3>地址：<?= $y['address']; ?></td></tr>

                    <tr><td>性別：<?= $y['sex']; ?></td><td>DOB: <?= $y['dob']; ?></td><td>職業：<?= $y['job']; ?></td></tr>
                    <tr><td>駕駛執照：<?= $y['drive']; ?></td><td>皈依日期: <?= $y['refdate']; ?></td><td>道場：<?= $y['refchap']; ?></td></tr>
                    <tr><td colspan=2>聯絡電話：<?= $y['contact']; ?></td><td colspan=2>電郵：<?= $y['email']; ?></td></tr>

                    <tr><td>專長：</td>
                    <td colspan=3>
<?= ($y['skill_ary']['s1']) ? "【<i class='glyphicon glyphicon-ok'></i>】" : "【 】" ;?> 基本電腦運用 Basic Computer Operation<br />
<?= ($y['skill_ary']['s2']) ? "【<i class='glyphicon glyphicon-ok'></i>】" : "【 】" ;?> 電腦設計 Digital Design (PS, AI)<br />
<?= ($y['skill_ary']['s3']) ? "【<i class='glyphicon glyphicon-ok'></i>】" : "【 】" ;?> 網絡宣傳 Online Marketing<br />
<?= ($y['skill_ary']['s4']) ? "【<i class='glyphicon glyphicon-ok'></i>】" : "【 】" ;?> 撰寫稿件 Writing Article<br />
<?= ($y['skill_ary']['s5']) ? "【<i class='glyphicon glyphicon-ok'></i>】" : "【 】" ;?> CPR 急救<br />
<?= ($y['skill_ary']['s6']) ? "【<i class='glyphicon glyphicon-ok'></i>】" : "【 】" ;?> 售賣組 Retail<br />
<?= ($y['skill_ary']['s7']) ? "【<i class='glyphicon glyphicon-ok'></i>】" : "【 】" ;?> 接待組 Reception<br />
<?= ($y['skill_ary']['s8']) ? "【<i class='glyphicon glyphicon-ok'></i>】" : "【 】" ;?> 文集推廣組 Books Promotion<br />
                        <?= isset($y['skill_oth']) ? "【<i class='glyphicon glyphicon-ok'></i>】" : "【 】" ;?> 其他： <?= isset($y['skill_oth']) ? join(', ',$y['skill_oth']) : "-"; ?>
                    </td></tr>

                    <tr><td style='width:25%;'>義工覆歷：</td>
                    <td colspan=3>
<div style="width:49%;display:inline-table;"><?= ($y['exp_ary']['s1'])  ? "【<i class='glyphicon glyphicon-ok'></i>】" : "【 】" ;?> 報名組 Registration</div>
<div style="width:49%;display:inline-table;"><?= ($y['exp_ary']['s2'])  ? "【<i class='glyphicon glyphicon-ok'></i>】" : "【 】" ;?> 佈置組 Decoration</div>
<div style="width:49%;display:inline-table;"><?= ($y['exp_ary']['s3'])  ? "【<i class='glyphicon glyphicon-ok'></i>】" : "【 】" ;?> 交通組 Transportation</div>
<div style="width:49%;display:inline-table;"><?= ($y['exp_ary']['s4'])  ? "【<i class='glyphicon glyphicon-ok'></i>】" : "【 】" ;?> 保安組 Security</div>
<div style="width:49%;display:inline-table;"><?= ($y['exp_ary']['s5'])  ? "【<i class='glyphicon glyphicon-ok'></i>】" : "【 】" ;?> 清潔組 Cleaning</div>
<div style="width:49%;display:inline-table;"><?= ($y['exp_ary']['s6'])  ? "【<i class='glyphicon glyphicon-ok'></i>】" : "【 】" ;?> 皈依組 Refuge Registration</div>
<div style="width:49%;display:inline-table;"><?= ($y['exp_ary']['s7'])  ? "【<i class='glyphicon glyphicon-ok'></i>】" : "【 】" ;?> 焚化組 Burning Mantra Paper</div>
<div style="width:49%;display:inline-table;"><?= ($y['exp_ary']['s8'])  ? "【<i class='glyphicon glyphicon-ok'></i>】" : "【 】" ;?> 售賣組 Retail</div>
<div style="width:49%;display:inline-table;"><?= ($y['exp_ary']['s9'])  ? "【<i class='glyphicon glyphicon-ok'></i>】" : "【 】" ;?> 接待組 Reception</div>
<div style="width:49%;display:inline-table;"><?= ($y['exp_ary']['s10']) ? "【<i class='glyphicon glyphicon-ok'></i>】" : "【 】" ;?> 文集推廣組 Books Promotion</div>
                        <?= isset($y['exp_oth']) ? "【<i class='glyphicon glyphicon-ok'></i>】" : "【 】" ;?> 其他： <?= isset($y['exp_oth']) ? join(', ',$y['exp_oth']) : "-"; ?>
                    </td></tr>

                    <tr><td colspan=3>特別安排：<?= ($y['req']) ? $y['req']:"-"; ?></td><td>T-Shirt：<?= $y['tshirt']; ?></td></tr>
                    <tr><td colspan=4>備註：<div style='height:300px;width:100%'></div></td></tr>

                </table>
            </div>
        </div>
    </div>





        
</div>
<?php endforeach; ?>