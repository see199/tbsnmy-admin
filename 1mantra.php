<?php

  // Days calculator
  $day01_timestamp = strtotime('first day of last month');
  $day31_timestamp = strtotime('last day of last month');
  $start_date      = date('Y-m-d', $day01_timestamp);
  $end_date        = date('Y-m-d', $day31_timestamp);
  $start_date_name = date('md', $day01_timestamp);
  $end_date_name   = date('md', $day31_timestamp);


  $url = "https://ch.tbsn.org/control/prayclass/exceloutput.html?pid=3&start_day=".$start_date."&end_day=".$end_date."&filetype=xls&datatype=";

  $url_100k = $url."10";
  $url_1mil = $url."100";
  $m = date('m')-1;

$mail_subject = "「{$m}月一生一咒」名單";
$mail_body = "尊敬的上師/法師您好，

您好！文宣處附上{$m}月的「一生一咒」持誦名單，敬請打印後請師尊在護摩法會上加持。

謝謝！

敬祝法務順利
宗委會文宣處合十";

?>
<h3><u>DATE: [<?=$start_date;?>] - [<?=$end_date;?>]</u></h3>
<p><a href="https://ch.tbsn.org/control/">Login</a>
 | <a href="<?=$url_100k;?>">100K</a>
 | <a href="<?=$url_1mil;?>">1MIL</a></p>
 <p><input value="一生一咒-<?=$start_date_name;?>-<?=$end_date_name;?>-100k" onfocus="this.select();"></p>
 <p><input value="一生一咒-<?=$start_date_name;?>-<?=$end_date_name;?>-1mil" onfocus="this.select();"></p>
 <p><input type="text" value="<?=$mail_subject;?>" onfocus="this.select();"></p>
 <p><textarea cols="100" rows="10" onfocus="this.select();"><?=$mail_body;?></textarea></p>
<a target="email" href="mailto:info@tbs-rainbow.org?subject=<?= $mail_subject;?>&body=<?= urlencode($mail_body);?>">Send Email</a>

