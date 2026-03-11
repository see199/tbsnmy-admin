<!DOCTYPE html>
<html lang="zh-tw">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>會員大會登記表</title>
    <link rel="shortcut icon" href="<?php echo base_url(); ?>assets/images/icon/tab-icon.png" type="image/x-icon">

    <!-- Bootstrap -->
    <link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- jQuery -->
    <script src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/sb-admin-2.js"></script>

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
    </style>
</head>

<body>

<script type="text/javascript">
function update_chapter_id(val){
    $('#chapter_id').val(val);
}

function check_empty(){

    // Function to check if the required fields are filled
    function areFieldsFilled(index) {
        return $('#name_chinese' + index).val() && 
               $('#name_malay' + index).val() && 
               $('#email' + index).val() && 
               $('#position' + index).val() && 
               $('#phone_mobile' + index).val();
    }

    // Loop through each entry (1 to 3)
    for (let i = 1; i <= 3; i++) {
        // Check if name_chinese is not empty
        if ($('#name_chinese' + i).val()) {
            // Check if any online option is selected
            if (!$('#online_yes' + i).is(':checked') && !$('#online_no' + i).is(':checked')) {
                alert("請選擇通過 ZOOM 出席或現場出席！Please choose within attendance via ZOOM or on site for entry " + i + "!");
                return false;
            }
            // Check if all required fields are filled
            if (!areFieldsFilled(i)) {
                alert("請填寫所有資料！Please fill in all information for entry " + i + "!");
                return false;
            }
        }

    }

    // If all checks pass
    return true;
}

$(document).ready(function(){
    
    $('#chapter-').show(); // Default display Johor

    $('#state').on('change',function(){
        hide_all_chapter();
        $('#chapter-'+this.value).show();
    });

    function hide_all_chapter(){
        <?php foreach($states as $k => $state): ?>
        $('#chapter-<?=$k;?>').hide();
        <?php endforeach; ?>
    }

    function fetchContactByNRIC(nricId,index){
        const nric = $(nricId).val();

        if(!nric) return;

        $.ajax({
            url: '<?= base_url('agm/get_contact_by_nric'); ?>/'+nric,
            type: 'get',
            success: function( data, textStatus, jQxhr ){
                me = JSON.parse(data);
                // Set value from Data
                ['name_chinese','name_malay','email','position','online','contact_id','phone_mobile']
                    .forEach(field => {
                        $('#' + field + index).val(me[field]);
                    });
                $('#displayNRIC' + index).text(nric);
            }
        });
    }


    $('#btn-check').on('click',function(){

        // Error Checking
        if(!$('#chapter_id').val()){
            alert("請選擇代表道場！");
            return;
        }
        if(!$('#nric1').val()){
            alert("請填入身份證號碼！");
            return;
        }


        $('#page1').hide();
        $('#page-check').show();

        fetchContactByNRIC('#nric1',1);
        fetchContactByNRIC('#nric2',2);
        fetchContactByNRIC('#nric3',3);

        $('#page-check').hide();
        $('#page2').show();
    });

    $('#btn-check-list').on('click', function() {
        // Get the value from the input field with id=chapter_id
        var chapterId = $('#chapter_id').val();

        if(chapterId === ''){
            alert('請先選擇道場!');
            return;
        }
        
        // Redirect to the constructed URL
        window.location.href = '<?= base_url('agm/check_registered_chapter');?>/'+chapterId;
    });
});

