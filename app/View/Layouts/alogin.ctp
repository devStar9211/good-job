<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Caregiverjapan様 日次決算システム">
    <meta name="author" content="Caregiverjapan様 日次決算システム">

    <link rel="shortcut icon" type="favicons/vnd.microsoft.icon" href="/favicons/favicon.ico">
    <link rel="icon" type="image/vnd.microsoft.icon" href="/favicons/favicon.ico">
    <link rel="apple-touch-icon" sizes="57x57" href="/favicons/apple-touch-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/favicons/apple-touch-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/favicons/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/favicons/apple-touch-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/favicons/apple-touch-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/favicons/apple-touch-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/favicons/apple-touch-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/favicons/apple-touch-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/favicons/apple-touch-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192" href="/favicons/android-chrome-192x192.png">
    <link rel="icon" type="image/png" sizes="48x48" href="/favicons/favicon-48x48.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/favicons/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/favicons/favicon-160x160.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/favicons/favicon-196x196.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicons/favicon-16x16.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicons/favicon-32x32.png">
    <link rel="manifest" href="/manifest.json">
    <meta name="msapplication-TileColor" content="#2d88ef">
    <meta name="msapplication-TileImage" content="/mstile-144x144.png">

    <title><?php echo $this->fetch('title'); ?></title>

    <?php echo $this->Html->css('bootstrap.min'); ?>
    <!--[if lt IE 9]>
    <?php echo $this->Html->script('html5shiv'); ?>
    <![endif]-->
    <?php echo $this->Html->css('main'); ?>
    <?php echo $this->Html->css('AdminLTE.min'); ?>
    <?php echo $this->Html->css('blue'); ?>
    <?php echo $this->fetch('css'); ?>
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-102568884-1', 'auto');
        ga('send', 'pageview');

    </script>

</head><!--/head-->

<body class="login-page">
    <?php echo $this->fetch('content'); ?>
    <?php echo $this->Html->script('jquery.min'); ?>
    <?php echo $this->Html->script('bootstrap.min'); ?>
    <?php echo $this->Html->script('icheck.min'); ?>
    <?php echo $this->Html->script('alogin'); ?>
    <?php echo $this->fetch('script'); ?>
</body>
</html>