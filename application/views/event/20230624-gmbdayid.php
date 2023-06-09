<?php

$event_1 = 'Mahapuja Pertama: Trimulapuja Vajra Mahabala';
$event_2 = 'Mahapuja Kedua: Argampuja Mahadewi Yaochi';
$event_3 = 'Mahapuja Ketiga: Trimulapuja Buddha Amitabha';

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
                            <h2 class='text-center'>Tri Mahapuja Hari Jadi Mulacarya Tahun 2023 </h2>
                            <br /><img style='width: 100%' src='https://id.tbsn.org/images/2023_04_24/1682304115154.jpg' />
                            <br /><br /><div class='text-justify'>True Buddha Foundation (TBF) bergandengan tangan dengan segenap Acarya Zhenfo Zong, biksu/biksuni, segenap Dharmaduta, dan semua tempat ibadah Zhenfo di seluruh dunia, untuk menyelenggarakan Upacara Trimulapuja Vajra Mahabala, Upacara Agung Argampuja Mahadewi Yaochi, dan Upacara Agung Trimulapuja Buddha Amitabha, dengan tulus satu hati Mohon Buddha Menetap di Dunia, menyalurkan jasa semoga Mulacarya Liansheng sehat sentosa, panjang umur dan leluasa, senantiasa memutar cakra Dharma, memberi manfaat kepada semua makhluk yang tak terhingga banyaknya. 
