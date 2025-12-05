<?php
$event_list = array(
    array('第01場：',"2026-01-01 10:00AM"),
    array('第02場：',"2026-01-01 02:00PM"),
    array('第03場：',"2026-01-01 05:00PM"),
    array('第04場：',"2026-01-01 08:00PM"),
    array('第05場：',"2026-01-02 02:00AM"),
    /*array('第06場：',"2024-01-01 01:00AM"),
    array('第07場：',"2024-01-01 02:00AM"),
    array('第08場：',"2024-01-01 03:00AM"),
    array('第09場：',"2024-01-01 04:00AM"),
    array('第10場：',"2024-01-01 05:00AM"),
    array('第11場：',"2024-01-01 06:00AM"),
    array('第12場：',"2024-01-01 07:00AM"),
    array('第13場：',"2024-01-01 08:00AM"),
    array('第14場：',"2024-01-01 09:00AM"),
    array('第15場：',"2024-01-01 10:00AM"),
    array('第16場：',"2024-01-01 11:00AM"),
    array('第17場：',"2024-01-01 12:00PM"),
    array('第18場：',"2024-01-01 01:00PM"),
    array('第19場：',"2024-01-01 02:00PM"),
    array('第20場：',"2024-01-01 03:00PM"),
    array('第21場：',"2024-01-01 04:00PM"),
    array('第22場：',"2024-01-01 05:00PM"),
    array('第23場：',"2024-01-01 06:00PM"),
    array('第24場：',"2023-01-01 07:00PM"),*/
);
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
            <?php if($event['due_date']):?>
                .due-date {
                    pointer-events: none;
                }
                .due-date-div {
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background-color: rgba(0,0,0,0.7);
                    z-index: 2;
                    pointer-events: none;
                }
            <?php endif;?>
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

                    <div class='col-xs-11' class="due-date">
                    <form class="due-date" style='z-index: 1;position: relative;' class='form-horizontal' method="post" action="<?= base_url('event/register/'.$event['event_id']);?>">
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
                                    <?php foreach($event_list as $k => $v):?> <?php $id = $k+1; ?>
                                    <input type="checkbox" id="event_type_<?=$id;?>" name="event_type[<?=$id;?>]" value="<?=$id;?>">
                                    <label for="event_type_<?=$id;?>" id="label_type_<?=$id;?>"><?= $v[0];?></label><br>
                                    <?php endforeach; ?>
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
                    <div class="due-date-div"></div>
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
        10  :5 , //16
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

    // Your initial date string TW Date: 2023-12-31 2330
    const timezone_list = [
        <?php foreach($event_list as $k => $v): ?>
        getTimeZoneList("<?=$v[0];?>","<?=$v[1];?>"),
        <?php endforeach; ?>
    ];

    

    const chapter_timezone = $('#chapter_timezone');
    chapter_timezone.on('change',updateLabel);
    function updateLabel(){
        $.each(timezone_list,function(index){
            var i = index+1;
            $('#label_type_'+i).html(timezone_list[index][timezone_mapping[chapter_timezone.val()]]);
        });
    }

    function getTimeZoneList(eventDesc,myDate){
        return {
            "6":   eventDesc + "（當地時間：" + addHours(myDate,  5) + "）",
            "16":  eventDesc + "（當地時間：" + addHours(myDate,  3) + "）",
            "5":   eventDesc + "（當地時間：" + addHours(myDate,  2) + "）",
            "3":   eventDesc + "（當地時間：" + addHours(myDate,  1) + "）",
            "2":   eventDesc + "（當地時間：" + addHours(myDate,  0) + "）",
            "4":   eventDesc + "（當地時間：" + addHours(myDate,- 1) + "）",
            "15":  eventDesc + "（當地時間：" + addHours(myDate,- 5) + "）",
            "14":  eventDesc + "（當地時間：" + addHours(myDate,- 6) + "）",
            "13":  eventDesc + "（當地時間：" + addHours(myDate,- 7) + "）",
            "7":   eventDesc + "（當地時間：" + addHours(myDate,-11) + "）",
            "11":  eventDesc + "（當地時間：" + addHours(myDate,-12) + "）",
            "10":  eventDesc + "（當地時間：" + addHours(myDate,-13) + "）",
            "9":   eventDesc + "（當地時間：" + addHours(myDate,-14) + "）",
            "8":   eventDesc + "（當地時間：" + addHours(myDate,-16) + "）",
            "12":  eventDesc + "（當地時間：" + addHours(myDate,-18) + "）",
        }
    }

    // Add Hours into Date and Return in MM/DD HH:MM AM/PM
    function addHours(myDate,hoursToAdd){

        var parts = myDate.match(/(\d+)-(\d+)-(\d+) (\d+)(?::(\d+))?\s?([APMapm]+)?/);
        var year = parseInt(parts[1], 10);
        var month = parseInt(parts[2], 10) - 1;
        var day = parseInt(parts[3], 10);
        var hour = parts[6] && parts[6].toLowerCase() === 'pm' && parts[4] != 12 ? parseInt(parts[4], 10) + 12 : parseInt(parts[4], 10);
        var minutes = parseInt(parts[5], 10) || 0;
        var myDateObject = new Date(year, month, day, hour, minutes);

        var theTime = theMonth = '';

        myDateObject.setHours(myDateObject.getHours() + hoursToAdd);

        if(myDateObject.getHours() > 12){
            theTime = myDateObject.getHours() - 12;
            theTime += ":"+('0' + myDateObject.getMinutes()).slice(-2) + "PM";
        }else{
            theTime = myDateObject.getHours() +":"+('0' + myDateObject.getMinutes()).slice(-2) + "AM";
            if(!myDateObject.getHours()) theTime = "12:"+('0' + myDateObject.getMinutes()).slice(-2) + "AM";

        }
        theMonth = (!myDateObject.getMonth()) ? 1 :myDateObject.getMonth() + 1;

        return ('0' + theMonth).slice(-2) + '/' +
               ('0' + myDateObject.getDate()).slice(-2) + ' ' +
               theTime;
    }


      
  </script>
</html>