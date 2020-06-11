<meta charset="UTF-8">
<table border=0 width="100%" style='max-width:580px;'>
    <tr><td>
        <div style='font-weight:bold;margin:20px 0;'>馬密教總會與馬來西亞真佛宗各道場<?= $year; ?>年<?= $month; ?>月份活動預告</div>
        <div>各位同門及善信大德：
            <br />
            <br />以下傳單為
            <br />馬密教總會與馬來西亞真佛宗各道場<?= $year; ?>年<?= $month; ?>月份活動預告
            <br />
            <br />歡迎各位同門及善信大德踴躍參與護持共沾法喜！也歡迎轉發！
            <br />謝謝！
            <br />
            <br />馬密教總會  文宣部 合十
        </div>
    </td></tr>
    <tr><td>&nbsp;</td></tr>

    <tr><td style='border-top:1px solid #AAA;text-align:center;'>
        <div style='text-align:left;font-size:1.5em;color:#333;font-weight:bold;margin:10px 0;'>馬來西亞真佛宗密教總會般若雷藏寺活動</div>
        <img src='<?= $boyeh['cal']; ?>' style="width:95%; margin:5px auto;" />
        <?php foreach($boyeh['event'] as $img): ?>
        <br />
        <img src='<?= $img; ?>' style="width:95%; margin:5px auto;" / >
        <?php endforeach; ?>
    </td></tr>
    <tr><td>&nbsp;</td></tr>

    <tr><td style='border-top:1px solid #AAA;text-align:center;'>
        <div style='text-align:left;font-size:1.5em;color:#333;font-weight:bold;margin:10px 0;'>大馬各道場法務活動</div>
        <table style="width:95%; background-color:#CCC;margin:auto;">
            <tr>
            <?php $k = 0; foreach($event['with_img'] as $e): ?>
            <?php if($k%2 == 0 && $k != 0) echo "</tr><tr>"; ?>
            <td style="width:50%;text-align:center;vertical-align:top;">
                <table style='margin:5px;width:95%;'>
                    <tr><td style='background-color:#F2F2F2;text-align:center;'>
                        <a href="<?= $e['event_url']; ?>" target="event">
                        <img src='<?= $e['resize_img']; ?>' style='width:90%;margin:auto;' />
                        </a>
                    </td></tr>
                    <tr><td style='background-color:#CCC;text-align:center;'>
                        <a style='color:#000;font-weight:bold;' href="<?= $e['chapter_domain']; ?>" target="chapter">主辦單位：<?= $e['chapter_name']; ?></a>
                    </td></tr>
                </table>
            </td>
            <?php $k++; endforeach; ?>
            </tr>
        </table>
        <br /><br />
        <div style='text-align:left;font-size:1.5em;color:#333;font-weight:bold;margin:10px 0;'>大馬各道場法務活動列表</div>
        <br />
        <table style='border:1px solid #999; width:95%;margin:auto;' cellspacing=0>
            <tr>
                <th style='padding:5px;border:1px solid #999; background-color: #CCC;'>活動</th>
                <th style='padding:5px;border:1px solid #999; background-color: #CCC;'>弘法人員</th>
            </tr>
            <?php foreach($event['all_event'] as $k => $e): ?>
            <tr style="background-color:<?= ($k%2 == 0) ? '#FFFAFA' : 'F2F2F2' ; ?>">
                <td style='border:1px solid #999;font-size:0.8em;text-align:left;padding:5px;'>
                    [ <?= substr($e['start_date'],0,10); ?> ] - <a style='color:#000;' href="<?= $e['chapter_domain']; ?>" target="chapter"><?= $e['chapter_name']; ?></a>
                    主辦<br />&nbsp;&nbsp;&nbsp;&nbsp;<a style='color:#000;' href="<?= $e['event_url']; ?>" target="event"><?= $e['name']; ?></a></td>
                <td style='border:1px solid #999;font-size:0.8em;text-align:center;padding:5px;'><?= preg_replace('/上師及/','上師<br />及',$e['master_name']); ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </td></tr>
    <tr><td style='text-align:right;font-size:0.8em;padding:10px 25px;'>完整版可瀏覽：<a style='color:#000;' href='http://www.tbsn.my/活動消息/上師弘法行程表.html'>http://www.tbsn.my/活動消息/上師弘法行程表.html</a></td></tr>
    <tr><td>&nbsp;</td></tr>

    <tr><td style='border-top:1px solid #AAA;text-align:center;'>
        <div style='text-align:left;font-size:1.5em;color:#333;font-weight:bold;margin:10px 0;'>真佛報大馬增版<?= $tbsnews_date; ?></div>
        <div style='text-align:left;font-size:1em;color:#333;margin:10px;'>歡迎大家下載瀏覽本月的真佛報大馬增版</div>
        <table style='border:1px solid #999; width:95%;margin:auto;' cellspacing=0>
        <?php $k = 0; foreach($tbsnews as $issue => $t): $k++; ?>
        <tr><?php if($k == 1): ?><td style='border:1px solid #999;text-align:center;width:35%;' rowspan="<?= sizeof($tbsnews); ?>"><img src='http://www.tbsn.my/images/tbnews-malaysia.jpg' style='width:100%;max-width:250px;' /></td><?php endif; ?>
            <td style='border:1px solid #999;padding:5px;text-align:center;background-color:<?= ($k%2 == 0) ? '#FFFAFA' : 'F2F2F2' ; ?>'><a href="<?= $t['link'] ?>" target="tbsnews" style='color:#000;font-weight:bold;' >第 <?= $issue;?> 期 - 出版於 <?= $t['date']; ?></a></td></tr>
        <?php endforeach; ?>
        </table>
    </td></tr>
    <tr><td>&nbsp;</td></tr>


</table>