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
                background-color: #FAE3E1;
            }
            .drop-shadow {
                -webkit-box-shadow: 0 0 5px 2px rgba(0, 0, 0, .5);
                box-shadow: 0 0 8px 3px rgba(0, 0, 0, .5);
            }
            .panel.drop-shadow {
                padding-left:0;
                padding-right:0;
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
            if(ui.item.option.parentElement.name == 'master_country'){
                $.ajax({
                    url: '<?= base_url();?>event/ajax_get_master_by_country',
                    type: 'post',
                    data: {country: ui.item.option.attributes[0].nodeValue},
                    success: function(data) {
                        res = JSON.parse(data);
                        var options = '';
                        for (var i = 0; i < res.length; i++) {
                            options += '<option value="' + res[i].value + '">' + res[i].text + '</option>';
                        }
                        $('#master_id').html(options);
                        $("#master_id" ).combobox();
                    }
                });
            }else if(ui.item.option.parentElement.name == 'master_id'){
                console.log(ui.item); // How to get the value?
                //$('#mySelect option:selected').text();
                $('#master_name').val();
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
          .attr( "title", "Show All Items" )
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
    $( "#master_country" ).combobox();
  });
  </script>
  <!-- Combo box JavaScript End -->



    </head>

<body>

    <div class="col-xs-12 col-sm-8 col-md-6 col-lg-6 col-sm-offset-2 col-md-offset-3 col-lg-offset-3">
        <br />
        <div class="panel panel-default drop-shadow">
            <div class="panel-body">
                <div class='row text-center'>
                    <div class='col-md-12' style='padding:30px;'>
                        <h2 class='text-center' style='color:#600'><?=str_replace("\r\n", "<br>", $event['title']);?></h2>
                        <div class='text-justify'><?= str_replace("\r\n", "<br>", $event['description']);?></div>
                    </div>

                    <?php if (isset($msg)): ?>
                        <div class="alert alert-success"><?php echo $msg; ?></div>
                    <?php endif;?>

                    <form class='form-horizontal' method="post" action="<?= base_url('event/register/'.$event['event_id']);?>">
                        <input type='hidden' name='event_id' value='<?=$event['event_id'];?>' />
                        <input type='hidden' name='master_name' />

                        <div class='row'>&nbsp;</div>

                        <div class='row row-data col-xs-12'>
                            <div class='col-xs-3 strong_txt text-right'>國家:</div>
                            <div class='col-xs-9 text-left'>
                                <div class='form form-group'>
                                    <select required class='form-control' id='master_country' name='master_country'><?php foreach ($master_country as $c): ?>
                                    <option value="<?php echo $c; ?>"><?php echo $c; ?></option>
                                    <?php endforeach; ?></select>
                                </div>
                            </div>
                        </div>

                        <div class='row row-data col-xs-12'>
                            <div class='col-xs-3 strong_txt text-right'>法號:</div>
                            <div class='col-xs-9 text-left'>
                                <div class='form form-group'>
                                    <select required class='custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left ui-autocomplete-input' id='master_id' name='master_id'></select>
                                </div>
                            </div>
                        </div>

                        <div class='row row-data col-xs-12'>
                            <div class='col-xs-3 strong_txt text-right'>日期:</div>
                            <div class='col-xs-9 text-left'>
                                <div class='form form-group'>
                                    <input type='date' name='event_date' class='form-control' required>
                                </div>
                            </div>
                        </div>

                        <div class='row row-data col-xs-12'>
                            <div class='col-xs-3 strong_txt text-right'>法會:</div>
                            <div class='col-xs-9 text-left'>
                                <div class='form form-group'>
                                    <select required class='form-control' id='event_type' name='event_type'>
                                    <option value="火供">護摩火供</option>
                                    <option value="水供">水供</option>
                                    <option value="薈供">薈供</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class='row row-data col-xs-12'>
                            <div class='col-xs-3 strong_txt text-right'>道場:</div>
                            <div class='col-xs-9 text-left'>
                                <div class='form form-group'>
                                    <input type='text' name='chapter_name' class='form-control'>
                                </div>
                            </div>
                        </div>


                        <div class='row row-data col-xs-10 col-xs-offset-1'>
                            <div class='col-xs-2 col-xs-offset-1'></div>
                            <div class='col-xs-12'>
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
        </div>
    </div>

</body>
</html>