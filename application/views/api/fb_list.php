<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>

<title><?= lang('fb_like_title'); ?></title>
<meta name="keywords" content="<?= lang('fb_like_keyword'); ?>" />
<meta name="description" content="<?= lang('fb_like_description'); ?>" />
<?php foreach($meta_fb as $k => $v): ?>
    <meta property="<?= $k; ?>" content="<?= $v; ?>" />
<?php endforeach; ?>
</head>
<body>
    <div class="container-fluid">
        <div class='row'>
            <div class='col-sm-12' style='background:#952023;'>
                <img src='http://www.tbsn.my/images/header.jpg' style='display: block; margin-left: auto; margin-right: auto;' />
            </div>
        </div>
        <div class='row'>
            <div class='col-sm-12 text-center' style='background:#660000;color:#FFF;'>
                <h2> 馬來西亞道場面子書專頁列表 </h2>
            </div>
        </div>

        <div class='row'>&nbsp;</div>
    
        <div class='row'>
            <div class='col-sm-12'>
                <table class='table table-striped table-bordered table-hover'>
                    <thead>
                        <tr class='active'>
                            <th class='col-sm-6'>道場名字</th>
                            <th class='col-sm-4'>面子書專頁</th>
                            <th class='col-sm-2'>馬上按贊</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        <?php foreach($pages as $d): ?>
                        <tr>
                            <td nowrap><?= $d['name_chinese']; ?></td>
                            <td style='width:150px;max-width:150px;text-align:center;'><a href='<?= $d['fb_page']; ?>' target='_like'>點擊瀏覽面子書專頁</a></td>
                            <td style='width:100px;max-width:100px;'><iframe src="//www.facebook.com/plugins/like.php?href=<?= urlencode($d['fb_page']); ?>&amp;width=100&amp;layout=button_count&amp;action=like&amp;show_faces=false&amp;share=false&amp;height=21&amp;appId=111147508995524" scrolling="no" frameborder="0" style="border:none; overflow:hidden; height:21px;" allowTransparency="true"></iframe></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>


        <div class='row'>
            <div class='col-sm-12 text-center' style='background:#660000;color:#FFF;'>
                <h2> 馬來西亞其他相關面子書專頁列表 </h2>
            </div>
        </div>

        <div class='row'>&nbsp;</div>
    
        <div class='row'>
            <div class='col-sm-12'>
                <table class='table table-striped table-bordered table-hover'>
                    <thead>
                        <tr class='active'>
                            <th class='col-sm-6'>團體/單位名字</th>
                            <th class='col-sm-4'>面子書專頁</th>
                            <th class='col-sm-2'>馬上按贊</th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        <?php foreach($pages2 as $d): ?>
                        <tr>
                            <td nowrap><?= $d['name_chinese']; ?></td>
                            <td style='width:150px;max-width:150px;text-align:center;'><a href='<?= $d['fb_page']; ?>' target='_like'>點擊瀏覽面子書專頁</a></td>
                            <td style='width:100px;max-width:100px;'><iframe src="//www.facebook.com/plugins/like.php?href=<?= urlencode($d['fb_page']); ?>&amp;width=100&amp;layout=button_count&amp;action=like&amp;show_faces=false&amp;share=false&amp;height=21&amp;appId=111147508995524" scrolling="no" frameborder="0" style="border:none; overflow:hidden; height:21px;" allowTransparency="true"></iframe></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>


    </div>
    <script type="text/javascript">
    var _gaq = _gaq || [];
    _gaq.push(["_setAccount", "UA-40622277-1"]);
    _gaq.push(["_trackPageview"]);
    (function() {
        var ga = document.createElement("script"); ga.type = "text/javascript"; ga.async = true;
        ga.src = ("https:" == document.location.protocol ? "https://ssl" : "http://www") + ".google-analytics.com/ga.js";
        var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(ga, s);
    })();
    </script>
</body>
</html>
