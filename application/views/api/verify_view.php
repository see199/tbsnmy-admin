<!DOCTYPE html>
<html lang="zh-tw">

<head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>登記會員</title>
        <link rel="shortcut icon" href="<?php echo base_url(); ?>assets/images/icon/tab-icon.png" type="image/x-icon">

        <!-- Bootstrap -->
        <link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet">

        <!-- jQuery -->
        <script src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
        <script type="text/javascript" language="javascript" src="<?php echo base_url(); ?>assets/js/jquery.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/jquery-1.10.2.js"></script>
        <script src="<?php echo base_url(); ?>assets/js/jquery-ui.js"></script>

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
    </head>

<body>

<script type="text/javascript">
function check_empty(){
    if(!$('#name').val() || !$('#phone').val() || !$('#email').val() || !$('#address').val()){
        alert("請填寫所有資料！Please fill in all information!");
        return false;
    }
}
</script>

    <div class="col-xs-12 col-sm-8 col-md-4 col-lg-4 col-sm-offset-2 col-md-offset-4 col-lg-offset-4">
        <br />
        <div class="panel panel-default drop-shadow">
            <div class="panel-body">
                <div class='row text-center'>
                    <div class='col-md-12' style='padding:30px;'>
                        <h2 class='text-center' style='color:#600'>登記會員</h2>
                        
                        <center><table style='font-size: 16px;'>
                            <tr>
                                <td>講解：</td>
                                <td>很多很多講解</td>
                            </tr>
                        </table></center>

                        <?php if($updated == true): ?>
                            <div class='alert alert-success'>Success Update</div>
                        <?php endif; ?>
                    </div>

                    <form class='form-horizontal' id="upload_form" method="post" action="<?= base_url('api/verify');?>">

                        <div class='row'>&nbsp;</div>

                        <div style='text-align:left'>

                           <div class='row row-data col-xs-10 col-xs-offset-1'>
                                <div class='col-md-5 strong_txt'>姓名 | Name | Nama:</div>
                                <div class='col-md-7'>
                                    <div class='form form-group'><input type='text' class='form-control col-xs-8' name='name' id='name' /></div>
                                </div>
                            </div>

                           <div class='row row-data col-xs-10 col-xs-offset-1'>
                                <div class='col-md-5 strong_txt'>電郵 | Email:</div>
                                <div class='col-md-7'>
                                    <div class='form form-group'><input type='text' class='form-control col-xs-8' name='email' id='email' /></div>
                                </div>
                            </div>

                            <div class='row row-data col-xs-10 col-xs-offset-1'>
                                <div class='col-md-5 strong_txt'>電話 | Phone No | Telepon:</div>
                                <div class='col-md-7'>
                                    <div class='form form-group'><input type='text' class='form-control col-xs-8' name='phone' id='phone' /></div>
                                </div>
                            </div>

                            <div class='row row-data col-xs-10 col-xs-offset-1'>
                                <div class='col-md-5 strong_txt'>地址 | Address | Alamat:</div>
                                <div class='col-md-7'>
                                    <div class='form form-group'><textarea class='form-control col-xs-8' name='address' id='address' rows=5></textarea></div>
                                </div>
                            </div>


                            <div class='row'>
                                <div class='col-xs-2 col-xs-offset-1'></div>
                                <div class='col-xs-8'>
                                    <div class='form form-group text-right'>
                                        <input type="submit" id="btn-check" class="btn btn-success" onclick="return check_empty()" value="Submit">
                                    </div>
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