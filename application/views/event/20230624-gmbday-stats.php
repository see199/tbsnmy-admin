<?php

// Stats Calculation
$stats_by_master = array();
$chapter_joined = array();
$total_event = 0;
$event_counter = array(0,0,0,0);
//print_pre($stats);
foreach($stats as $k => $v){
    $stats_sorted[$v['chapter_country']][$v['chapter_name']][$v['event_type']] = $v;
    $chapter_joined[$v['chapter_name']] = $v['chapter_name'];
    @$event_counter[$v['event_type']] += 1; 
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
                        </div>
                        <div class='row'>&nbsp;</div>


                        <div class='row text-left'>
                            <table id="example" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                                <thead>
                                    <tr class="info">
                                        <td><b>No</b></td>
                                        <td><b>國家</b></td>
                                        <td><b>道場</b></td>
                                        <td align="center"><b>第1場</b><br/><small>大力金剛</small><br/>[<?=$event_counter['1'];?>]</td>
                                        <td align="center"><b>第2場</b><br/><small>瑤池金母</small><br/>[<?=$event_counter['2'];?>]</td>
                                        <td align="center"><b>第3場</b><br/><small>阿彌陀佛</small><br/>[<?=$event_counter['3'];?>]</td>
                                        <td><b>主壇者</b></td>
                                        <td><b>護壇者</b></td>
                                        <td><b>登記日期</b></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $k=0;?>
                                    <?php foreach($stats_sorted as $country => $v): ?>
                                    <?php foreach($v as $chapter_name => $v2): ?>
                                    <?php foreach($v2 as $event_type => $data): $k++;?>
                                    <tr>
                                        <td nowrap><?= $k;?></td>
                                        <td nowrap><?= $country;?></td>
                                        <td nowrap><?= $chapter_name;?></td>
                                        <td align="center" nowrap><?= ($event_type == 1)? "&#x2714;":""; ?></td>
                                        <td align="center" nowrap><?= ($event_type == 2)? "&#x2714;":""; ?></td>
                                        <td align="center" nowrap><?= ($event_type == 3)? "&#x2714;":""; ?></td>
                                        <td nowrap><?= $data['master_name'];?></td>
                                        <td><?= $data['join_personnel'];?></td>
                                        <td nowrap><small><?= $data['create_date'];?></small></td>
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
                                    <tr>
                                        <td nowrap><?= $k+1;?></td>
                                        <td><?= $data['create_date'];?></td>
                                        <td nowrap><?= $data['chapter_country'];?></td>
                                        <td nowrap><?= $data['chapter_name'];?></td>
                                        <td nowrap><?= $data['event_type']; ?></td>
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