<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-40622277-2', 'auto');
  ga('send', 'pageview');

</script>
    
<div class="container">
    <div class="row">
        <div class="box">
            <div class="col-lg-8">
                <h1>真佛報大馬增版 <?= $year; ?> 年</h1>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="box">
            <div class="col-lg-8">
                <table class="table table-striped table-bordered table-hover">
                <?php foreach($tbsnews as $issue => $data): ?>
                    <tr><td class='col-lg-10'><?= $data['date']; ?> - 大馬增版第 <?= $issue; ?> 期 </a></td>
                        <td class='col-lg-2'><a class="btn btn-primary btn-xs" href="http://news.tbsn.my/download/<?= $issue; ?>" role="button">下載 / Download</a>
                            <a class="btn btn-primary btn-xs" href="http://news.tbsn.my/<?= $issue; ?>" role="button">瀏覽 / View</a>
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