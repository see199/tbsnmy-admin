<?php

// Stats Calculation
$stats_by_master = array();
$chapter_joined = array();
$total_event = 0;
$event_counter = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
$stats_by_time = array();
//print_pre($stats);
foreach($stats as $k => $v){
    $stats_sorted[$v['chapter_country']][$v['chapter_name']][$v['event_type']] = $v;
    $chapter_joined[$v['chapter_name']] = $v['chapter_name'];
    $event_type_list = json_decode($v['event_type'],1);
    foreach($event_type_list as $i){
        @$event_counter[$i] += 1;

        if($v['chapter_name']) $stats_by_time[$i]['group'][$v['chapter_country'].$v['chapter_name']] = $v['chapter_country'].$v['chapter_name'];
        if($v['master_name']) $stats_by_time[$i]['master'][$v['master_name']] = $v['master_name'];
        if($v['join_personnel']) $stats_by_time[$i]['join'][] = $v['join_personnel'];
    }
}
// Calculation Ends
// Slot | TW Time | SEA Time
$event_timelist = array(
    array( '1',"2023-<br/>12-31<br/>08PM","2023-<br/>12-31<br/>04AM"),
    array( '2',"2023-<br/>12-31<br/>09PM","2023-<br/>12-31<br/>05AM"),
    array( '3',"2023-<br/>12-31<br/>10PM","2023-<br/>12-31<br/>06AM"),
    array( '4',"2023-<br/>12-31<br/>11PM","2023-<br/>12-31<br/>07AM"),
    array( '5',"2024-<br/>01-01<br/>12AM","2023-<br/>12-31<br/>08AM"),
    array( '6',"2024-<br/>01-01<br/>01AM","2023-<br/>12-31<br/>09AM"),
    array( '7',"2024-<br/>01-01<br/>02AM","2023-<br/>12-31<br/>10AM"),
    array( '8',"2024-<br/>01-01<br/>03AM","2023-<br/>12-31<br/>11AM"),
    array( '9',"2024-<br/>01-01<br/>04AM","2023-<br/>12-31<br/>12PM"),
    array('10',"2024-<br/>01-01<br/>05AM","2023-<br/>12-31<br/>01PM"),
    array('11',"2024-<br/>01-01<br/>06AM","2023-<br/>12-31<br/>02PM"),
    array('12',"2024-<br/>01-01<br/>07AM","2023-<br/>12-31<br/>03PM"),
    array('13',"2024-<br/>01-01<br/>08AM","2023-<br/>12-31<br/>04PM"),
    array('14',"2024-<br/>01-01<br/>09AM","2023-<br/>12-31<br/>05PM"),
    array('15',"2024-<br/>01-01<br/>10AM","2023-<br/>12-31<br/>06PM"),
    array('16',"2024-<br/>01-01<br/>11AM","2023-<br/>12-31<br/>07PM"),
    array('17',"2024-<br/>01-01<br/>12PM","2023-<br/>12-31<br/>08PM"),
    array('18',"2024-<br/>01-01<br/>01PM","2023-<br/>12-31<br/>09PM"),
    array('19',"2024-<br/>01-01<br/>02PM","2023-<br/>12-31<br/>10PM"),
    array('20',"2024-<br/>01-01<br/>03PM","2023-<br/>12-31<br/>11PM"),
    array('21',"2024-<br/>01-01<br/>04PM","2024-<br/>01-01<br/>12AM"),
    array('22',"2024-<br/>01-01<br/>05PM","2024-<br/>01-01<br/>01AM"),
    array('23',"2024-<br/>01-01<br/>06PM","2024-<br/>01-01<br/>02AM"),
    array('24',"2024-<br/>01-01<br/>07PM","2024-<br/>01-01<br/>03AM"),
);

?>

<!DOCTYPE html>
<html lang="zh-tw">

<head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?= $event['title'];?></title>

        <!-- Bootstrap Core CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">

        <!-- Custom CSS -->
        <link href="<?= base_url(); ?>assets/css/sb-admin-2.css" rel="stylesheet">

        <!-- Custom Fonts -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->

        <!-- jQuery -->
        <script src="<?= base_url(); ?>assets/js/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
        <script type="text/javascript" language="javascript" src="<?= base_url(); ?>assets/js/jquery.js"></script>
        <script src="//code.jquery.com/jquery-1.10.2.js"></script>
        <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

        <style>
            body {
                background-color: #EEEEEE;
                font-size: large;
            }
        </style>

    </head>

