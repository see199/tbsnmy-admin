<?php

$event_1 = '1st ceremony: Mahabala Feast Offering';
$event_2 = '2nd ceremony: Jade Pond Golden Mother Water Offering';

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

              if(ui.item.option.attributes[0].nodeValue == 'Not in the list'){
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
          .attr( "title", "Entering：'" + value + "' Invalid，Please choose from the list。" )
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
                            <h2 class='text-center'>Celebrating Guru Buddha’s Birthday in 2023 with Three Major Offerings<br /><br />Global Online Feast Offering and Water Offering “Pray for Guru Buddha to Stay in the World" Ceremonies</h2>
                            <br /><img style='width: 100%; max-width:920px;' src='https://en.tbsn.org/images/2023_05_08/mahabala.jpg' />
                            <br /><br /><div class='text-justify'>The True Buddha Foundation is joining hands with the True Buddha School Masters, Reverends, all-level Dharma propagating personnel, and worldwide cultivation venues to perform Mahabala Feast Offering Ceremony, Jade Pond Golden Mother Water Offering Ceremony, and Amitabha Buddha Feast Offering Ceremony to sincerely and wholeheartedly “pray for the Guru Buddha to stay in the world” and dedicate the merit to the health and well-being of Grandmaster, his long life, being unhindered, staying long in the world, forever turning the Dharma wheel, and benefitting infinite sentient beings.
<br />
<br />Cultivation venues can select a convenient time to participate via Zoom in the feast offering and water offering ceremonies supplicating Guru Buddha to stay in the world and collectively turn the Dharma wheel. The ceremonies will be held at three different times as below.
<br />
<br />1st ceremony: Mahabala Feast Offering
<br />Taiwan time: Sat., June 24, 2023, 3:00PM
<br />Seattle USA time: Sat., June 24, 2023, 12:00AM (midnight)
<br />
<br />2nd ceremony: Jade Pond Golden Mother Water Offering
<br />Taiwan time: Sat., June 24, 2023, 7:00PM
<br />Seattle USA time: Sat., June 24, 2023 4:00AM
<br />
<br />3rd ceremony: Amitabha Feast Offering
<br />Taiwan time: Sun., June 25, 2023, 1:00AM
<br />Seattle USA time: Sat., June 24, 2023, 10:00AM
<br />
<br />#Zoom ID and password will be forwarded to participants via email. Submit your registration form after you have confirmed the email address is correct.</div>
                        </div>


                        <?php if (isset($msg) && $msg != ""): ?>
                        <div class='row'>
                            <div class="alert alert-success" role="alert"><?php echo $msg; ?></div>
                        </div>
                        <?php endif;?>

                        <hr />
                    </div>

                    <div class='col-xs-11'>
                    <form class='form-horizontal' method="post" action="<?= base_url('event/register/'.$event['event_id']);?>/en">
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
                            <div class='col-xs-3 col-lg-4 strong_txt text-right'><font color='red'>*</font>Ceremony:</div>
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
                            <div class='col-xs-3 col-lg-4 strong_txt text-right'><font color='red'>*</font>Principal of Cultivation Venue:</div>
                            <div class='col-xs-9 col-lg-6 text-left form-row'>
                                <div class='form form-group'>
                                    <input type='text' name='chapter_pic' class='form-control' placeholder="Principal of Cultivation Venue" required>
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
                                    <input type='text' name='master_name' class='form-control' placeholder="Examples：Master Shi Lianx, Dharma Assistant Lianhuaxx" required>
                                </div>
                            </div>
                        </div>

                        <div class='row row-data'>
                            <div class='col-xs-3 col-lg-4 strong_txt text-right'>Supporter(s)</div>
                            <div class='col-xs-9 col-lg-6 text-left'>
                                <div class='form form-group'>
                                    <input type='text' name='join_personnel' class='form-control' placeholder="Can be multiple. Examples：Master Shi Lianx, Dharma Assistant Lianhuaxx">
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
        1   :2 ,
        2   :2 ,
        3   :2 ,
        4   :4 ,
        5   :4 ,
        6   :2 ,
        7   :3 ,
        8   :3 ,
        9   :5 ,
        10  :5 ,
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
        "2":   "<?= $event_1;?> (Local time: 11/25  3PM) ",
        "3":   "<?= $event_1;?> (Local time: 11/25  4PM) ",
        "4":   "<?= $event_1;?> (Local time: 11/25  2PM) ",
        "5":   "<?= $event_1;?> (Local time: 11/25  5PM) ",
        "6":   "<?= $event_1;?> (Local time: 11/25  7PM) ",
        "7":   "<?= $event_1;?> (Local time: 11/25  4AM) ",
        "8":   "<?= $event_1;?> (Local time: 11/25  0AM) ",
        "9":   "<?= $event_1;?> (Local time: 11/25  1AM) ",
        "10":  "<?= $event_1;?> (Local time: 11/25  2AM) ",
        "11":  "<?= $event_1;?> (Local time: 11/25  3AM) ",
        "12":  "<?= $event_1;?> (Local time: 11/24  9PM) ",
        "13":  "<?= $event_1;?> (Local time: 11/25  8AM) ",
        "14":  "<?= $event_1;?> (Local time: 11/25  9AM) ",
        "15":  "<?= $event_1;?> (Local time: 11/25 10AM) "
    }

    const timezone_list_2 = {
        "2":   "<?= $event_2;?> (Local time: 11/26  1AM) ",
        "3":   "<?= $event_2;?> (Local time: 11/26  2AM) ",
        "4":   "<?= $event_2;?> (Local time: 11/26  0AM) ",
        "5":   "<?= $event_2;?> (Local time: 11/26  3AM) ",
        "6":   "<?= $event_2;?> (Local time: 11/26  5AM) ",
        "7":   "<?= $event_2;?> (Local time: 11/25  2PM) ",
        "8":   "<?= $event_2;?> (Local time: 11/25 10AM) ",
        "9":   "<?= $event_2;?> (Local time: 11/25 11AM) ",
        "10":  "<?= $event_2;?> (Local time: 11/25 12PM) ",
        "11":  "<?= $event_2;?> (Local time: 11/25  1PM) ",
        "12":  "<?= $event_2;?> (Local time: 11/25  7AM) ",
        "13":  "<?= $event_2;?> (Local time: 11/25  6PM) ",
        "14":  "<?= $event_2;?> (Local time: 11/25  7PM) ",
        "15":  "<?= $event_2;?> (Local time: 11/25  8PM) "
    }

    const chapter_timezone = $('#chapter_timezone');
    chapter_timezone.on('change',updateLabel);
    function updateLabel(){
        $('#label_type_1').html(timezone_list_1[timezone_mapping[chapter_timezone.val()]]);
        $('#label_type_2').html(timezone_list_2[timezone_mapping[chapter_timezone.val()]]);
        $('#label_type_3').html(timezone_list_3[timezone_mapping[chapter_timezone.val()]]);
    }


      
  </script>
</html>