<!DOCTYPE html>
<html lang="zh-tw">

<head>

    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta http-equiv="Cache-control" content="public">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php if(sizeof($meta)>0):foreach($meta as $name => $content): ?>
    <meta name="<?= $name; ?>" content="<?= $content; ?>" />
    <?php endforeach; endif; ?>
    
    <!-- Bootstrap Core CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" rel="stylesheet">

    
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

</head>

<body>