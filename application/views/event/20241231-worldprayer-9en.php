<?php
$event_list = array(
    array('1st Session：',"2025-01-01 10:00AM"),
    array('2nd Session：',"2025-01-01 02:00PM"),
    array('3rd Session：',"2025-01-01 05:00PM"),
    array('4th Session：',"2025-01-01 08:00PM"),
    array('5th Session：',"2025-01-02 02:00AM"),
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
                            <h2 class='text-center'>2025 True Buddha School Praying for Buddha to Stay in the World and Praying for the World Event<br />Grand Worldwide Online Feast Offering to Golden Mother Ceremony Dedicated to Buddha Remaining in the World and for Blessing of the World.</h2>
                            <br /><img style='width: 100%; max-width:920px;' src='https://en.tbsn.org/images/2024_12_15/pray-for-world-en.jpg' />
                            <br /><br /><div class='text-justify'>
<font color="red">
<br />Love is a compassionate and selfless force.
<br />Praying for buddha to remain in the world is an expression of love for Grandmaster.
<br />Praying for the world is transforming love for Grandmaster into great love for all sentient beings.
<br />With buddha in the world, suffering beings can find refuge and salvation.
<br />May there be peace throughout the world, cessation of calamities, happiness of all beings, and the creation of a beautiful pure land on Earth.
<br />
<br /><b>Grand worldwide online Feast Offering to Golden Mother Ceremony dedicated to buddha remaining in the world and for blessing of the world.</b>
</font>
<br />
<br /><b>The event will be led by representative cultivation venues from Taiwan, Indonesia, Malaysia, and the United States.</b>
<br />
<br /><b><u>Schedule of the Five Sessions:</u></b>
<br /><b>[First Session]
<br />Feast Offering to Jade Pond Golden Mother Ceremony + <font color="red">Blessing with the Disaster Relief Mantra</font></b>
<br />Taiwan Time: January 1 (Wednesday), 10:00 AM
<br />Led by Taiwan Lei Tsang Temple
<br />
<br /><b>[Second Session]
<br />Feast Offering to Jade Pond Golden Mother Ceremony + <font color="red">Blessing with the Disaster Relief Mantra</font></b>
<br />Taiwan Time: Wednesday, January 1, 2025, 2:00 PM
<br />Led by Taiwan Chung Kuan Temple
<br />
<br /><b>[Third Session]
<br />Feast Offering to Jade Pond Golden Mother Ceremony + <font color="red">Blessing with the Disaster Relief Mantra</font></b>
<br />Taiwan Time: Wednesday, January 1, 2025, 5:00 PM (Indonesia Time: 4:00 PM)
<br /> Led by Indonesia Lei Tsang Temple
<br />
<br /><b>[Fourth Session]
<br />Feast Offering to Jade Pond Golden Mother Ceremony + <font color="red">Blessing with the Disaster Relief Mantra</font></b>
<br />Taiwan Time: Wednesday, January 1, 2025, 8:00 PM
<br />Led by Federal Tantric Buddhism Chen Foh Chong Malaysia (PERSEKUTUAN AGAMA BUDDHA TANTRAYANA CHEN FOH CHONG MALAYSIA)
<br />
<br /><b>[Fifth Session]
<br />Feast Offering to Jade Pond Golden Mother Ceremony + <font color="red">Blessing with the Disaster Relief Mantra</font></b>
<br />Seattle Time: Wednesday, January 1, 2025, 10:00 AM (Taiwan Time: Thursday, January 2, 2025, 2:00 AM)
<br />Led by Ling Shen Ching Tze Temple
<br />
<br />
<br />Note:
<br />True Buddha School Net (tbsn.org) will soon release webpages of master registration for homa, cultivation venue registration for feast offering ceremony, and mantra recitation registration. Please stay tuned.
<br />
<br />Cultivation venues please click on the website below to check if the registration has been successfully completed: 
<br /><a href="https://admin.tbsn.my/event/pstat/9" target="pstat">https://admin.tbsn.my/event/pstat/9</a>

                            </div>
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
                            <div class='col-xs-3 col-lg-4 strong_txt text-right'><font color='red'>*</font>Country:</div>
                            <div class='col-xs-9 col-lg-6 text-left form-row'>
                                <div class='form form-group'>
                                    <select required class='form-control' id='chapter_country' name='chapter_country'><?php foreach ($chapter_country as $c): ?>
                                    <option value="<?php echo $c; ?>"><?php echo $c; ?></option>
                                    <?php endforeach; ?></select>
                                </div>
                            </div>
                        </div>

                        <div class='row row-data'>
                            <div class='col-xs-3 col-lg-4 strong_txt text-right'><font color='red'>*</font>Cultivation Venue:</div>
                            <div class='col-xs-9 col-lg-6 text-left form-row'>
                                <div class='form form-group'>
                                    <select class='custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left ui-autocomplete-input' id='chapter_id' name='chapter_id'></select>
                                    <input type='text' class='form-control' id='chapter_name_other' name='chapter_name_other' style='display:none' placeholder="請填寫道場及國家" />
                                </div>
                            </div>
                        </div>

                        <div class='row row-data'>
                            <div class='col-xs-3 col-lg-4 strong_txt text-right'><font color='red'>*</font>Time Zone:</div>
                            <div class='col-xs-9 col-lg-6 text-left form-row'>
                                <div class='form form-group'>
                                    <select class='custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left' id='chapter_timezone' name='chapter_timezone'>
                                        <option value=''></option>
                                        <option value="1">Malaysia / Singapore / Brunei</option>
                                        <option value="2">Taiwan</option>
                                        <option value="3">Hong Kong</option>
                                        <option value="4">Tailand / Vietnam / Cambodia</option>
                                        <option value="5">Indonesia（West）</option>
                                        <option value="6">Indonesia（Central）</option>
                                        <option value="7">Indonesia（East）</option>
                                        <option value="8">Japan</option>
                                        <option value="9">Quam</option>
                                        <option value="10">Australia (South East)</option>
                                        <option value="11">Australia (Christmas Island)</option>
                                        <option value="12">Australia (Western)</option>
                                        <option value="13">New Zealand</option>
                                        <option value="14">Brazil (Sao Paulo)/(Fortaleza)</option>
                                        <option value="15">United States (West Coast)/Canada (BC)</option>
                                        <option value="16">United States (Mountain Time Zone)/Canada (AB Province)</option>
                                        <option value="17">United States (Central Time Zone)</option>
                                        <option value="18">United States (East Coast Time Zone)/Canada (ON/QC Province)/Panama</option>
                                        <option value="19">Puerto Rico/Dominican Republic</option>
                                        <option value="20">Hawaii</option>
                                        <option value="21">UK/Portugal</option>
                                        <option value="22">Europe-1: Sweden/Norway/Germany/Switzerland/France/Spain/Italy</option>
                                        <option value="23">Europe-2: Finland/Greece</option>
                                        <option value="24">South Africa</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class='row row-data'>
                            <div class='col-xs-3 col-lg-4 strong_txt text-right'><font color='red'>*</font>Session:</div>
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
                            <div class='col-xs-3 col-lg-4 strong_txt text-right'><font color='red'>*</font>Cultivation Venue Principal:</div>
                            <div class='col-xs-9 col-lg-6 text-left form-row'>
                                <div class='form form-group'>
                                    <input type='text' name='chapter_pic' class='form-control' placeholder="Cultivation Venue Principal" required>
                                  </div>
                            </div>
                        </div>

                        <div class='row row-data'>
                            <div class='col-xs-3 col-lg-4 strong_txt text-right'><font color='red'>*</font>Cultivation Venue Email:</div>
                            <div class='col-xs-9 col-lg-6 text-left form-row'>
                                <div class='form form-group'>
                                    <input type='email' name='chapter_email' class='form-control' placeholder="Cultivation Venue Email" required>
                                  </div>
                            </div>
                        </div>

                        <div class='row row-data'>
                            <div class='col-xs-3 col-lg-4 strong_txt text-right'><font color='red'>*</font>Ceremony Presider</div>
                            <div class='col-xs-9 col-lg-6 text-left'>
                                <div class='form form-group'>
                                    <input type='text' name='master_name' class='form-control' placeholder="Example: Master Shi Liana, Dharma Instructor Shi Lianb" required>
                                </div>
                            </div>
                        </div>

                        <div class='row row-data'>
                            <div class='col-xs-3 col-lg-4 strong_txt text-right'>Ceremony Supporter(s)</div>
                            <div class='col-xs-9 col-lg-6 text-left'>
                                <div class='form form-group'>
                                    <input type='text' name='join_personnel' class='form-control' placeholder="Can enter multiple propagators, example: Master Shi Liana, Dharma Instructor Shi Lianb, Dharma Assistant Lianhuac">
                                </div>
                            </div>
                        </div>

                        <div class='row row-data'>
                            <div class='col-xs-12 col-lg-10'>
                                <div class='form form-group text-right'>
                                    <button id="btn-check" class="btn btn-success">
                                        Submit
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
        // 10  :5 , // June Event
        10  :16, // December Event
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
            // June Event
            //"6":   eventDesc + "（Local Time：" + addHours(myDate,  5) + "）",
            //"16":  eventDesc + "（Local Time：" + addHours(myDate,  3) + "）",
            //"5":   eventDesc + "（Local Time：" + addHours(myDate,  2) + "）",
            //"3":   eventDesc + "（Local Time：" + addHours(myDate,  1) + "）",
            //"2":   eventDesc + "（Local Time：" + addHours(myDate,  0) + "）",
            //"4":   eventDesc + "（Local Time：" + addHours(myDate,- 1) + "）",
            //"15":  eventDesc + "（Local Time：" + addHours(myDate,- 5) + "）",
            //"14":  eventDesc + "（Local Time：" + addHours(myDate,- 6) + "）",
            //"13":  eventDesc + "（Local Time：" + addHours(myDate,- 7) + "）",
            //"7":   eventDesc + "（Local Time：" + addHours(myDate,-11) + "）",
            //"11":  eventDesc + "（Local Time：" + addHours(myDate,-12) + "）",
            //"10":  eventDesc + "（Local Time：" + addHours(myDate,-13) + "）",
            //"9":   eventDesc + "（Local Time：" + addHours(myDate,-14) + "）",
            //"8":   eventDesc + "（Local Time：" + addHours(myDate,-15) + "）",
            //"12":  eventDesc + "（Local Time：" + addHours(myDate,-18) + "）",

            // December Event
            "6":   eventDesc + "（Local Time：" + addHours(myDate,  4) + "）",
            "16":  eventDesc + "（Local Time：" + addHours(myDate,  3) + "）",
            "5":   eventDesc + "（Local Time：" + addHours(myDate,  2) + "）",
            "3":   eventDesc + "（Local Time：" + addHours(myDate,  1) + "）",
            "2":   eventDesc + "（Local Time：" + addHours(myDate,  0) + "）",
            "4":   eventDesc + "（Local Time：" + addHours(myDate,- 1) + "）",
            "15":  eventDesc + "（Local Time：" + addHours(myDate,- 6) + "）",
            "14":  eventDesc + "（Local Time：" + addHours(myDate,- 7) + "）",
            "13":  eventDesc + "（Local Time：" + addHours(myDate,- 8) + "）",
            "7":   eventDesc + "（Local Time：" + addHours(myDate,-11) + "）",
            "11":  eventDesc + "（Local Time：" + addHours(myDate,-13) + "）",
            "10":  eventDesc + "（Local Time：" + addHours(myDate,-14) + "）",
            "9":   eventDesc + "（Local Time：" + addHours(myDate,-15) + "）",
            "8":   eventDesc + "（Local Time：" + addHours(myDate,-16) + "）",
            "12":  eventDesc + "（Local Time：" + addHours(myDate,-19) + "）",
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