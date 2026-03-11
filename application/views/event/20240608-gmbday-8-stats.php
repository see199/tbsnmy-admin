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
    if(isset($event_type_list)){
        foreach($event_type_list as $i){
            @$event_counter[$i] += 1;

            if($v['chapter_name']) $stats_by_time[$i]['group'][$v['chapter_country'].$v['chapter_name']] = $v['chapter_country'].$v['chapter_name'];
            if($v['master_name']) $stats_by_time[$i]['master'][$v['master_name']] = $v['master_name'];
            if($v['join_personnel']) $stats_by_time[$i]['join'][] = $v['join_personnel'];
        }
    }
}

// Calculation Ends
// Slot | TW Time | SEA Time
$event_timelist = array(
    array( '1',"2024-<br/>06-08<br/>10AM","2024-<br/>06-07<br/>07PM"),
    array( '2',"2024-<br/>06-08<br/>02PM","2024-<br/>06-07<br/>11PM"),
    array( '3',"2024-<br/>06-08<br/>05PM","2024-<br/>06-08<br/>02AM"),
    array( '4',"2024-<br/>06-08<br/>08PM","2024-<br/>06-08<br/>05AM"),
    array( '5',"2024-<br/>06-09<br/>01AM","2024-<br/>06-08<br/>10AM"),
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
                                        <td><b>道場</b></td>
                                        <td><b>主壇</b></td>
                                        <td><b>護壇</b></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php for($i=1;$i<=5;$i++): ?>
                                    <tr>
                                        <?php if(isset($stats_by_time[$i]['group'])) ksort($stats_by_time[$i]['group']); ?>
                                        <td><?=$i;?></td>
                                        <td nowrap><?= $event_timelist[$i-1][1]; ?></td>
                                        <td nowrap><?= $event_timelist[$i-1][2]; ?></td>
                                        <td><?= isset($stats_by_time[$i]['group']) ? sizeof($stats_by_time[$i]['group']) : "0" ?></td>
                                        <td><?= isset($stats_by_time[$i]['group']) ? join(", ", $stats_by_time[$i]['group']) : ""; ?></td>
                                        <td><?= isset($stats_by_time[$i]['master']) ? join(", ",$stats_by_time[$i]['master']) : ""; ?></td>
                                        <td><?= isset($stats_by_time[$i]['join']) ? join(", ", $stats_by_time[$i]['join']) : ""; ?></td>
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

                                        <?php for($i=1;$i<=5;$i++): ?>
                                        <td>
                                            <b>第<?=$i;?>場</b><br/>[<?= isset($stats_by_time[$i]['group']) ? sizeof($stats_by_time[$i]['group']) : "0" ?>]
                                        </td>
                                        <?php endfor;?>

                                        <td><b>主壇</b></td>
                                        <td><b>護壇</b></td>
                                        <td><b>登記日期</b></td>
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

                                        <?php for($i=1;$i<=5;$i++): ?>
                                        <td align="center" nowrap><?= (@$event_type_list[$i] == $i)? "&#x2714;":""; ?></td>
                                        <?php endfor;?>

                                        <td><?= $data['master_name'];?></td>
                                        <td><?= $data['join_personnel'];?></td>
                                        <td><small><?= $data['create_date'];?></small></td>
                                    </tr>
                                    <?php endforeach;?>
                                    <?php endforeach;?>
                                    <?php endforeach;?>

                                </tbody>
                            </table>
                        </div>

                        <div class='row'>&nbsp;</div>

                        <hr />
                        <h3>電郵列表</h3>


                        <div class='row text-left'>
                            <table id="example" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                                <thead>
                                    <tr class="info">
                                        <td><b>No</b></td>
                                        <td><b>登記日期</b></td>
                                        <td><b>國家</b></td>
                                        <td><b>道場</b></td>
                                        <td><b>第X場</b></td>
                                        <td><b>道場負責人</b></td>
                                        <td><b>道場電郵</b></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($stats as $k => $data): ?>
                                    <?php if(!($data['event_type'])) $data['event_type'] = "{}"; ?>
                                    <tr>
                                        <td nowrap><?= $k+1;?></td>
                                        <td><?= $data['create_date'];?></td>
                                        <td nowrap><?= $data['chapter_country'];?></td>
                                        <td nowrap><?= $data['chapter_name'];?></td>
                                        <td nowrap><?= implode(",",json_decode($data['event_type'],1)); ?></td>
                                        <td nowrap><?= $data['chapter_pic'];?></td>
                                        <td nowrap><?= $data['chapter_email'];?></td>
                                    </tr>
                                    <?php endforeach;?>

                                </tbody>
                            </table>
                        </div>
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