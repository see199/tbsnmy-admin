<?php
$minisite = json_decode($chapter['minisite'],1);
if(!isset($minisite['bgcolor'])) $minisite['bgcolor'] = '#800';
?>
<!DOCTYPE html>
<html lang="zh-tw">

<head>

    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta http-equiv="Cache-control" content="public">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="馬來西亞真佛宗密教總會, 蓮生活佛,盧勝彥, 道顯密, 密宗, 密法, 瑜伽,雷藏寺,正信佛教團體, <?= $chapter['name_chinese']; ?>, <?= $chapter['state']; ?>, True Buddha School Malaysia, Vajrayana, Tantrayana, Tao, Grand Master Lian-shen Sheng-yen Lu, Buddhism, Buddha, Buddhist, Dharma, Mantra, Mudra" />
    <meta name="description" content="<?= $chapter['name_chinese']; ?> 是位於馬來西亞 <?= $chapter['state']; ?> 的真佛宗道場。弘揚蓮生活佛盧勝彥所教導的真佛密法。歡迎聯絡 <?= $chapter['name_chinese']; ?>: <?= $chapter['phone']; ?>" />
    <meta name="rights" content="馬來西亞真佛宗密教總會" />
    <meta name="author" content="馬來西亞真佛宗密教總會">
    <base href="http://chapter.tbsn.my/<?= $chapter['url_name']; ?>" />

    <title><?= $chapter['name_chinese']; ?> | 馬來西亞真佛宗道場</title>

    <!-- Bootstrap Core CSS -->
    <link href="<?= base_url();?>assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    
    <link rel="stylesheet" type="text/css" href="<?= base_url();?>assets/css/slick-team-slider.css" />
    <link rel="stylesheet" type="text/css" href="<?= base_url();?>assets/css/style.css">

    <!-- Fonts -->
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300,600|Raleway:600,300|Josefin+Slab:400,700,600italic,600,400italic' rel='stylesheet' type='text/css'>

    <link href="http://www.tbsn.my/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
    
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

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
    <script src="https://kit.fontawesome.com/cd20e8d1d5.js" crossorigin="anonymous"></script>
</head>

<body>

    <!--HEADER START-->
      <div class="main-navigation navbar-fixed-top">
        <nav class="navbar navbar-default">
          <div class="container">
            <div class="navbar-header">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                  </button>
              <a class="navbar-brand" href="index.html"><?= $chapter['name_chinese']; ?></a>
            </div>
            <div class="collapse navbar-collapse" id="myNavbar">
              <ul class="nav navbar-nav navbar-right">
                <li class="active"><a href="#banner">主頁</a></li>
                <li><a href="#service">道場簡介</a></li>
                <li><a href="#about">聯絡我們</a></li>
                <li><a href="#contact">地圖</a></li>
              </ul>
            </div>
          </div>
        </nav>
      </div>
      <!--HEADER END-->