<!DOCTYPE html>
<html lang="zh-tw">

<head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>馬密總會員大會登入</title>
        <link rel="shortcut icon" href="<?php echo base_url(); ?>assets/images/icon/tab-icon.png" type="image/x-icon">

        <!-- Bootstrap -->
        <link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet">

        <!-- jQuery -->
        <script src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
        <script type="text/javascript" language="javascript" src="<?php echo base_url(); ?>assets/js/jquery.js"></script>
        <script src="//code.jquery.com/jquery-1.10.2.js"></script>
        <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

        <style>
            body {
                background-color: #E6E6E6;
                background: url(<?php echo base_url(); ?>asset/img/lotus-2528454_1920.jpg);
                background-size: cover;
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

        <script>
            $(document).ready(function(){
                $( "#nric" ).keyup(function() {
                    var ic_val = this.value;
                    if($.isNumeric(ic_val.charAt(6))) $('#nric').val([ic_val.slice(0,6),'-',ic_val.slice(6)].join(''));

                    if(ic_val.length == 12)
                        if($.isNumeric(ic_val.charAt(8)) && $.isNumeric(ic_val.charAt(6))) $('#nric').val([ic_val.slice(0,8),'-',ic_val.slice(8)].join(''));
                    if(ic_val.length == 10){
                        console.log("here");
                        console.log($.isNumeric(ic_val.charAt(9)));
                        if($.isNumeric(ic_val.charAt(9))) $('#nric').val([ic_val.slice(0,9),'-',ic_val.slice(9)].join(''));
                    }
                });
            });
           
  

        </script>
    </head>

<body>

    <div class="col-xs-12 col-sm-8 col-md-4 col-lg-4 col-sm-offset-2 col-md-offset-4 col-lg-offset-4">
        <br />
        <div class="panel panel-default drop-shadow">
            <div class="panel-body">
                <div class='row text-center'>
                    <div class='col-md-12' style='padding:30px;'>
                        <img src="https://storage.googleapis.com/stateless-info-tbsn-my-2/2021/02/91c74a77-logo.png" width="100%" />
                        <h2 class='text-center' style='color:#600'>第8屆<?= date('Y'); ?>年度<br />常年會員代表大會<br />登入處</h2>
                        <?php if(@$error == 'user_not_found'): ?><div class='alert alert-danger'>您並未申請登記。請先註冊登記！</div><?php endif; ?>
                        
                        <center><table style='font-size: 16px;'>
                            <tr>
                                <td>日期：</td>
                                <td>2021年10月3日</td>
                            </tr>
                            <tr>
                                <td>時間：</td>
                                <td>1:00 PM</td>
                            </tr>
                        </table></center>
                    </div>

                    <form class='form-horizontal' id="upload_form" method="post" action="<?= base_url('agm/zoom_login');?>">

                        <div class='row'>&nbsp;</div>

                        <div class='row row-data col-xs-10 col-xs-offset-1'>
                            <div class='col-xs-3 strong_txt'>身份證號碼 IC No:</div>
                                <div class='col-xs-9'>
                                    <div class='form form-group'><input type='text' class='form-control col-xs-8' name='nric' id='nric' maxlength="14" placeholder="身份證號碼 IC No" /></div>
                                </div>
                            </div>


                            <div class='row row-data col-xs-10 col-xs-offset-1'>
                                <div class='col-xs-2 col-xs-offset-1'></div>
                                <div class='col-xs-12'>
                                    <div class='form form-group text-right'>
                                        <button id="btn-check" class="btn btn-success">
                                            登入 Login
                                        </button>
                                        <br /><br />
                                        <a href="<?= base_url('agm/register'); ?>" class="btn btn-warning">
                                            註冊登記 Register (團體 | Group)
                                        </a><br /><br />
                                        <a href="<?= base_url('agm/register_personal'); ?>" class="btn btn-warning">
                                            註冊登記 Register (個人 | Personal)
                                        </a>
                                    </div>
                                </div>
                            </div>

                    </form>
                </div>
            </div>
        </div>
    </div>


<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
</body>
</html>