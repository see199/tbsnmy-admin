<!DOCTYPE html>
<html lang="zh-tw">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Type" content="application/pdf; charset=utf-8">
<meta http-equiv="Content-Disposition" content="inline;">
<meta http-equiv="Cache-control" content="public">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title><?= sprintf(lang('viewtbsnews_fb_like_title'),$issue); ?></title>
<meta name="keywords" content="<?= lang('viewtbsnews_fb_like_keyword'); ?>" />
<meta name="description" content="<?= sprintf(lang('viewtbsnews_fb_like_description'),$issue,$issue,$date,$issue); ?>" />
<?php foreach($meta_fb as $k => $v): ?>
<meta property="<?= $k; ?>" content="<?= $v; ?>" />
<?php endforeach; ?>
    
    <!-- Bootstrap Core CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" rel="stylesheet">

    
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>
<style>
iframe{
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    width: 100%;
    height: 100%;
    overflow: hidden;
}
.content{
  /*overflow: hidden;*/
  position: absolute;
  top: 40px;
  left: 0;
  right: 0;
  bottom: 0;
}
.navigation{
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  height: 40px;
  margin-bottom: 0;
}
.pager{
  margin:5px 0;
}
</style>
<div class="container">
  <div class="row">
        <div class="box">
            <div class="col-lg-12">
                <nav>
                  <ul class="pager">
                    <?php if($previous_issue): ?><li class="previous"><a href="<?= 'http://news.tbsn.my/'.$previous_issue; ?>"><span aria-hidden="true">&larr;</span> 大馬增版第 <?= $previous_issue; ?> 期 </a></li><?php endif; ?>
                    <li><a href='<?= 'http://news.tbsn.my/year/'.$year ?>'>返回大馬增版 <?= $year; ?> 年目錄</a></li>
                    <?php if($next_issue): ?><li class="next"><a href="<?= 'http://news.tbsn.my/'.$next_issue; ?>"> 大馬增版第 <?= $next_issue; ?> 期 <span aria-hidden="true">&rarr;</span> </a></li><?php endif; ?>
                  </ul>
                </nav>
            </div>
        </div>
    </div>
  
  <div class='content'>
    <object data='<?= $pdf; ?>' type='application/pdf' width='100%' height='100%' style="overflow:scroll">
        <img src="<?=$meta_fb['og:image'];?>" width='0%'>
      <?php foreach($images as $i_url): ?><img src="<?=$i_url;?>" width='100%'><?php endforeach;?>
      <p style='padding-top:70px;text-align:center;'>若無法瀏覽，敬請點擊以下鏈接下載: <a href="<?= $pdf; ?>"><?= $pdf; ?></a></p></object>
      
  </div>
</div>
    <!-- Bootstrap Core JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-40622277-2', 'auto');
  ga('send', 'pageview');

</script>

</body>

</html>