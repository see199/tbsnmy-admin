<?php

function get_first_day($day_number=1, $month=false, $year=false){
	$month  = ($month === false) ? strftime("%m"): $month;
    $year   = ($year === false) ? strftime("%Y"): $year;

    $first_day = 1 + ((7+$day_number - strftime("%w", @mktime(0,0,0,$month, 1, $year)))%7);

    return @mktime(0,0,0,$month, $first_day, $year);
}

  // this will output the first saturday of january 2007 (Sat 06-01-2007)
  $start_date = strftime("%Y-%m-%d", get_first_day(7, date('m')-1, date('Y')));
  $end_date   = strftime("%Y-%m-%d", get_first_day(6, date('m'), date('Y')));

  $start_date_name = strftime("%m%d", get_first_day(7, date('m')-1, date('Y')));
  $end_date_name   = strftime("%m%d", get_first_day(6, date('m'), date('Y')));


  $url = "https://ch.tbsn.org/control/prayclass/exceloutput.html?pid=3&start_day=".$start_date."&end_day=".$end_date."&filetype=xls&datatype=";

  $url_100k = $url."10";
  $url_1mil = $url."100";
  $m = date('m')-1;

$mail_subject = "「" . $m . "月一生一咒」名單";
$mail_body = "尊敬的上師/法師您好，

附上".$m."月的「一生一咒」兩份名單，麻煩幫忙列印呈給師尊加持。

感恩！
文宣處合十";

?>
<h3><u>DATE: [<?=$start_date;?>] - [<?=$end_date;?>]</u></h3>
<p><a href="https://ch.tbsn.org/control/">Login</a>
 | <a href="<?=$url_100k;?>">100K</a>
 | <a href="<?=$url_1mil;?>">1MIL</a></p>
 <p><input value="一生一咒-<?=$start_date_name;?>-<?=$end_date_name;?>-100k" onfocus="this.select();"></p>
 <p><input value="一生一咒-<?=$start_date_name;?>-<?=$end_date_name;?>-1mil" onfocus="this.select();"></p>
 <p><input type="text" value="「<?=$mail_subject;?>月一生一咒」名單" onfocus="this.select();"></p>
 <p><textarea cols="100" rows="7" onfocus="this.select();"><?=$mail_body;?></textarea></p>
<a target="email" href="mailto:info@tbs-rainbow.org?subject=<?= $mail_subject;?>&body=<?= urlencode($mail_body);?>">Send Email</a>

