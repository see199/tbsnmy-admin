<?php
$event_list = array(
    array('Sesi 01: ',"2026-06-13 10:00AM"),
    array('Sesi 02: ',"2026-06-13 02:00PM"),
    array('Sesi 03: ',"2026-06-13 05:00PM"),
    array('Sesi 04: ',"2026-06-13 08:00PM"),
    array('Sesi 05: ',"2026-06-14 01:00AM"),
);
?>
<!DOCTYPE html>
<html lang="id">

<head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?= $event['title'];?></title>

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">

        <link href="<?= base_url(); ?>assets/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

        <link href="<?= base_url(); ?>assets/css/sb-admin-2.css" rel="stylesheet">

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

        <script src="<?= base_url(); ?>assets/js/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
        <script type="text/javascript" language="javascript" src="<?= base_url(); ?>assets/js/jquery.js"></script>
        <script src="//code.jquery.com/jquery-1.10.2.js"></script>
        <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
        
        <script src="https://cdn.jsdelivr.net/npm/luxon@3.4.4/build/global/luxon.min.js"></script>


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
                width: 100%;
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

              if(ui.item.option.attributes[0].nodeValue == '不在名單內' || ui.item.option.attributes[0].nodeValue == 'Other'){
                $('#chapter_name_other').attr('style','display:block');
                $('#chapter_id').attr('style','display:none');
                $('#chapter_id').html("");
                $("#chapter_id" ).combobox();
                $('#chapter_id').siblings().first().hide()

              }else{
                $.ajax({
                    url: '<?= base_url();?>event/ajax_get_chapter_by_country',
                    type: 'post',
                    data: {country: ui.item.option.attributes[0].nodeValue, lang: 'id'},
                    success: function(data) {
                        res = JSON.parse(data);
                        var options = '';
                        for (var i = 0; i < res.length; i++) {
                            options += '<option value="' + res[i].value + '" data-name-cn="' + (res[i].name_cn || '') + '">' + res[i].text + '</option>';
                        }
                        $('#chapter_id').html(options);
                        $("#chapter_id" ).combobox();
                    }
                });
              }
            }else if(ui.item.option.parentElement.name == 'chapter_id'){
                var nameCn = $(ui.item.option).attr('data-name-cn');
                var nameLocal = ui.item.value;
                if (nameCn && nameLocal && nameCn !== nameLocal) {
                    $('#chapter_name').val(nameCn + ' (' + nameLocal + ')');
                } else {
                    $('#chapter_name').val(nameCn ? nameCn : nameLocal);
                }

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
          .attr( "title", "Input: '" + value + "' tidak valid, silakan klik untuk memilih." )
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
  </head>

<body>

    <div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-sm-offset-1 col-md-offset-1 col-lg-offset-1">
        <br />
                <div class='row text-center'>
                    <div class='col-lg-10 col-lg-offset-1 col-xs-10 col-xs-offset-1'>

                        <div class='row'>
                            <h2 class='text-center'>Link Pendaftaran Upacara Trimula Puja Memohon Buddha Menetap di Dunia dalam Rangka Hari Ulang Tahun Mahaguru, 13 Juni 2026</h2>
                            <br /><img style='width: 100%; max-width:920px;' src='<?=$event['banner_url'];?>' />
                            <br /><br /><div class='text-justify'>
<br />Upacara Trimula Puja Memohon Buddha Menetap di Dunia dalam Rangka Hari Ulang Tahun Mahaguru, 13 Juni 2026
<br />
<br />Saat ini diatur menjadi 5 sesi, yang akan dipandu oleh 5 tempat ibadah secara bergantian melalui Zoom dan YouTube.
<br />
<br />Tempat ibadah yang memandu dan waktu pelaksanaan adalah sebagai berikut:
<br />
<br />Sesi 1: Taiwan Lei Tsang Temple (Waktu Taiwan: 10:00 - 12:00)
<br />Sesi 2: Chung Kuan Lei Tsang Temple (Waktu Taiwan: 14:00 - 16:00)
<br />Sesi 3: Vihara Vajra Bumi Nusantara (Waktu Taiwan: 17:00 - 19:00 / Waktu Indonesia Barat [WIB]: 16:00 - 18:00)
<br />Sesi 4: Federal Tantric Buddhism Chen Foh Chong Malaysia (Waktu Taiwan: 20:00 - 22:00)
<br />Sesi 5: Seattle Ling Shen Ching Tze Temple (Waktu Seattle: 10:00 - 12:00 / Waktu Taiwan: 14 Juni, pukul 01:00 - 03:00 dini hari)
<br />
<br />Undangan Tulus:
<br />Kami mengundang dengan hormat setiap tempat ibadah dan seluruh siswa Zhenfo Zong di seluruh dunia, untuk bersama-sama melakukan penjapaan mantra dan penyaluran jasa secara sinkron via cloud (online).
                            </div>
                        </div>


                        <?php if (isset($msg) && $msg != ""): ?>
                        <div class='row'>
                            <div class="alert alert-success" role="alert"><?php echo $msg; ?></div>
                        </div>
                        <?php endif;?>

                        <hr />
                    </div>

                    <div class='col-xs-11' class="due-date">
                    <form class="due-date" style='z-index: 1;position: relative;' class='form-horizontal' method="post" action="<?= base_url('event/register/'.$event['event_id']);?>/id">
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
                                    <input type='text' class='form-control' id='chapter_name_other' name='chapter_name_other' style='display:none' placeholder="Silakan isi nama tempat ibadah dan negara" />
                                </div>
                            </div>
                        </div>

                        <div class='row row-data'>
                            <div class='col-xs-3 col-lg-4 strong_txt text-right'><font color='red'>*</font>Waktu Wilayah:</div>
                            <div class='col-xs-9 col-lg-6 text-left form-row'>
                                <div class='form form-group'>
                                    <select class='custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left' id='chapter_timezone' name='chapter_timezone'>
                                        <option value=''></option>
                                        <option value='Asia/Jakarta'>Indonesia（Barat / WIB）</option>
                                        <option value='Asia/Makassar'>Indonesia（Tengah / WITA）</option>
                                        <option value='Asia/Jayapura'>Indonesia（Timur / WIT）</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class='row row-data'>
                            <div class='col-xs-3 col-lg-4 strong_txt text-right'><font color='red'>*</font>Waktu Bagian:</div>
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
                            <div class='col-xs-3 col-lg-4 strong_txt text-right'><font color='red'>*</font>Penanggung Jawab Tempat Ibadah:</div>
                            <div class='col-xs-9 col-lg-6 text-left form-row'>
                                <div class='form form-group'>
                                    <input type='text' name='chapter_pic' class='form-control' placeholder="Penanggung Jawab Tempat Ibadah" required>
                                  </div>
                            </div>
                        </div>

                        <div class='row row-data'>
                            <div class='col-xs-3 col-lg-4 strong_txt text-right'><font color='red'>*</font>Surel Tempat Ibadah:</div>
                            <div class='col-xs-9 col-lg-6 text-left form-row'>
                                <div class='form form-group'>
                                    <input type='email' name='chapter_email' class='form-control' placeholder="Surel Tempat Ibadah" required>
                                  </div>
                            </div>
                        </div>

                        <div class='row row-data'>
                            <div class='col-xs-3 col-lg-4 strong_txt text-right'><font color='red'>*</font>Upacarika</div>
                            <div class='col-xs-9 col-lg-6 text-left'>
                                <div class='form form-group'>
                                    <input type='text' name='master_name' class='form-control' placeholder="Contoh: Vajracarya Shi Lianxx, Asisten Dharma Lianhuaxx" required>
                                </div>
                            </div>
                        </div>

                        <div class='row row-data'>
                            <div class='col-xs-3 col-lg-4 strong_txt text-right'>Didampingi oleh</div>
                            <div class='col-xs-9 col-lg-6 text-left'>
                                <div class='form form-group'>
                                    <input type='text' name='join_personnel' class='form-control' placeholder="Bisa beberapa orang. Contoh: Vajracarya Shi Lianxx, Asisten Dharma Lianhuaxx">
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
                    <div class="due-date-div"></div>
                    </div>
    </div>
</body>

  <script type="text/javascript">

    // Luxon is available globally as 'luxon.DateTime'
    const DateTime = luxon.DateTime;

    // The original event times are fixed to a *reference* timezone (assumed to be Taiwan based on the original offsets)
    const REFERENCE_TIMEZONE = "Asia/Taipei";

    const timezone_list = [
        <?php foreach($event_list as $k => $v): ?>
        {
          desc: "<?=$v[0];?>",
          time: "<?=$v[1];?>" // e.g., "2026-01-01 10:00AM"
        },
        <?php endforeach; ?>
    ];

    const chapter_timezone = $('#chapter_timezone');
    chapter_timezone.on('change', updateLabel);

    // Run once on load if a default value is present (or after an AJAX update)
    if (chapter_timezone.val()) {
        updateLabel();
    }

    function updateLabel() {
        const targetTimezone = chapter_timezone.val();

        if (!targetTimezone) {
            // Clear labels if no timezone is selected (show only original event description)
            $.each(timezone_list, function(index) {
                $('#label_type_' + (index + 1)).html(this.desc);
            });
            return;
        }

        $.each(timezone_list, function(index, eventItem) {
            const id = index + 1;
            const eventDesc = eventItem.desc;
            const originalTime = eventItem.time; 
            
            // 1. Parse the original time string, explicitly setting it in the REFERENCE_TIMEZONE
            const referenceDateTime = DateTime.fromFormat(
                originalTime,
                "yyyy-MM-dd hh:mma", // Matches the format in $event_list
                { zone: REFERENCE_TIMEZONE }
            );

            // 2. Convert the reference time to the target timezone (handles DST automatically)
            // This will return an Invalid DateTime object if the timezone is unknown.
            const convertedDateTime = referenceDateTime.setZone(targetTimezone);

            let formattedTime = '';
            
            if (convertedDateTime.isValid) {
                // 3. Format the time for display (e.g., 01/01 10:00AM)
                formattedTime = convertedDateTime.toFormat('MM/dd h:mma');
                
                // Update the label with converted time
                $('#label_type_' + id).html(
                    eventDesc + "（Waktu Setempat: " + formattedTime + "）"
                );
            } else {
                // Keep the original text if conversion fails (e.g., invalid timezone ID)
                $('#label_type_' + id).html(eventDesc + "（Gagal konversi zona waktu）");
            }
        });
    }

  </script>
</html>
