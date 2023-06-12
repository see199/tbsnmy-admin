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

              if(ui.item.option.attributes[0].nodeValue == 'Not in list'){
                $('#chapter_name_other').attr('style','display:block');
                $('#chapter_id').attr('style','display:none');
                $('#chapter_id').html("");
                $("#chapter_id" ).combobox();
                $('#chapter_id').siblings().first().hide();

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
          .attr( "title", "Enter：'" + value + "'invalid. Please select again." )
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
                            <h2 class='text-center'>True Buddha Foundation (TBF) Menggelar Kegiatan Doa bagi Mahaguru</h2>
                            <br /><img style='width: 100%' src='https://id.tbsn.org/images/2023_04_24/1682304115154.jpg' />
                            <br /><br /><div class='text-justify'>20 April 2023 s.d. 20 Juni 2023, Segenap siswa Zhenfo Zong di seluruh dunia merapal Mantra Hati Vajra Mahabala (Dali Jin'gang/大力金剛) menyalurkan jasa Mahaguru sehat sentosa.
<br />Memohon Vajra Mahabala dengan daya agung tanpa rintangan melindungi Mahaguru: "Dharmapala silsilah melindungi Zhenfo, Menampilkan wibawa menaklukkan Catur Mara,
<br />Sehat sentosa penuh daya, Senantiasa leluasa, Senantiasa menetap di dunia, 
<br />Senantiasa memutar Cakra Dharma, Arus silsilah Dharma tidak berkesudahan."
<br />
<br /><b>Kegiatan Gema Mantra Mahabala - Doa bagi Mahaguru Tahun 2023</b>
<br />Mulai 20 April 2023 s.d. 20 Juni 2023
<br />Tempat ibadah di seluruh dunia memandu Sadhana Vajra Mahabala, menyalurkan jasa bagi Mahaguru. (Apabila Tempat ibadah telah mengatur upacara atau pujabakti, dihimbau untuk menambahkan Mantra Hati Vajra Mahabala 108 kali dalam tata ritual.)
<br />
<br />Menurut Peraturan Acarya dalam AD/ART Zhenfo Zong, Sadhana Vajra Mahabala tergolong dalam Mahasadhana Vajra Avenika, setelah Mulacarya Liansheng mentransmisikan sadhana ini secara terbuka, hanya Acarya Zhenfo Zong yang telah menerima abhiseka sadhana ini baru bisa memimpin pujabakti, puja, homa dan upacara Vajra Mahabala. 
<br />
<br />Oleh karena itu, berkat permohonan khusus dari True Buddha Foundation (TBF), pada tanggal 18 April 2023, Mulacarya Liansheng telah menganugerahkan adhisthana bagi kegiatan kali ini, dan memberikan otorisasi istimewa kepada segenap Acarya Zhenfo Zong di seluruh dunia sehingga dapat memimpin Upacara Homa Vajra Mahabala, sedangkan bagi segenap Dharmaduta (semua level) dan pengurus tempat ibadah, mendapatkan otorisasi untuk memimpin pujabakti Sadhana Vajra Mahabala (Dali Jingang Niansongfa/大力金剛念誦法). 
<br />
<br />Ini merupakan anugerah yang sangat istimewa, demi menghormati Guru dan menghargai Dharma, TBF mengatur supaya tempat ibadah bisa mendaftarkan nama Dharmaduta (semua level) dan pengurus tempat ibadah yang memimpin puja dan penjapaan Vajra Mahabala, dan secara berkala memohon Mulacarya Liansheng untuk mengadhisthana menganugerahkan otoritas istimewa untuk memimpin puja dan penjapaan Vajra Mahabala.
<br />
<br />Mohon Tempat Ibadah dan Para Dharmaduta segera mendaftarkan tanggal dan nama pemandu kebaktian di link berikut ini:
<br /><a href="https://ch.tbsn.org/page/index/全球道場同修迴向.html">https://ch.tbsn.org/page/index/全球道場同修迴向.html</a>
<br />
<br />Silahkan unduh tata ritual di link berikut ini:
<br /><a href="https://ch.tbsn.org/uploads/rulelist/2023_04_23/大力金剛修法儀軌_20230420A.pdf">https://ch.tbsn.org/uploads/rulelist/2023_04_23/大力金剛修法儀軌_20230420A.pdf</a>
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
                    <form class='form-horizontal' method="post" action="<?= base_url('event/register/'.$event['event_id'].'/id');?>">
                        <input type='hidden' name='event_id' value='<?=$event['event_id'];?>' />
                        <input type='hidden' id='chapter_name' name='chapter_name' />
                        <input type='hidden' name='event_date[1]' value='0000-00-00' class='form-control' >

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
                            <div class='col-xs-3 col-lg-4 strong_txt text-right'><font color='red'>*</font>Pemimpin Puja:</div>
                            <div class='col-xs-9 col-lg-6 text-left form-row'>
                                <div class='form form-group col-md-6'>
                                    <input type='text' name='master_name[1]' class='form-control' placeholder="Pemimpin Puja" required>
                                  </div>
                                  <div class='form form-group col-md-6'>
                                    <input type='text' name='master_position[1]' class='form-control' placeholder="Jabatan: Biksu/Biksuni/Pandita Dharmaduta/Pandita Lokapalasraya/Anggota Dharmabakti Tempat Ibadah" required>
                                </div>
                            </div>
                        </div>

                        <div class='row row-data'>
                            <div class='col-xs-3 col-lg-4 strong_txt text-right'><font color='red'>*</font>Jumlah Pujabakti:</div>
                            <div class='col-xs-9 col-lg-6 text-left'>
                                <div class='form form-group'>
                                    <input type='number' name='event_counter' class='form-control' required>
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
</html>