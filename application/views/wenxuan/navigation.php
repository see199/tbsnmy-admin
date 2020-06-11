<!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?= base_url(); ?>wenxuan/index">馬密總文宣系統</a>
            </div>
            <!-- /.navbar-header -->

            
            <!-- /.navbar-top-links -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
              <ul class="nav navbar-nav">
                <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">真佛報 & 燃燈 <span class="caret"></span></a>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="<?= base_url(); ?>wenxuan/lists/chapter"><i class="fa fa-dashboard fa-fw"></i> 道場訂閱</a></li>
                    <li><a href="<?= base_url(); ?>wenxuan/lists/contact"><i class="fa fa-dashboard fa-fw"></i> 個人訂閱</a></li>
                  </ul>
                </li>

                <li><a href="<?= base_url(); ?>wenxuan/lists/package"> 文宣方案</a></li>
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
                            <a href="<?= base_url(); ?>wenxuan/login/logout/"><i class="fa fa-sign-out"></i> Logout</a>
                        </li>
                    </ul>
                </li>
              </ul>
          </div>

            
            <!-- /.navbar-static-side -->
        </nav>