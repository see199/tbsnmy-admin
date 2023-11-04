<?php

$event_1 = '第一場：台灣時間 11/25（六）下午3點';
$event_2 = '第二場：西雅圖時間 11/25（六）早上10點';

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

        <!-- MetisMenu CSS -->
        <link href="<?= base_url(); ?>assets/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

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
                background-color: #FFF;
                font-size:large;
            }
            h2 {
                color: #700;
                font-weight:bold;
            }

            /* Combo Box */
            .custom-combobox {
                position: relative;
                display: inline-block;
            }
            .custom-combobox-toggle {
                position: absolute;
                top: 0;
                bottom: 0;
                margin-left: -1px;
                padding: 0;
            }
            .custom-combobox-input {
                margin: 0;
                padding: 5px 10px;
                width: 100%;
            }
            /* Combo Box End */
        </style>

  <!-- Combo box JavaScript -->
  <script>
  (function( $ ) {
    $.widget( "custom.combobox", {
      _create: function() {
        this.wrapper = $( "<span>" )
          .addClass( "custom-combobox" )
          .insertAfter( this.element );
 
        this.element.hide();
        this._createAutocomplete();
        this._createShowAllButton();
      },
 
      _createAutocomplete: function() {
        var selected = this.element.children( ":selected" ),
          value = selected.val() ? selected.text() : "";
 
        this.input = $( "<input>" )
          .appendTo( this.wrapper )
          .val( value )
          .attr( "title", "" )
          .addClass( "custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left" )
          .autocomplete({
            delay: 0,
            minLength: 0,
            source: $.proxy( this, "_source" ),
            position: {my:"left top+15"}
          })
          .tooltip({
            tooltipClass: "ui-state-highlight"
          });
 
        this._on( this.input, {
          autocompleteselect: function( event, ui ) {
            ui.item.option.selected = true;
            if(ui.item.option.parentElement.name == 'chapter_country'){

              if(ui.item.option.attributes[0].nodeValue == '不在名單內'){
                $('#chapter_name_other').attr('style','display:block');
                $('#chapter_id').attr('style','display:none');
                $('#chapter_id').html("");
                $("#chapter_id" ).combobox();
                $('#chapter_id').siblings().first().hide()

              }else{
                $.ajax({
                    url: '<?= base_url();?>event/ajax_get_chapter_by_country',
                    type: 'post',
                    data: {country: ui.item.option.attributes[0].nodeValue},
                    success: function(data) {
                        res = JSON.parse(data);
                        var options = '';
                        for (var i = 0; i < res.length; i++) {
                            options += '<option value="' + res[i].value + '">' + res[i].text + '</option>';
                        }
                        $('#chapter_id').html(options);
                        $("#chapter_id" ).combobox();
                    }
                });
              }
            }else if(ui.item.option.parentElement.name == 'chapter_id'){
                $('#chapter_name').val(ui.item.value);

                $.ajax({
                    url: '<?= base_url();?>event/ajax_get_chapter_timezone/',
                    type: 'post',
                    data: {chapter_id: $('#chapter_id').val()},
                    success: function(data) {
                        chapter_timezone.val(data);
                        updateLabel();
                    }
                });
            }
            this._trigger( "select", event, {
              item: ui.item.option
            });
          },
 
          autocompletechange: "_removeIfInvalid"
        });
 
      },
 
      _createShowAllButton: function() {
        var input = this.input,
          wasOpen = false;
 
        $( "<a>" )
          .attr( "tabIndex", -1 )
          .tooltip()
          .appendTo( this.wrapper )
          .button({
            icons: {
              primary: "ui-icon-triangle-1-s"
            },
            text: false
          })
          .removeClass( "ui-corner-all" )
          .addClass( "custom-combobox-toggle ui-corner-right" )
          .mousedown(function() {
            wasOpen = input.autocomplete( "widget" ).is( ":visible" );
          })
          .click(function() {
            input.focus();
 
            // Close if already visible
            if ( wasOpen ) {
              return;
            }
 
            // Pass empty string as value to search for, displaying all results
            input.autocomplete( "search", "" );
          });
      },
 
      _source: function( request, response ) {
        var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
        response( this.element.children( "option" ).map(function() {
          var text = $( this ).text();
          if ( this.value && ( !request.term || matcher.test(text) ) )
            return {
              label: text,
              value: text,
              option: this
            };
        }) );
      },
 
      _removeIfInvalid: function( event, ui ) {
 
        // Selected an item, nothing to do
        if ( ui.item ) {
          return;
        }
 
        // Search for a match (case-insensitive)
        var value = this.input.val(),
          valueLowerCase = value.toLowerCase(),
          valid = false;
        this.element.children( "option" ).each(function() {
          if ( $( this ).text().toLowerCase() === valueLowerCase ) {
            this.selected = valid = true;
            return false;
          }
        });
 
        // Found a match, nothing to do
        if ( valid ) {
          return;
        }
 
        // Remove invalid value
        this.input
          .val( "" )
          .attr( "title", "輸入：'" + value + "'無效，請點擊選擇。" )
          .tooltip( "open" );
        this.element.val( "" );
        this._delay(function() {
          this.input.tooltip( "close" ).attr( "title", "" );
        }, 2500 );
        this.input.autocomplete( "instance" ).term = "";
      },
 
      _destroy: function() {
        this.wrapper.remove();
        this.element.show();
      }
    });
  })( jQuery );
 
  $(function() {
    $( "#chapter_country" ).combobox();
  });

  </script>
  <!-- Combo box JavaScript End -->



    </head>

