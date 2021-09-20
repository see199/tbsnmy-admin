<style>
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
            if(ui.item.option.parentElement.name == 'chapter_load')
            window.document.location.href='<?=base_url('admin/index/update_default_chapter/');?>'+ui.item.option.attributes[0].nodeValue;
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
          .attr( "title", value + " didn't match any item" )
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
    $( "#select_chapter" ).combobox();
    $( "#select_master" ).combobox();
  });
  </script>
<!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?= base_url(); ?>admin/index"><?= lang('glb_nav_title'); ?></a>
            </div>
            <!-- /.navbar-header -->

            
            <!-- /.navbar-top-links -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
              <ul class="nav navbar-nav">
                <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><?= lang('glb_nav_menu'); ?><span class="caret"></span></a>
                  <ul class="dropdown-menu" role="menu">
                    <li>
                        <a href="<?= base_url(); ?>admin/index"><i class="fa fa-dashboard fa-fw"></i> <?= lang('glb_nav_dashboard'); ?></a>
                    </li>
                    
                    <?php if($chapter_allowed[0] == 'all'): ?>
                    <li>
                        <a href="<?= base_url(); ?>admin/tbnews"><i class="fa fa-newspaper-o fa-fw"></i> <?= lang('glb_nav_tbnews'); ?></a>
                    </li>
                    <li>
                        <a href="<?= base_url(); ?>admin/game"><i class="fa fa-gamepad "></i> Games</a>
                    </li>
                    <?php endif; ?>
                    
                    <?php if($chapter_allowed[0] == 'all'): ?>
                    
                    <li class="divider"></li>
                    <li>
                        <a href="<?= base_url(); ?>admin/index/export_db"><i class="fa fa-upload fa-fw"></i> <?= lang('glb_nav_export'); ?></a>
                    </li>
                    <li>
                        <a href="<?= base_url(); ?>admin/index/import_db"><i class="fa fa-download fa-fw"></i> <?= lang('glb_nav_import'); ?></a>
                    </li>
                    


                    <?php endif; ?>

                  </ul>
                </li>

                <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><?= lang('glb_nav_chapter'); ?><span class="caret"></span></a>
                  <ul class="dropdown-menu" role="menu">
                    <li>
                        <a href="<?= base_url(); ?>admin/chapter"><i class="fa fa-info fa-fw"></i> <?= lang('glb_nav_chapter_details'); ?></a>
                    </li>
                    <li>
                        <a href="<?= base_url(); ?>admin/event/index"><i class="fa fa-clock-o fa-fw"></i> <?= lang('glb_nav_event_index'); ?></a>
                    </li>
                    <li>
                        <a href="<?= base_url(); ?>admin/event/detail"><i class="fa fa-plus-square fa-fw"></i> <?= lang('glb_nav_event_add'); ?></a>
                    </li>
                    <li class="divider"></li>
                    <li>
                        <a href="<?= base_url(); ?>admin/agm/"><i class="fa fa-briefcase"></i> 會員大會</a>
                    </li>
                    <li>
                        <a href="<?= base_url(); ?>admin/agm/list_zoom_registrant"><i class="fa fa-briefcase"></i> AGM Zoom 團體</a>
                    </li>
                    <li>
                        <a href="<?= base_url(); ?>admin/agm/list_zoom_registrant_personal"><i class="fa fa-briefcase"></i> AGM Zoom 個人</a>
                    </li>
                    <li>
                        <a href="<?= base_url(); ?>admin/agm/list_zoom_registrant_nonvote"><i class="fa fa-briefcase"></i> AGM Zoom 列席</a>
                    </li>

                  </ul>
                </li>

                <?php if($chapter_allowed[0] == 'all'): ?>
                <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><?= lang('glb_nav_dharma'); ?><span class="caret"></span></a>
                  <ul class="dropdown-menu" role="menu">
                    <?php foreach($this->config->item('tbs')['dharma_staff'] as $k => $v): ?>
                    <li>
                        <a href="<?= base_url(); ?>admin/contact/dharma/<?= strtolower($k); ?>"><i class="fa fa-user fa-fw"></i> <?= $v; ?></a>
                    </li>
                    <?php endforeach;?>
                    <li class="divider"></li>
                    <li>
                        <a href="<?= base_url(); ?>admin/insurance/"><i class="fa fa-user fa-fw"></i> <?= lang('glb_nav_insurance'); ?></a>
                    </li>


                  </ul>
                </li>

                <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><?= lang('glb_nav_user'); ?><span class="caret"></span></a>
                  <ul class="dropdown-menu" role="menu">
                    <li>
                        <a href="<?= base_url(); ?>admin/user/index"><i class="fa fa-users fa-fw"></i> <?= lang('glb_nav_user_list'); ?></a>
                    </li>
                    <li>
                        <a href="<?= base_url(); ?>admin/user/add"><i class="fa fa-user-plus fa-fw"></i> <?= lang('glb_nav_user_new'); ?></a>
                    </li>
                    <li>
                        <a href="<?= base_url(); ?>admin/contact/list"><i class="fa fa-users fa-fw"></i> <?= lang('glb_nav_contact'); ?></a>
                    </li>
                    <li>
                        <a href="<?= base_url(); ?>admin/contact/add_contact"><i class="fa fa-user-plus fa-fw"></i> <?= lang('glb_nav_add_contact'); ?></a>
                    </li>

                    <li class="divider"></li>
                    <li>
                        <a href="<?= base_url(); ?>admin/email/chapter"><i class="fa fa-envelope"></i> Email - Chapters</a>
                    </li>
                    <li>
                        <a href="<?= base_url(); ?>admin/email/member"><i class="fa fa-envelope"></i> Email - Members</a>
                    </li>
                    <li>
                        <a href="<?= base_url(); ?>admin/chapter/list_contact"><i class="fa fa-phone"></i> Phone - Chapters</a>
                    </li>

                  </ul>
                </li>

                <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">其他系統 <span class="caret"></span></a>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="<?= base_url(); ?>/dizang/login" target="_blank"><i class="fa fa-user-md"></i> 地藏殿系統</a></li>
                    <li><a href="<?= base_url(); ?>/wenxuan/login" target="_blank"><i class="fa fa-book"></i> 文宣處系統</a></li>
                    <li><a href="<?= base_url(); ?>/"></a></li>
                    <li><a href="<?= base_url(); ?>/"></a></li>
                  </ul>
                </li>
                


                <!-- li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">供僧大會 <span class="caret"></span></a>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="<?= base_url(); ?>admin/forms/sangha"><i class="fa fa-info fa-fw"></i> 供僧功德主</a></li>
                    <li><a href="<?= base_url(); ?>admin/forms/blessing"><i class="fa fa-info fa-fw"></i> 法會主祈者-祈福</a></li>
                    <li><a href="<?= base_url(); ?>admin/forms/bardo"><i class="fa fa-info fa-fw"></i> 法會主祈者-超度</a></li>
                    <li><a href="<?= base_url(); ?>admin/forms/blessing_normal"><i class="fa fa-info fa-fw"></i> 法會報名-祈福</a></li>
                    <li><a href="<?= base_url(); ?>admin/forms/bardo_normal"><i class="fa fa-info fa-fw"></i> 法會報名-超度</a></li>

                  </ul>
                </li -->
                <?php endif; ?>

              </ul>

              <ul class="nav navbar-nav navbar-right" style='margin-right:10px;'>
                <li><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><?= $google_name; ?> <img src='<?= $avatar; ?>?sz=20' class='img-circle' style='width:20px'> <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li>
                            <a href='#'>
                            <table><tr>
                                <td><img src='<?= $avatar; ?>?sz=50' style='width:50px' /></td>
                                <td style='padding-left:10px;'>
                                    <b><?= $google_name; ?></b>
                                    <small><br /><?= $google_email; ?></small>
                                </td>
                            </tr></table>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="<?= base_url(); ?>admin/login/logout/"><i class="fa fa-sign-out"></i> Logout</a>
                        </li>
                    </ul>
                </li>
              </ul>

              <form class="navbar-form navbar-right" style='margin-right:15px;'>
                <div class="form-group">
                  <?= $chapter_dropdown; ?>
                </div>
              </form>
          </div>

            
            <!-- /.navbar-static-side -->
        </nav>