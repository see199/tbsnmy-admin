<!--FOOTER START-->
  <footer class="footer section-padding">
    <div class="container">
      <div class="row">
        <div style="visibility: visible; animation-name: zoomIn;" class="col-sm-12 text-center wow zoomIn">
          <h3>Follow us on</h3>
          <div class="footer_social">
            <ul>
                <?php if($chapter['fb_page']): ?>
                    <li><a class="f_facebook" href="<?= $chapter['fb_page']; ?>"><i class="fab fa-facebook-f"></i></a></li>
                <?php endif; ?>
                <?php if($chapter['4square_page']): ?>
                    <li><a class="f_twitter" href="<?= $chapter['4square_page']; ?>"><i class="fab fa-foursquare"></i></a></li>
                <?php endif; ?>
                <?php if($chapter['coordinate']): ?>
                    <li><a class="f_google" href="https://www.google.com.my/maps/dir//<?= $chapter['coordinate']; ?>"><i class="fa fa-map-marker"></i></a></li>
                    <li><a class="f_linkedin" href="waze://?ll=<?= $chapter['coordinate']; ?>&amp;navigate=yes"><i class="fab fa-waze"></i></a></li>
                <?php endif; ?>
            </ul>
          </div>
        </div>
        <!--- END COL -->
      </div>
      <!--- END ROW -->
    </div>
    <!--- END CONTAINER -->
  </footer>
  <!--FOOTER END-->
  <div class="footer-bottom">
    <div class="container">
      <div style="visibility: visible; animation-name: zoomIn;" class="col-md-12 text-center wow zoomIn">
        <div class="footer_copyright">
          <p>Copyright &copy; 2008 - <?php echo date('Y'); ?> 馬來西亞真佛宗密教總會網站</p>
        </div>
      </div>
    </div>
  </div>


  <script src="<?= base_url();?>assets/js/jquery.min.js"></script>
  <script src="<?= base_url();?>assets/js/jquery.easing.min.js"></script>
  <script src="<?= base_url();?>assets/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="<?= base_url();?>assets/js/slick.min.js"></script>
  <script type="text/javascript" src="<?= base_url();?>assets/js/custom.js"></script>
  
</body>

</html>