<body>

    <div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-sm-offset-1 col-md-offset-1 col-lg-offset-1">
        <br />
                <div class='row text-center'>
                    <div class='col-lg-10 col-lg-offset-1 col-xs-10 col-xs-offset-1'>

                        <div class='row'>
                            <h2 class='text-center'><?=str_replace("\r\n", "<br>", $event['title']);?></h2>
                            <br /><img style='width: 100%; max-width:920px;' src='<?=$event['banner_url'];?>' />
                            <br /><br /><div class='text-justify'><?= str_replace("\r\n", "<br>", $event['description']);?></div>
                        </div>


                        <?php if (isset($msg) && $msg != ""): ?>
                        <div class='row'>
                            <div class="alert alert-success" role="alert"><?php echo $msg; ?></div>
                        </div>
                        <?php endif;?>

                        <hr />
                    </div>

                    <div class='col-xs-11'>
                    <form class='form-horizontal' method="post" action="<?= base_url('event/register/'.$event['event_id']);?>">
                        <input type='hidden' name='event_id' value='<?=$event['event_id'];?>' />
                        <input type='hidden' id='chapter_name' name='chapter_name' />

                        <div class='row'>&nbsp;</div>

                        <div class='row row-data'>
                            <div class='col-xs-3 col-lg-4 strong_txt text-right'><font color='red'>*</font>國家:</div>
                            <div class='col-xs-9 col-lg-6 text-left form-row'>
                                <div class='form form-group'>
                                    <select required class='form-control' id='chapter_country' name='chapter_country'><?php foreach ($chapter_country as $c): ?>
                                    <option value="<?php echo $c; ?>"><?php echo $c; ?></option>
                                    <?php endforeach; ?></select>
                                </div>
                            </div>
                        </div>

                        <div class='row row-data'>
                            <div class='col-xs-3 col-lg-4 strong_txt text-right'><font color='red'>*</font>道場:</div>
                            <div class='col-xs-9 col-lg-6 text-left form-row'>
                                <div class='form form-group'>
                                    <select class='custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left ui-autocomplete-input' id='chapter_id' name='chapter_id'></select>
                                    <input type='text' class='form-control' id='chapter_name_other' name='chapter_name_other' style='display:none' placeholder="請填寫道場及國家" />
                                </div>
                            </div>
                        </div>

                        <div class='row row-data'>
                            <div class='col-xs-3 col-lg-4 strong_txt text-right'><font color='red'>*</font>時區:</div>
                            <div class='col-xs-9 col-lg-6 text-left form-row'>
                                <div class='form form-group'>
                                    <select class='custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left' id='chapter_timezone' name='chapter_timezone'>
                                        <option value=''></option>
                                        <option value='1'>馬來西亞 / 新加坡 / 汶萊</option>
                                        <option value='2'>台灣</option>
                                        <option value='3'>香港</option>
                                        <option value='4'>泰國 / 越南 / 柬埔寨</option>
                                        <option value='5'>印尼（西部）</option>
                                        <option value='6'>印尼（中部）</option>
                                        <option value='7'>印尼（東部）</option>
                                        <option value='8'>日本</option>
                                        <option value='9'>關島</option>
                                        <option value='10'>澳洲（東南部）</option>
                                        <option value='11'>澳洲（聖誕島）</option>
                                        <option value='12'>澳洲（西部）</option>
                                        <option value='13'>紐西蘭</option>
                                        <option value='14'>巴西（聖保羅）/（福塔雷薩）</option>
                                        <option value='15'>美國（西岸)/加拿大（BC省）</option>
                                        <option value='16'>美國（山地時區）/加拿大（AB省）</option>
                                        <option value='17'>美國（中部時區）</option>
                                        <option value='18'>美國（東岸時區）/加拿大（ON/QC省）/巴拿馬</option>
                                        <option value='19'>波多黎各/多米尼加共和國</option>
                                        <option value='20'>夏威夷</option>
                                        <option value='21'>英國/葡萄牙</option>
                                        <option value='22'>歐洲-1：瑞典/挪威/德國/瑞士/法國/西班牙/意大利</option>
                                        <option value='23'>歐洲-2：芬蘭/希臘</option>
                                        <option value='24'>南非</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class='row row-data'>
                            <div class='col-xs-3 col-lg-4 strong_txt text-right'><font color='red'>*</font>時段:</div>
                            <div class='col-xs-9 col-lg-6 text-left form-row'>
                                <div class='form form-group'>
                                    <input type="radio" id="event_type_1" name="event_type" value="1">
                                    <label for="event_type_1" id="label_type_1"><?= $event_1;?></label><br>
                                    <input type="radio" id="event_type_2" name="event_type" value="2">
                                    <label for="event_type_2" id="label_type_2"><?= $event_2;?></label><br>
                                </div>
                            </div>
                        </div>

                        <div class='row row-data'>
                            <div class='col-xs-3 col-lg-4 strong_txt text-right'><font color='red'>*</font>道場負責人:</div>
                            <div class='col-xs-9 col-lg-6 text-left form-row'>
                                <div class='form form-group'>
                                    <input type='text' name='chapter_pic' class='form-control' placeholder="道場負責人" required>
                                  </div>
                            </div>
                        </div>

                        <div class='row row-data'>
                            <div class='col-xs-3 col-lg-4 strong_txt text-right'><font color='red'>*</font>道場電郵:</div>
                            <div class='col-xs-9 col-lg-6 text-left form-row'>
                                <div class='form form-group'>
                                    <input type='email' name='chapter_email' class='form-control' placeholder="道場電郵" required>
                                  </div>
                            </div>
                        </div>

                        <div class='row row-data'>
                            <div class='col-xs-3 col-lg-4 strong_txt text-right'><font color='red'>*</font>主壇者</div>
                            <div class='col-xs-9 col-lg-6 text-left'>
                                <div class='form form-group'>
                                    <input type='text' name='master_name' class='form-control' placeholder="例：釋蓮A上師, 蓮花BB助教" required>
                                </div>
                            </div>
                        </div>

                        <div class='row row-data'>
                            <div class='col-xs-3 col-lg-4 strong_txt text-right'>護壇者</div>
                            <div class='col-xs-9 col-lg-6 text-left'>
                                <div class='form form-group'>
                                    <input type='text' name='join_personnel' class='form-control' placeholder="可添多位。例：釋蓮A教授師, 蓮花BB助教">
                                </div>
                            </div>
                        </div>

                        <div class='row row-data'>
                            <div class='col-xs-12 col-lg-10'>
                                <div class='form form-group text-right'>
                                    <button id="btn-check" class="btn btn-success">
                                        登記
                                    </button>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
    </div>
