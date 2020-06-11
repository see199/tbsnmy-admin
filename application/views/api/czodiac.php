<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style type="text/css">
table {
	border-width: 2px;
	border-spacing: 2px;
	border-style: solid;
	border-color: gray;
	border-collapse: collapse;
	background-color: white;
}
table th {
	border-width: 1px;
	padding: 2px;
	border-style: dotted;
	border-color: gray;
	background-color: rgb(250, 240, 230);
	-moz-border-radius: ;
}
table td {
	border-width: 1px;
	padding: 2px;
	border-style: dotted;
	border-color: gray;
	background-color: rgb(250, 240, 230);
	text-align: center;
	-moz-border-radius: ;
}
</style>


<?php

$zodiac = array('鼠','牛','虎','兔','龍','蛇','馬','羊','猴','雞','狗','豬');
$b = array('子','丑','寅','卯','辰','巳','午','未','申','酉','戌','亥');
$a = array('甲','乙','丙','丁','戊','己','庚','辛','壬','癸');

$a_key = 0;
$b_key = 0;

do{
	
	
	$name[] = $a[$a_key].$b[$b_key];
	
	$a_key++;
	$b_key++;
	
	$a_key=$a_key%sizeof($a);
	$b_key=$b_key%sizeof($b);
	
}while($name[0] != end($name) || sizeof($name) == 1);

$yr = (int)date('Y');
//for($a=$yr-100;$a<=$yr;$a++){
//	$v = ($a-4)%60;
//	$age = $yr-$a;
//	echo "<br>$a ($age): ".$name[$v];
//}
//

?>
<p align='center'>
<b><u><? echo date('Y').' - '.$name[($yr-4)%60].' ('.$zodiac[($yr-4)%12].') 年 - 陽曆生肖歲數'; ?></u></b>
<table><tr><td>
	<table border=1>
		<tr><th>年</th><th>生肖</th><th>年齡</th></tr>
		<?php for($a=$yr-100;$a<$yr-75;$a++){
				$v = ($a-4)%60;
				$z = ($a-4)%12;
				$age = $yr - $a;
				echo '<tr><td>'.$name[$v].' '.$a.'</td><td>'.$zodiac[$z].'</td><td>'.$age.'</td></tr>';
			}
		?>
	</table>
</td><td>
	<table border=1>
		<tr><th>年</th><th>生肖</th><th>年齡</th></tr>
		<?php for($a=$yr-75;$a<$yr-50;$a++){
				$v = ($a-4)%60;
				$z = ($a-4)%12;
				$age = $yr - $a;
				echo '<tr><td>'.$name[$v].' '.$a.'</td><td>'.$zodiac[$z].'</td><td>'.$age.'</td></tr>';
			}
		?>
	</table>
</td><td>
	<table border=1>
		<tr><th>年</th><th>生肖</th><th>年齡</th></tr>
		<?php for($a=$yr-50;$a<$yr-25;$a++){
				$v = ($a-4)%60;
				$z = ($a-4)%12;
				$age = $yr - $a;
				echo '<tr><td>'.$name[$v].' '.$a.'</td><td>'.$zodiac[$z].'</td><td>'.$age.'</td></tr>';
			}
		?>
	</table>
</td><td>
	<table border=1>
		<tr><th>年</th><th>生肖</th><th>年齡</th></tr>
		<?php for($a=$yr-25;$a<$yr;$a++){
				$v = ($a-4)%60;
				$z = ($a-4)%12;
				$age = $yr - $a;
				echo '<tr><td>'.$name[$v].' '.$a.'</td><td>'.$zodiac[$z].'</td><td>'.$age.'</td></tr>';
			}
		?>
	</table>
</td></tr></table>
</p>