<br />
<br />Semasa hidup, Je Tsongkapa mengalami tiga rintangan besar usia, demi menyingkirkan rintangan tersebut, Je Tsongkapa menyelenggarakan tiga kali Mahatrimulapuja. Tiap Trimulapuja mempersembahkan sarana puja yang sangat banyak. Setelah Tiga Mahatrimulapuja sempurna, rintangan usia pun tersingkirkan, mencapai keberhasilan panjang umur dan leluasa, sehingga memiliki lebih banyak waktu untuk memutar cakra Dharma, membabarkan Dharma memberi manfaat kepada lebih banyak makhluk. 
<br />
<br />Argampuja dan Homa pada dasarnya sama-sama merupakan persembahan yang paling nyata. Hanya saja, Argampuja mengundang Dewa Waruna untuk mengantarkan persembahan kepada Sarwa Buddha, Bodhisatwa, dan Dewata, pahala yang dihasilkan sangat luas, dapat dilakukan untuk tolak bala, meningkatkan berkah, memperoleh keharmonisan, penyembuhan, kesehatan, menaklukkan teluh, dan lain-lain. 
<br />
<br />Upacara Agung Mohon Buddha Menetap di Dunia terdiri dari Trimulapuja dan Argampuja, dan dibagi menjadi tiga waktu. Semua tempat ibadah di seluruh dunia dapat memilih waktu yang sesuai dengan wilayah masing-masing untuk berpartisipasi dalam upacara, bersama masuk Zoom untuk memutar cakra Dharma. 
<br />
<br />Mahapuja Pertama: Trimulapuja Vajra Mahabala
<br />Sabtu, 24 Juni 2023, pukul 14:00 WIB
<br />Sabtu, 24 Juni 2023, pukul 00:00 Waktu Seattle Amerika Serikat 
<br />
<br />Mahapuja Kedua: Argampuja Mahadewi Yaochi
<br />Sabtu, 24 Juni 2023, pukul 18:00 WIB
<br />Sabtu, 24 Juni 2023, pukul 04:00 pagi Waktu Seattle Amerika Serikat 
<br />
<br />Mahapuja Ketiga: Trimulapuja Buddha Amitabha
<br />Minggu, 25 Juni 2023, pukul 00:00 WIB
<br />Sabtu, 24 Juni 2023, pukul 10:00 pagi Waktu Seattle Amerika Serikat 
<br />
<br />Zoom ID dan kata sandi akan dikirim ke surel, harap pastikan terlebih dahulu ketepatan alamat surel yang Anda tulis, baru kemudian menyerahkan formulir. </div>
                        </div>


                        <?php if (isset($msg) && $msg != ""): ?>
                        <div class='row'>
                            <div class="alert alert-success" role="alert"><?php echo $msg; ?></div>
                        </div>
                        <?php endif;?>

                        <hr />
                    </div>

                    <div class='col-xs-11'>
                    <form class='form-horizontal' method="post" action="<?= base_url('event/register/'.$event['event_id']);?>/id">
                        <input type='hidden' name='event_id' value='<?=$event['event_id'];?>' />
                        <input type='hidden' id='chapter_name' name='chapter_name' />

                        <div class='row'>&nbsp;</div>

                        <div class='row row-data'>
                            <div class='col-xs-3 col-lg-4 strong_txt text-right'><font color='red'>*</font>Negara:</div>
                            <div class='col-xs-9 col-lg-6 text-left form-row'>
                                <div class='form form-group'>
                                    <select required class='form-control' id='chapter_country' name='chapter_country'><?php foreach ($chapter_country as $c): ?>
                                    <option value="<?php echo $c; ?>"><?php echo $c; ?></option>
                                    <?php endforeach; ?></select>
                                </div>
                            </div>
                        </div>

                        <div class='row row-data'>
                            <div class='col-xs-3 col-lg-4 strong_txt text-right'><font color='red'>*</font>Tempat Ibadah:</div>
                            <div class='col-xs-9 col-lg-6 text-left form-row'>
                                <div class='form form-group'>
                                    <select class='custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left ui-autocomplete-input' id='chapter_id' name='chapter_id'></select>
                                    <input type='text' class='form-control' id='chapter_name_other' name='chapter_name_other' style='display:none' placeholder="請填寫道場及國家" />
                                </div>
                            </div>
                        </div>

                        <div class='row row-data'>
                            <div class='col-xs-3 col-lg-4 strong_txt text-right'><font color='red'>*</font>Waktu Wilayah:</div>
                            <div class='col-xs-9 col-lg-6 text-left form-row'>
                                <div class='form form-group'>
                                    <select class='custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left' id='chapter_timezone' name='chapter_timezone'>
                                        <option value=''></option>
                                        <option value="5">Indonesia（West）</option>
                                        <option value="6">Indonesia（Central）</option>
                                        <option value="7">Indonesia（East）</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class='row row-data'>
                            <div class='col-xs-3 col-lg-4 strong_txt text-right'><font color='red'>*</font>Waktu Bagian:</div>
                            <div class='col-xs-9 col-lg-6 text-left form-row'>
                                <div class='form form-group'>
                                    <input type="radio" id="event_type_1" name="event_type" value="1">
                                    <label for="event_type_1" id="label_type_1"><?= $event_1;?></label><br>
                                    <input type="radio" id="event_type_2" name="event_type" value="2">
                                    <label for="event_type_2" id="label_type_2"><?= $event_2;?></label><br>
                                    <input type="radio" id="event_type_3" name="event_type" value="3">
                                    <label for="event_type_3" id="label_type_3"><?= $event_3;?></label>
                                </div>
                            </div>
                        </div>

                        <div class='row row-data'>
                            <div class='col-xs-3 col-lg-4 strong_txt text-right'><font color='red'>*</font>Penanggung Jawab Tempat Ibadah:</div>
                            <div class='col-xs-9 col-lg-6 text-left form-row'>
                                <div class='form form-group'>
                                    <input type='text' name='chapter_pic' class='form-control' placeholder="Principal of Cultivation Venue" required>
                                  </div>
                            </div>
                        </div>

                        <div class='row row-data'>
                            <div class='col-xs-3 col-lg-4 strong_txt text-right'><font color='red'>*</font>Surel Tempat Ibadah:</div>
                            <div class='col-xs-9 col-lg-6 text-left form-row'>
                                <div class='form form-group'>
                                    <input type='email' name='chapter_email' class='form-control' placeholder="Cultivation Venue Email" required>
                                  </div>
                            </div>
                        </div>

                        <div class='row row-data'>
                            <div class='col-xs-3 col-lg-4 strong_txt text-right'><font color='red'>*</font>Upacarika</div>
                            <div class='col-xs-9 col-lg-6 text-left'>
                                <div class='form form-group'>
                                    <input type='text' name='master_name' class='form-control' placeholder="Examples：Master Shi Lianx, Dharma Assistant Lianhuaxx" required>
                                </div>
                            </div>
                        </div>

                        <div class='row row-data'>
                            <div class='col-xs-3 col-lg-4 strong_txt text-right'>Didampingi oleh</div>
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
                                        Daftarkan
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
        "2":   "<?= $event_1;?> 6/24  3PM",
        "3":   "<?= $event_1;?> 6/24  4PM",
        "4":   "<?= $event_1;?> 6/24  2PM",
        "5":   "<?= $event_1;?> 6/24  5PM",
        "6":   "<?= $event_1;?> 6/24  7PM",
        "7":   "<?= $event_1;?> 6/24  4AM",
        "8":   "<?= $event_1;?> 6/24  12AM(midnight)",
        "9":   "<?= $event_1;?> 6/24  1AM",
        "10":  "<?= $event_1;?> 6/24  2AM",
        "11":  "<?= $event_1;?> 6/24  3AM",
        "12":  "<?= $event_1;?> 6/23  9PM",
        "13":  "<?= $event_1;?> 6/24  8AM",
        "14":  "<?= $event_1;?> 6/24  9AM",
        "15":  "<?= $event_1;?> 6/24 10AM"
    }

    const timezone_list_2 = {
        "2":   "<?= $event_2;?> 6/24  7PM",
        "3":   "<?= $event_2;?> 6/24  8PM",
        "4":   "<?= $event_2;?> 6/24  6PM",
        "5":   "<?= $event_2;?> 6/24  9PM",
        "6":   "<?= $event_2;?> 6/24 11PM",
        "7":   "<?= $event_2;?> 6/24  8AM",
        "8":   "<?= $event_2;?> 6/24  4AM",
        "9":   "<?= $event_2;?> 6/24  5AM",
        "10":  "<?= $event_2;?> 6/24  6AM",
        "11":  "<?= $event_2;?> 6/24  7AM",
        "12":  "<?= $event_2;?> 6/24  1AM",
        "13":  "<?= $event_2;?> 6/24 12PM",
        "14":  "<?= $event_2;?> 6/24  1PM",
        "15":  "<?= $event_2;?> 6/24  2PM"
    }

    const timezone_list_3 = {
        "2":   "<?= $event_3;?> 6/25  1AM",
        "3":   "<?= $event_3;?> 6/25  2AM",
        "4":   "<?= $event_3;?> 6/25  12AM(midnight)",
        "5":   "<?= $event_3;?> 6/25  3AM",
        "6":   "<?= $event_3;?> 6/25  5AM",
        "7":   "<?= $event_3;?> 6/24  2PM",
        "8":   "<?= $event_3;?> 6/24 10AM",
        "9":   "<?= $event_3;?> 6/24 11AM",
        "10":  "<?= $event_3;?> 6/24 12PM",
        "11":  "<?= $event_3;?> 6/24  1PM",
        "12":  "<?= $event_3;?> 6/24  7AM",
        "13":  "<?= $event_3;?> 6/24  6PM",
        "14":  "<?= $event_3;?> 6/24  7PM",
        "15":  "<?= $event_3;?> 6/24  8PM"
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