</body>

  <script type="text/javascript">

    const timezone_mapping = {
        // 2: Taiwan
        // 8: Seattle
        1   :2 ,
        2   :2 ,
        3   :2 ,
        4   :4 ,
        5   :4 ,
        6   :2 ,
        7   :3 ,
        8   :3 ,
        9   :5 ,
        10  :16 , //5
        11  :4 ,
        12  :2 ,
        13  :6 ,
        14  :7 ,
        15  :8 ,
        16  :9 ,
        17  :10,
        18  :11,
        19  :11,
        20  :12,
        21  :13,
        22  :14,
        23  :15,
        24  :14
    };

    const timezone_list_1 = {
        "2":   "<?= $event_1;?>（當地時間：11/25  3PM）",
        "3":   "<?= $event_1;?>（當地時間：11/25  4PM）",
        "4":   "<?= $event_1;?>（當地時間：11/25  2PM）",
        "5":   "<?= $event_1;?>（當地時間：11/25  5PM）",
        "6":   "<?= $event_1;?>（當地時間：11/25  8PM）",
        "7":   "<?= $event_1;?>（當地時間：11/25  4AM）",
        "8":   "<?= $event_1;?>（當地時間：11/25  0AM）",
        "9":   "<?= $event_1;?>（當地時間：11/25  1AM）",
        "10":  "<?= $event_1;?>（當地時間：11/25  2AM）",
        "11":  "<?= $event_1;?>（當地時間：11/25  3AM）",
        "12":  "<?= $event_1;?>（當地時間：11/24  9PM）",
        "13":  "<?= $event_1;?>（當地時間：11/25  7AM）",
        "14":  "<?= $event_1;?>（當地時間：11/25  8AM）",
        "15":  "<?= $event_1;?>（當地時間：11/25  9AM）",
        "16":  "<?= $event_1;?>（當地時間：11/25  6PM）"
    }

    const timezone_list_2 = {
        "2":   "<?= $event_2;?>（當地時間：11/26  1AM）",
        "3":   "<?= $event_2;?>（當地時間：11/26  2AM）",
        "4":   "<?= $event_2;?>（當地時間：11/26  0AM）",
        "5":   "<?= $event_2;?>（當地時間：11/26  3AM）",
        "6":   "<?= $event_2;?>（當地時間：11/26  6AM）",
        "7":   "<?= $event_2;?>（當地時間：11/25  2PM）",
        "8":   "<?= $event_2;?>（當地時間：11/25 10AM）",
        "9":   "<?= $event_2;?>（當地時間：11/25 11AM）",
        "10":  "<?= $event_2;?>（當地時間：11/25 12PM）",
        "11":  "<?= $event_2;?>（當地時間：11/25  1PM）",
        "12":  "<?= $event_2;?>（當地時間：11/25  7AM）",
        "13":  "<?= $event_2;?>（當地時間：11/25  5PM）",
        "14":  "<?= $event_2;?>（當地時間：11/25  6PM）",
        "15":  "<?= $event_2;?>（當地時間：11/25  7PM）",
        "16":  "<?= $event_1;?>（當地時間：11/25  4AM）"
    }

    const chapter_timezone = $('#chapter_timezone');
    chapter_timezone.on('change',updateLabel);
    function updateLabel(){
        $('#label_type_1').html(timezone_list_1[timezone_mapping[chapter_timezone.val()]]);
        $('#label_type_2').html(timezone_list_2[timezone_mapping[chapter_timezone.val()]]);
    }


      
  </script>
</html>