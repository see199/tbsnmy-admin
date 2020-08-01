<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-40622277-2', 'auto');
  ga('send', 'pageview');

</script>
<style>
    a.xxx:hover{
        background: rgb(148,42,77);color:#FFF;
    }
    a.xxx{
        background:rgb(165,65,92);color:#FFF;
    }
</style>

<div class="container" style='background:rgb(148,42,77);'>
    <div class="row">
        <div class="box">
            <div class="col-lg-12">
                <div class='col-lg-3'><img src='/asset/img/tbnews-logo.png' /></div>
                <div class='col-lg-4' style='padding:0 50px;'>
                    <center><h4 style='color:rgb(255,246,133);'>歡迎隨喜贊助真佛報<br/>功德等同印經</h3></center>
                    <table class='no-border' style='color:#FFF;'>
                        <tr><td>賬戶：</td><td>P.A.B.T Chen Foh Chong Malaysia</td></tr>
                        <tr><td valign=top>帳號：</td><td>Public Bank 3125 8429 30<br />Maybank 5121 0160 4748</td></tr>
                        <tr><td colspan=2>Tel: 03-3374 9399 Fax: 03-3377 1908<br />Website: www.tbsn.my</td></tr>
                    </table>
                </div>
                <div class='col-lg-5' style='padding:0 20px 20px 20px;background:rgb(165,65,92);'>
                    <h2 style='color:rgb(255,246,133);border-bottom:2px solid rgb(191,94,0);'><?= $year; ?> 年</h2>
                    <table class='no-border' style='color:#FFF;'>
                        <tr><td>創辦人：</td><td>蓮生活佛 盧勝彥 | 1991年10月 創刊</td></tr>
                        <tr><td valign=top>出版人：</td><td>
                            馬來西亞真佛宗密教總會 PP8073/07/2012 (030222)
                            <br />PERSEKUTUAN AGAMA BUDDHA TANTRAYANA
                            <br />CHEN FOH CHONG MALAYSIA<small>(No. Pendaftaran: 277)</small>
                        </td></tr>
                        <tr><td>印刷商：</td><td>PEBINACOM SDN BHD (569759-K)
                        <tr><td colspan=2>真佛報大馬增版投稿： <a href='mailto:shihechng@gmail.com' style='color:#FFF'>shihechng@gmail.com</a></td></tr>
                    </table>

                </div>
                
            </div>
        </div>
    </div>

    <div class="row">
        <div class="box">
            <div class="col-lg-12">
                <table class="table table-striped table-bordered table-hover" style='background:#FFFAFA'>
                    <tr><td class='col-lg-12' colspan=2></td></tr>
                <?php foreach($tbsnews as $issue => $data): ?>
                    <tr><td class='col-lg-10'><?= $data['date']; ?> - 大馬增版第 <?= $issue; ?> 期 </a></td>
                        <td class='col-lg-2'><!--a class="btn btn-primary btn-xs" href="http://news.tbsn.my/download/<?= $issue; ?>" role="button">下載 / Download</a-->
                            <a class="btn btn-xs xxx" href="http://news.tbsn.my/<?= $issue; ?>" role="button">瀏覽 / View</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="box">
            <div class="col-lg-8">
                <nav>
                  <ul class="pager">
                    <li class="previous"><a href="<?= $pagenavi['url'].'/'.$pagenavi['previous']; ?>"><span aria-hidden="true">&larr;</span> 真佛報大馬增版 <?= $pagenavi['previous']; ?> 年</a></li>
                    <?php if($pagenavi['next']): ?><li class="next"><a href="<?= $pagenavi['url'].'/'.$pagenavi['next']; ?>">真佛報大馬增版 <?= $pagenavi['next']; ?> 年<span aria-hidden="true">&rarr;</span></a></li><?php endif; ?>
                  </ul>
                </nav>
            </div>
        </div>
    </div>



        
</div>