<body>

    <div class="col-lg-10 col-lg-offset-1">
        <br />
        <div class="panel panel-default drop-shadow">
            <div class="panel-body">
                <div class='row text-center'>
                    <div class='col-lg-10 col-lg-offset-1'>

                        <div class='row'>
                            <h2 class='text-center' style='color:#600'><?=str_replace("\r\n", "<br>", $event['name']);?> - 統計</h2>
                        </div>

                        <hr />

                        <div class='row'>
                            登記道場總數：<?= count($chapter_joined);?>
                        </div>
                        <div class='row'>&nbsp;</div>

                        <div class='row text-left'>
                            <table id="example" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                                <thead>
                                    <tr class="info">
                                        <td><b>時間段</b></td>
                                        <td><b>台灣時間</b></td>
                                        <td><b>西雅圖時間</b></td>
                                        <td><b>參與道場</b></td>
                                        <td style='min-width:300px;'><b>道場</b></td>
                                        <td style='min-width:300px;'><b>參與弘法人員</b></td>
                                        <td style='min-width:300px;'><b>弘法人員（個人登入）</b></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php for($i=1;$i<=24;$i++): ?>
                                    <tr <?= ($i >= 12 && $i <= 14)? "style='background:#FAA'" : "" ?>>
                                        <?php if(isset($stats_by_time[$i]['group'])) ksort($stats_by_time[$i]['group']); ?>
                                        <td><?=$i;?></td>
                                        <td ><?= $event_timelist[$i-1][1]; ?></td>
                                        <td ><?= $event_timelist[$i-1][2]; ?></td>
                                        <td><?= isset($stats_by_time[$i]['group']) ? sizeof($stats_by_time[$i]['group']) : "0" ?></td>
                                        <td><?= isset($stats_by_time[$i]['group']) ? join(", ", $stats_by_time[$i]['group']) : ""; ?></td>
                                        <td><?= isset($stats_by_time[$i]['join']) ? join(", ", $stats_by_time[$i]['join']) : ""; ?></td>
                                        <td><?= isset($stats_by_time[$i]['master']) ? join(", ",$stats_by_time[$i]['master']) : ""; ?></td>
                                    </tr>
                                    <?php endfor; ?>

                                </tbody>
                            </table>
                        </div>

                        <div class='row'>&nbsp;</div>


                        <div class='row text-left'>
                            <table id="example" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                                <thead>
                                    <tr class="info">
                                        <td><b>No</b></td>
                                        <td><b>國家</b></td>
                                        <td><b>道場</b></td>

                                        <?php for($i=1;$i<=24;$i++): ?>
                                        <td><?php if($i >= 12 && $i <= 14):?><font color="A00"><?php endif; ?>
                                            <b>第<?=$i;?>場</b><br/>[<?= isset($stats_by_time[$i]['group']) ? sizeof($stats_by_time[$i]['group']) : "0" ?>]
                                        </td>
                                        <?php endfor;?>

                                        <td><b>參與弘法人員</b></td>
                                        <td><b>弘法人員（個人登入）</b></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $k=0;?>
                                    <?php if(isset($stats_sorted))foreach($stats_sorted as $country => $v): ?>
                                    <?php foreach($v as $chapter_name => $v2): ?>
                                    <?php foreach($v2 as $event_type => $data): $k++;?>
                                    <?php $event_type_list = (json_decode($event_type,1));?>
                                    <tr>
                                        <td nowrap><?= $k;?></td>
                                        <td nowrap><?= $country;?></td>
                                        <td nowrap><?= $chapter_name;?></td>

                                        <?php for($i=1;$i<=24;$i++): ?>
                                        <td align="center" nowrap><?= (@$event_type_list[$i] == $i)? "&#x2714;":""; ?></td>
                                        <?php endfor;?>

                                        <td style='min-width:300px;'><?= $data['join_personnel'];?></td>
                                        <td style='min-width:300px;'><?= $data['master_name'];?></td>
                                    </tr>
                                    <?php endforeach;?>
                                    <?php endforeach;?>
                                    <?php endforeach;?>

                                </tbody>
                            </table>
                        </div>

                        <div class='row'>&nbsp;</div>


                        
                    </div>

                    <div class="col-lg-10 col-lg-offset-1">

                    </div>
                </div>
            </div>
        </div>
        <div class='row'>&nbsp;</div>
    </div>

</body>
</html>