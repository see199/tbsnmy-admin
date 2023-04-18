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
                            登記總數：<?= count($stats);?>
                        </div>
                        <div class='row'>&nbsp;</div>


                        <div class='row text-left'>
                            <table id="example" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                                <thead>
                                    <tr class="info">
                                        <td><b>No</b></td>
                                        <td><b>上師法號</b></td>
                                        <td><b>國家</b></td>
                                    </tr>
                                </thead>
                                <tbody><?php foreach($stats as $k => $m): ?>
                                    <tr>
                                        <td><?= $k+1;?></td>
                                        <td><?= $m['master_name'];?></td>
                                        <td><?= $m['master_country'];?></td>
                                    </tr>
                                <?php endforeach;?></tbody>
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