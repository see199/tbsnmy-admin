<?php
//
// Helper function to generate menu items
// Menu Format: URL,FontAwesome Icon,Text
// 'divider' for Divider
//
function generateMenuItem($menuItem) {
  if($menuItem[0] === 'divider') return '<li class="divider"></li>';
  else {
    return '<li>' . anchor(base_url($menuItem[0]),"<i class='${menuItem[1]}'></i> ${menuItem[2]}",'') . '</li>';
  }
}

function generateMenuTitle($menuText){
  return '<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">'.$menuText.' <span class="caret"></span></a>';
}
?>

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
      
      <!-- MENU 1: 目錄 -->
      <li class="dropdown">
        <?= generateMenuTitle(lang('glb_nav_menu'));?>
        <ul class="dropdown-menu" role="menu">
          <?= generateMenuItem(['admin/index','fa fa-dashboard fa-fw',lang('glb_nav_dashboard')]);?>
          <?php
            if($chapter_allowed[0] == 'all'):
              $menu = [
                ['admin/tbnews','fa-solid fa-newspaper',lang('glb_nav_tbnews')],
                ['admin/game','fa fa-gamepad','Games'],
                ['divider'],
                ['admin/index/export_db','fa fa-upload fa-fw',lang('glb_nav_export')],
                ['admin/index/import_db','fa fa-download fa-fw',lang('glb_nav_import')],
              ];
              foreach($menu as $m) echo generateMenuItem($m);
            endif;
          ?>
        </ul>
      </li>

      <!-- MENU 2: 道場 -->
      <li class="dropdown">
        <?= generateMenuTitle(lang('glb_nav_chapter'));?>
        <ul class="dropdown-menu" role="menu">
          <?php
            $menu = [
              ['admin/chapter','fa fa-info fa-fw',lang('glb_nav_chapter_details')],
              ['admin/chapter/list_ajk','fa-solid fa-users','理事會成員'],
              ['admin/event/index','fa-solid fa-calendar-days',lang('glb_nav_event_index')],
              ['admin/event/detail','fa fa-plus-square fa-fw',lang('glb_nav_event_add')],
            ];
            foreach($menu as $m) echo generateMenuItem($m);

            if($chapter_allowed[0] == 'all'){
              echo generateMenuItem(['divider']);
              echo generateMenuItem(['admin/chapter/list_all','fa fa-bank fa-fw','全馬道場列表']);
            }
          ?>
        </ul>
      </li>
        
      <!-- MENU 3: 弘法人員 -->
      <?php if($chapter_allowed[0] == 'all'): ?>
      <li class="dropdown">
        <?= generateMenuTitle(lang('glb_nav_dharma'));?>
        <ul class="dropdown-menu" role="menu">
          <?php
            $menu = [];
            foreach($this->config->item('tbs')['dharma_staff'] as $k => $v){
              $menu[] = ['admin/contact/dharma/'.strtolower($k),'fa fa-user fa-fw',$v];
            }
            $menu[] = ['divider'];
            $menu[] = ['admin/insurance','fa-solid fa-suitcase-medical',lang('glb_nav_insurance')];
            foreach($menu as $m) echo generateMenuItem($m);
          ?>
        </ul>
      </li>
      <?php endif; ?>

        
      <!-- MENU 4: 馬密總會員 -->
      <?php if($chapter_allowed[0] == 'all' || $google_email == 'agm@tbsn.my'): ?>
      <li class="dropdown">
        <?= generateMenuTitle('馬密總會員');?>
        <ul class="dropdown-menu" role="menu">
          <?php
            $menu = [
              ['admin/email/member','fa fa-envelope',' + <i class="fa fa-phone"></i> 個人會員'],
              ['admin/email/chapter','fa fa-envelope','團體會員'],
              ['admin/chapter/list_contact','fa fa-phone','團體會員'],
              ['divider'],
              ['admin/agm','fa fa-briefcase','會員大會'],
              ['admin/agm/list_analyse','fa-solid fa-chart-line',date('Y').' AGM 登記'],
              ['admin/agm/list_login_zoom','fa-solid fa-chart-line',date('Y').' AGM 簽到'],
              ['admin/agm/list_zoom_registrant','fa-regular fa-rectangle-list','AGM Zoom 團體'],
              ['admin/agm/list_zoom_registrant_personal','fa-regular fa-rectangle-list','AGM Zoom 個人'],
              ['admin/agm/list_zoom_registrant_nonvote','fa-regular fa-rectangle-list','AGM Zoom 列席'],
              ['admin/agm/setting','fa fa-cog','AGM Setting'],
              ['divider'],
              ['agm/register','fa fa-user','AGM 團體會員登記'],
              ['agm/register2','fa fa-user','AGM 個人會員登記'],
              ['admin/agm/scan_qr','fa-solid fa-qrcode','AGM QR 簽到'],
            ];
            foreach($menu as $m) echo generateMenuItem($m);
          ?>
        </ul>
      </li>
      <?php endif; ?>

      <?php if($chapter_allowed[0] == 'all'): ?>
        
        <!-- MENU 5: 用戶管理 -->
        <li class="dropdown">
          <?= generateMenuTitle(lang('glb_nav_user'));?>
          <ul class="dropdown-menu" role="menu">
            <?php
              $menu = [
                ['admin/user/index','fa fa-users fa-fw',lang('glb_nav_user_list')],
                ['admin/user/add','fa fa-user-plus fa-fw',lang('glb_nav_user_new')],
                ['admin/contact/list','fa fa-users fa-fw',lang('glb_nav_contact')],
                ['admin/contact/add_contact','fa fa-user-plus fa-fw',lang('glb_nav_add_contact')],
              ];
              foreach($menu as $m) echo generateMenuItem($m);
            ?>
          </ul>
        </li>

        <!-- MENU 6: 其他系統 -->
        <li class="dropdown">
          <?= generateMenuTitle('其他系統');?>
          <ul class="dropdown-menu" role="menu">
            <?php
              $menu = [
                ['dizang/login','fa fa-user-md','地藏殿系統'],
                ['wenxuan/login','fa fa-book','文宣處系統'],
                ['admin/api/verify_list','','Verify List'],
                ['divider'],
                ['admin/event/tbf','fa fa-calendar','宗委會活動'],
              ];
              foreach($menu as $m) echo generateMenuItem($m);
            ?>
          </ul>
        </li>

        <!-- Unused Menu -->
        <?php /*
        <li class="dropdown">
          <?= generateMenuTitle('供僧大會');?>
          <ul class="dropdown-menu" role="menu">
            <?php
              $menu = [
                ['admin/forms/sangha','fa fa-info fa-fw','供僧功德主'],
                ['admin/forms/blessing','fa fa-info fa-fw','法會主祈者-祈福'],
                ['admin/forms/bardo','fa fa-info fa-fw','法會主祈者-超度'],
                ['admin/forms/blessing_normal','fa fa-info fa-fw','法會報名-祈福'],
                ['admin/forms/bardo_normal','fa fa-info fa-fw','法會報名-超度'],
              ];
              foreach($menu as $m) echo generateMenuItem($m);
            ?>
          </ul>
        </li>
        */?>
      <?php endif; ?>
    </ul>

    <!-- Right Side Menu -->
    <ul class="nav navbar-nav navbar-right" style='margin-right:10px;'>
      <li><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><?= $google_name; ?> <img src='<?= $avatar; ?>?sz=20' class='img-circle' style='width:20px'> <span class="caret"></span></a>
        <ul class="dropdown-menu" role="menu">
          <!-- User Profile -->
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
          <?php
            $menu = [
              ['divider'],
              ['admin/login/logout','fa fa-sign-out','Logout'],
            ];
            foreach($menu as $m) echo generateMenuItem($m);
          ?>
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