</script>

    <div class="col-xs-12 col-sm-8 col-md-6 col-lg-6 col-sm-offset-2 col-md-offset-3 col-lg-offset-3">
        <br />
        <div class="panel panel-default drop-shadow">
            <div class="panel-body">
                <div class='row text-center'>
                    <div class='col-md-12' style='padding:30px;'>
                        <img src="https://storage.googleapis.com/stateless-info-tbsn-my-2/2021/02/91c74a77-logo.png" />
                        <h2 class='text-center' style='color:#600'>第<?= $setting['session']; ?>屆<?= $setting['year']; ?>年度<br />常年會員代表大會<br />登記表 (團體)</h2>
                        
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

                        <?php if($msg_code == 'success_reg'): ?>
                            <div class='alert alert-success'><?= $msg; ?></div>
                        <?php elseif($msg_code == 'error'):?>
                            <div class='alert alert-danger'><?= $msg; ?></div>
                        <?php endif; ?>
                    </div>

                    <form class='form-horizontal' id="upload_form" method="post" action="<?= base_url('agm/add_registrant');?>">

                        <div class='row'>&nbsp;</div>

                        <div id="page1">
                            <div class='row row-data col-xs-10 col-xs-offset-1'>
                                <div class='col-xs-4 strong_txt'>州屬 States:</div>
                                <div class='col-xs-8 form form-group'>
                                    <?= form_dropdown("state",$states,"",array("class"=>"form-control", "id" => "state")); ?>
                                </div>
                            </div>

                            <div class='row row-data col-xs-10 col-xs-offset-1'>
                                <div class='col-xs-4 strong_txt'>道場 Chapter:</div>
                                <div class='col-xs-8 form form-group'>
                                    <?php foreach($states as $k => $state): ?>
                                    <?= form_dropdown("chapter",$chapter_by_state[$k],"",array("class"=>"form-control", "id" => "chapter-".$k, "style" => "display:none", "onchange" => "update_chapter_id(this.value)")); ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <!-- Loop 3x for 代表 -->
                            <?php for ($i = 1; $i <= 3; $i++): ?>
                            <div class="row row-data col-xs-10 col-xs-offset-1">
                                <label class="col-xs-4" for="nric<?= $i ?>"><?= $i ?>. 身份證號碼 IC No:</label>
                                <div class='col-xs-8 form form-group'>
                                    <input type="text" class="form-control nric-input" 
                                           name="nric<?= $i ?>" id="nric<?= $i ?>" 
                                           maxlength="14" 
                                           placeholder="第<?= $i ?>位代表的身份證號碼">
                                </div>
                            </div>
                            <?php endfor; ?>


                            <div class='row row-data col-xs-10 col-xs-offset-1 text-right'>
                                <div class='col-xs-12 form form-group'>
                                    <a id="btn-check" class="btn btn-success">
                                        查詢 Check
                                    </a>
                                    <a href="<?= base_url('agm/zoom_login'); ?>" class="btn btn-warning">
                                        <i class="fa-solid fa-rotate-left"></i> 返回登入
                                    </a>
                                </div>
                                <div class='col-xs-12 form form-group'>
                                    <a id="btn-check-list" class="btn btn-success">
                                        <i class="fa-solid fa-table-list"></i> 查詢登記名單
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div id="page-check" style='display:none;'>
                            <div class='row row-data col-xs-10 col-xs-offset-1' id="checking-msg"> 查詢中... Checking...</div>
                        </div>

                        <input type='hidden' id="chapter_id" name="chapter_id">
                        <input type='hidden' id="contact_id1" name="contact_id1">
                        <input type='hidden' id="contact_id2" name="contact_id2">
                        <input type='hidden' id="contact_id3" name="contact_id3">

                        <div id="page2" style='display:none;text-align:left'>
                           
                           <?php for ($i = 1; $i <= 3; $i++): ?>
                           <div class='row row-data col-xs-10 col-xs-offset-1'>
                               <h4><b>第<?= $i; ?>位代表 <small>NRIC: <span id="displayNRIC<?= $i; ?>"></span></small></b></h4>
                           </div>
                           <div class='row row-data col-xs-10 col-xs-offset-1'>
                                <div class='col-md-3 strong_txt'>姓名（中）Chinese Name:</div>
                                <div class='col-md-9'>
                                    <div class='form form-group'><input type='text' class='form-control col-xs-8' name='name_chinese<?= $i; ?>' id='name_chinese<?= $i; ?>' /></div>
                                </div>
                            </div>

                            <div class='row row-data col-xs-10 col-xs-offset-1'>
                                <div class='col-md-3 strong_txt'>姓名（英）English Name:</div>
                                <div class='col-md-9'>
                                    <div class='form form-group'><input type='text' class='form-control col-xs-8' name='name_malay<?= $i; ?>' id='name_malay<?= $i; ?>' /></div>
                                </div>
                            </div>

                            <div class='row row-data col-xs-10 col-xs-offset-1'>
                                <div class='col-md-3 strong_txt'>電郵 Email:</div>
                                <div class='col-md-9'>
                                    <div class='form form-group'><input type='text' class='form-control col-xs-8' name='email<?= $i; ?>' id='email<?= $i; ?>' /></div>
                                </div>
                            </div>

                            <div class='row row-data col-xs-10 col-xs-offset-1'>
                                <div class='col-md-3 strong_txt'>電話 Phone No:</div>
                                <div class='col-md-9'>
                                    <div class='form form-group'><input type='text' class='form-control col-xs-8' name='phone_mobile<?= $i; ?>' id='phone_mobile<?= $i; ?>' /></div>
                                </div>
                            </div>

                            <div class='row row-data col-xs-10 col-xs-offset-1'>
                                <div class='col-md-3 strong_txt'>職位 Position:<small>* 主席/秘書/財政/理事</small></div>
                                <div class='col-md-9'>
                                    <div class='form form-group'><input type='text' class='form-control col-xs-8' name='position<?= $i; ?>' id='position<?= $i; ?>' /></div>
                                </div>
                            </div>

                            <div class='row row-data col-xs-10 col-xs-offset-1'>
                                <div class='col-md-3 strong_txt'></div>
                                <div class='col-md-9'>
                                    <div class='form form-check form-check-inline'>
                                        <input class="form-check-input" type="radio" name="online<?= $i; ?>" id="online_yes<?= $i; ?>" value="1"><label class="form-check-label" for="online_yes<?= $i; ?>">ZOOM 出席會議</label>
                                        <input class="form-check-input" type="radio" name="online<?= $i; ?>" id="online_no<?= $i; ?>" value="0"><label class="form-check-label" for="online_no<?= $i; ?>">現場出席會議</label>
                                    </div>
                                </div>
                            </div>
                            <div class='row row-data col-xs-10 col-xs-offset-1'><hr /></div>
                            <?php endfor; ?>


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


</body>
</html>