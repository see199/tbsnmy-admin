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
    </head>

<body>

<script type="text/javascript">

function update_chapter_id(val){
    $('#chapter_id').val(val);
}

function check_empty(){
    if(!$('#online_yes').is(':checked') && !$('#online_no').is(':checked')){
        alert("請選擇通過 ZOOM 出席或現場出席！Please choose within attendance via ZOOM or on site!");
        return false;
    }
    if(!$('#name_chinese').val() || !$('#name_malay').val() || !$('#email').val()){
        alert("請填寫所有資料！Please fill in all information!");
        return false;
    }
}

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

    $('#btn-check').on('click',function(){

        if(!$('#nric').val()){
            alert("請填入身份證號碼！");
            return;
        }


        $('#page1').hide();
        $('#page-check').show();

        $.ajax({
            url: '<?= base_url('agm/get_contact_by_nric'); ?>/'+$('#nric').val(),
            type: 'get',
            success: function( data, textStatus, jQxhr ){
                me = JSON.parse(data);
                console.log(me);
                $('#name_chinese').val(me.name_chinese);
                $('#name_malay').val(me.name_malay);
                $('#email').val(me.email);
                $('#online').val(me.online);
                $('#contact_id').val(me.contact_id);
                $('#page-check').hide();
            }
        });
        $('#page2').show();
    });
});

</script>

    <div class="col-xs-12 col-sm-8 col-md-4 col-lg-4 col-sm-offset-2 col-md-offset-4 col-lg-offset-4">
        <br />
        <div class="panel panel-default drop-shadow">
            <div class="panel-body">
                <div class='row text-center'>
                    <div class='col-md-12' style='padding:30px;'>
                        <img src="https://storage.googleapis.com/stateless-info-tbsn-my-2/2021/02/91c74a77-logo.png" width="100%" />
                        <h2 class='text-center' style='color:#600'>第8屆<?= date('Y'); ?>年度<br />常年會員代表大會<br />登記表 (個人)</h2>

                        <?php if($msg_code == 'success_reg'): ?>
                            <div class='alert alert-success'><?= $msg; ?></div>
                        <?php elseif($msg_code == 'error'):?>
                            <div class='alert alert-danger'><?= $msg; ?></div>
                        <?php endif; ?>
                    </div>

                    <form class='form-horizontal' id="upload_form" method="post" action="<?= base_url('agm/add_registrant_personal');?>">

                        <div class='row'>&nbsp;</div>

                        <div id="page1">
                           <div class='row row-data col-xs-10 col-xs-offset-1'>
                                <div class='col-xs-3 strong_txt'>身份證號碼 IC No:</div>
                                <div class='col-xs-9'>
                                    <div class='form form-group'><input type='text' class='form-control col-xs-8' name='nric' id='nric' maxlength="14" /></div>
                                </div>
                            </div>


                            <div class='row'>
                                <div class='col-xs-2 col-xs-offset-1'></div>
                                <div class='col-xs-8'>
                                    <div class='form form-group text-right'>
                                        <a id="btn-check" class="btn btn-success">
                                            查詢 Check
                                        </a>

                                        <a href="<?= base_url('agm/zoom_login'); ?>" class="btn btn-warning">
                                            返回登入 Back to Login
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="page-check" style='display:none;'>
                            <div class='row row-data col-xs-10 col-xs-offset-1' id="checking-msg"> 查詢中... Checking...</div>
                        </div>

                        <input type='hidden' id="chapter_id" name="chapter_id">
                        <input type='hidden' id="contact_id" name="contact_id">

                        <div id="page2" style='display:none;text-align:left'>

                           <div class='row row-data col-xs-10 col-xs-offset-1'>
                                <div class='col-md-3 strong_txt'>姓名（中）Chinese Name:</div>
                                <div class='col-md-9'>
                                    <div class='form form-group'><input type='text' class='form-control col-xs-8' name='name_chinese' id='name_chinese' /></div>
                                </div>
                            </div>

                            <div class='row row-data col-xs-10 col-xs-offset-1'>
                                <div class='col-md-3 strong_txt'>姓名（英）English Name:</div>
                                <div class='col-md-9'>
                                    <div class='form form-group'><input type='text' class='form-control col-xs-8' name='name_malay' id='name_malay' /></div>
                                </div>
                            </div>

                            <div class='row row-data col-xs-10 col-xs-offset-1'>
                                <div class='col-md-3 strong_txt'>電郵 Email:</div>
                                <div class='col-md-9'>
                                    <div class='form form-group'><input type='text' class='form-control col-xs-8' name='email' id='email' /></div>
                                </div>
                            </div>

                            <div class='row row-data col-xs-10 col-xs-offset-1'>
                                <div class='col-md-3 strong_txt'></div>
                                <div class='col-md-9'>
                                    <div class='form form-check form-check-inline'>
                                        <input class="form-check-input" type="radio" name="online" id="online_yes" value="1"><label class="form-check-label" for="online_yes">ZOOM 出席會議</label>
                                        <input class="form-check-input" type="radio" name="online" id="online_no" value="0"><label class="form-check-label" for="online_no">現場出席會議</label>
                                    </div>
                                </div>
                            </div>


                            <div class='row'>
                                <div class='col-xs-2 col-xs-offset-1'></div>
                                <div class='col-xs-8'>
                                    <div class='form form-group text-right'>
                                        <button id="btn-check" class="btn btn-success" onclick="return check_empty()">
                                            登記 Register
                                        </button>
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