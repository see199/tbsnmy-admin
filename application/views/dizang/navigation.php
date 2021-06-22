<!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?= base_url(); ?>dizang/index">般若雷藏寺地藏殿系統</a>
            </div>
            <!-- /.navbar-header -->

            
            <!-- /.navbar-top-links -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
              <ul class="nav navbar-nav">
                
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
                            <a href="<?= base_url(); ?>dizang/login/logout/"><i class="fa fa-sign-out"></i> Logout</a>
                        </li>
                    </ul>
                </li>
              </ul>
          </div>

            
            <!-- /.navbar-static-side -->
        </nav>