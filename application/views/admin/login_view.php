<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>馬密總網頁 API</title>
        <link rel="shortcut icon" href="<?php echo base_url(); ?>assets/images/icon/tab-icon.png" type="image/x-icon">

        <!-- Bootstrap -->
        <link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

        <style>
            body {
                background-color: #E6E6E6;
            }

            .backcolor-replace {
                background-color: #E6E6E6;
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
        </style>
    </head>
    <body>
        <div class="jumbotron vertical-center backcolor-replace">
            <div class="col-xs-12 col-sm-8 col-md-4 col-lg-4 col-sm-offset-2 col-md-offset-4 col-lg-offset-4">
                <h2 class='text-center'>馬密總網頁 API</h2>
                <div class="panel panel-default drop-shadow">
                    <div class="panel-body">
                        <div class='row text-center'>
                            <div class='col-md-12' style='padding:30px;'><img src="http://admin.tbsn.my/asset/img/tbsn-my-logo.jpg" /></div>
                            <div class='col-md-12'><a href="<?php echo $authUrl; ?>"><img class='col-md-6 col-md-offset-3' id="google_signin" src="<?=base_url()?>assets/img/icon-google.png"></a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
    </body>
</html>