<?php

// Stats Calculation
$stats_by_country = array();
$chapter_joined = array();
$total_event = 0;
//print_pre($stats);
foreach($stats as $k => $v){
    $txt = ($v['event_date'] == '0000-00-00') ? '' : $v['event_date'];
    $stats_by_country[$v['chapter_country']][$v['chapter_name']][] = $v['event_counter'].'壇 '.$txt.": ".$v['master_name']."  <small>(登記：".$v['create_date']."</small>)";
    $chapter_joined[$v['chapter_name']] = $v['chapter_name'];
    $total_event += $v['event_counter'];
}
// Calculation Ends
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
                            <br />活動登記次數：<?= $total_event;?>
                        </div>
                        <div class='row'>&nbsp;</div>

                        <div class='row text-left'>
                            <table id="example" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                                <thead>
                                    <tr class="warning text-center"><td colspan=4><b>國家道場統計</b></td></tr>
                                    <tr class="warning">
                                        <td><b>國家</b></td>
                                        <td><b>道場總數</b></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($stats_by_country as $country => $v): ?>
                                    <tr>
                                        <td><?= $country;?></td>
                                        <td><?= count($v);?></td>
                                    </tr>
                                    <?php endforeach;?>
                                </tbody>
                            </table>
                        </div>
                        <div class='row'>&nbsp;</div>


                        <div class='row text-left'>
                            <table id="example" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                                <thead>
                                    <tr class="info text-center"><td colspan=4><b>道場登記列表</b></td></tr>
                                    <tr class="info">
                                        <td><b>No</b></td>
                                        <td><b>國家</b></td>
                                        <td><b>道場</b></td>
                                        <td><b>日期:帶領人員</b></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $k=0;?>
                                    <?php foreach($stats_by_country as $country => $v): ?>
                                    <?php foreach($v as $chapter_name => $events): $k++;?>
                                    <tr>
                                        <td><?= $k;?></td>
                                        <td><?= $country;?></td>
                                        <td><?= $chapter_name;?></td>
                                        <td><?= implode("<br />", $events);?></td>
                                    </tr>
                                    <?php endforeach;?>
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