  <!--BANNER START-->
  <div id="banner" class="section-padding">
    <div class="container">
      <div class="row">
        <div class="jumbotron">
          <h1 class="small"><?= $chapter['name_chinese']; ?></h1>
          <p class="big"><?= $chapter['name_malay']; ?> (<?= $chapter['gov_register_number']; ?>)</p>
          <p class="big"><?= $chapter['city'].' | '.$chapter['state']; ?></p>
        </div>
      </div>
    </div>
  </div>
  <!--BANNER END-->

  <!--SERVICE START-->
  <div id="service" class="section-padding">
    <div class="container">
      <div class="row">
        <div class="page-title col-md-10 col-md-offset-1">
          <h1 class='text-center'>道場簡介</h1>
          <hr class="pg-titl-bdr-btm"></hr>
          <p><?php echo $chapter['additional_info']; ?></p>
          
        </div>
      </div>
    </div>
  </div>
  <!--SERVICE END-->


  <!--TEAM START-->
  <div id="about" class="section-padding">
    <div class="container">
      <div class="row">
        <div class="page-title col-md-10 col-md-offset-1">
          <h1 class='text-center'>聯絡我們</h1>
          <hr class="pg-titl-bdr-btm"></hr>
          <table class='table table-condensed contact'>
            <tr>
                <td>地址</td><td>:</td>
                <td><?= $chapter['address']; ?>, <?= $chapter['postcode']; ?> <?= $chapter['city']; ?>, <?= $chapter['state']; ?></td>
            </tr>

            <?php if($chapter['mailing_address']): ?><tr>
                <td>通訊地址</td><td>:</td>
                <td><?= $chapter['mailing_address']; ?></td>
            </tr><?php endif; ?>

            <?php if($chapter['phone']): ?><tr>
                <td>電話</td><td>:</td>
                <td><?= $chapter['phone']; ?></td>
            </tr><?php endif; ?>

            <?php if($chapter['contact_person']): ?><tr>
                <td>聯絡人</td><td>:</td>
                <td><?= $chapter['contact_person']; ?></td>
            </tr><?php endif; ?>

            <?php if($chapter['fax']): ?><tr>
                <td>傳真</td><td>:</td>
                <td><?= $chapter['fax']; ?></td>
            </tr><?php endif; ?>

            <?php if($chapter['email']): ?><tr>
                <td>電郵</td><td>:</td>
                <td><a href="mailto:<?= $chapter['email']; ?>"><?= $chapter['email']; ?></a></td>
            </tr><?php endif; ?>

            <?php if($chapter['website']['link']): ?><tr>
                <td>網站</td><td>:</td>
                <td><a href='<?= $chapter['website']['link']; ?>' target='_blank'><?= $chapter['website']['label']; ?></a></td>
            </tr><?php endif; ?>

            <?php if($chapter['website']['link']): ?><tr>
                <td>Foursquare</td><td>:</td>
                <td><a href='<?= $chapter['4square_page']; ?>' target='_blank'><?= $chapter['4square_page']; ?></a></td>
            </tr><?php endif; ?>

            <?php if($chapter['fb_page']): ?><tr>
                <td>面子書專頁</td><td>:</td>
                <td>
                    <a href='<?= $chapter['fb_page']; ?>' target='_blank'><?= $chapter['fb_page']; ?></a>
                    <iframe src="//www.facebook.com/plugins/like.php?href=<?= urlencode($chapter['fb_page']); ?>&amp;width=100&amp;layout=button_count&amp;action=like&amp;show_faces=false&amp;share=false&amp;height=21&amp;appId=111147508995524" scrolling="no" frameborder="0" style="border:none; overflow:hidden; height:20px; width:100px;" allowTransparency="true"></iframe>
                </td>
            </tr><?php endif; ?>

            <?php if($chapter['dharma_staff']): ?><tr>
                <td>弘法人員</td><td>:</td>
                <td><?= $chapter['dharma_staff']; ?></td>
            </tr><?php endif; ?>

        </table>
          
        </div>
      </div>
    </div>
  </div>
  <!--TEAM END-->

  <!--CTA2 START-->
  <div class="cta2">
    <div class="container">
      <div class="row white text-center">
        <p class="cta-sub-title">“活一天，感恩一天。活一天，修行一天。活一天，快樂一天。”</p>
        <p class="cta-title text-right"> - 蓮生活佛 【盧勝彥文集第193冊《牛稠溪的嗚咽》 - 修行即是工作】</p>
      </div>
    </div>
  </div>
  <!--CTA2 END-->

  <!--CONTACT START-->
  <?php if($chapter['coordinate']): ?>
  <div id="contact" class="section-padding">
    <div class="container">
      <div class="row">
        <div class="page-title text-center">
          <h1><?= $chapter['name_chinese']; ?>地圖</h1>
          <hr class="pg-titl-bdr-btm"></hr>

        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC6Hp4z7U6uBReF-79dBTmb-RuTAAmFnPw"></script>
        <script>
          function initialize() {
            var mapCanvas = document.getElementById('map-canvas');
            var mapOptions = {
              center: new google.maps.LatLng(<?= $chapter['coordinate']; ?>),
              zoom: 15,
              mapTypeId: google.maps.MapTypeId.ROADMAP
            }
            var map = new google.maps.Map(mapCanvas, mapOptions);
            var marker = new google.maps.Marker({
              position: new google.maps.LatLng(<?= $chapter['coordinate']; ?>),
              map: map
            });
          }
          google.maps.event.addDomListener(window, 'load', initialize);
        </script>
        <div class="row">
            <div class="box">
                <div class="col-lg-12">
                    <div id="map-canvas" style='width:100%;height:400px;'></div>
                </div>
            </div>
        </div>
        <?php endif; ?>
        </div>
        
      </div>
    </div>
  </div>
  <!--CONTACT END-->