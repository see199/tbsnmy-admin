<!DOCTYPE html>
<html lang="zh-tw">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>馬密總會員大會登記表</title>
    <link rel="shortcut icon" href="<?php echo base_url(); ?>assets/images/icon/tab-icon.png" type="image/x-icon">

    <!-- Bootstrap -->
    <link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- jQuery -->
    <script src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>
        html {
            height: 100%;
        }
        body {
            background-color: #E6E6E6;
            background-image: url(<?php echo base_url(); ?>asset/img/lotus-2528454_1920.jpg);
            background-size: cover;
        }
        .vertical-center {
            min-height: 100%;  /* Fallback for browsers do NOT support vh unit */
            min-height: 95vh; /* These two lines are counted as one :-)       */
            display: flex;
            align-items: center;
        }
        .drop-shadow {
            -webkit-box-shadow: 0 0 5px 2px rgba(0, 0, 0, .5);
            box-shadow: 0 0 8px 3px rgba(0, 0, 0, .5);
        }
        .panel.drop-shadow {
            padding-left:0;
            padding-right:0;
        }
        .myTable>tbody>tr>td {
            text-align: left;
        }

        .myTable>thead {
            background-color: #CDE;
        }
    </style>
</head>

<body>

    <div class="col-xs-12 col-sm-8 col-md-6 col-lg-6 col-sm-offset-2 col-md-offset-3 col-lg-offset-3">
        <br />
        <div class="panel panel-default drop-shadow">
            <div class="panel-body">
                <div class='row text-center'>
                    <div class='col-md-12' style='padding:30px;'>
                        <img src="https://storage.googleapis.com/stateless-info-tbsn-my-2/2021/02/91c74a77-logo.png"  />
                        <h2 class='text-center' style='color:#600'>第<?= $setting['session']; ?>屆<?= $setting['year']; ?>年度<br />常年會員代表大會</h2>
                        
                        <center><table style='font-size: 16px;'>
                            <tr>
                                <td>日期：</td>
                                <td><?= $setting['date']; ?></td>
                            </tr>
                            <tr>
                                <td>時間：</td>
                                <td><?= $setting['time']; ?></td>
                            </tr>
                        </table></center>
                    </div>

                    <div class='row'>&nbsp;</div>

                    <div class='row row-data col-xs-10 col-xs-offset-1'>
                        <h3>登記名單</h3>
                        <p><strong>道場：</strong><?= $chapter['name_chinese'];?></p>

                        <table class="table table-striped myTable">
                            <thead><tr>
                                <th>No</th><th>姓名</th><th>職位</th><th>身份證號碼</th><th>登記日期</th>
                            </tr></thead>
                            <tbody>
                                <?php foreach($registrant as $k => $r): $tr = '';?>
                                <?php if($r['membership_id'] == '列席'){ $r['position'] .= ' (列席)'; $tr = 'warning';} ?>
                                <tr class='<?=$tr;?>'>
                                    <td><?= $k+1;?></td>
                                    <td><?= $r['name_chinese'];?></td>
                                    <td><?= $r['position'];?></td>
                                    <td><?= $r['masked_nric'];?></td>
                                    <td><?= $r['reg_date'];?></td>
                                </tr>
                                <?php endforeach;?>
                            </tbody>
                        </table>

                        <div class='form form-group text-right'>
                            <a href="<?= base_url('agm/register'); ?>" class="btn btn-warning">
                                <i class="fa-solid fa-rotate-left"></i> 返回 Back
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</body>
</html>