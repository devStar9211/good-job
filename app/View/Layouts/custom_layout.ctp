<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $this->fetch('title'); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="TimeSet" />
    <meta name="keywords" content="TimeSet" />
    <?php echo $this->Html->meta('icon','img/favicon.ico', array('type' =>'icon')); ?>

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


    <?php
        echo $this->Html->css([
            // Bootstrap 3.3.2
            'bootstrap.min.css',
            // Font Awesome Icons
            'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css',
            // Ionicons
            '/assets/components/ionicons/css/ionicons.min.css',
            'frontend.css',
        ]);
    ?>

    <?php echo $this->fetch('css'); ?>
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-102568884-1', 'auto');
        ga('send', 'pageview');

    </script>
</head>
<body class="home_page drawer drawer--left drawer--sidebar layout_frontend">
<div id="fb-root"></div>
<header role="banner" class="navbar navbar-fixed-top navbar-inverse">
    <?php echo $this->element('fheader_custom'); ?>
</header>
<div id="wrapper">
    <?php echo $this->fetch('content'); ?>
    <?php //echo $this->element('sql_dump'); ?>
</div>
<footer>
    <div class="container">
        <div class="row">
            <div class="footer_bottom">
                <div class="col-xs-3  col-md-3 footer_logo">
                    <?php echo $this->Html->image('/img/logo_goodjob.png', array('alt' => 'Timeset', 'url' => array('controller' => 'frontend', 'action' => 'index'))); ?>
                </div>
                <div class="col-xs-9  col-md-9  div-copyright"><p class="copy">2017 Good-Job! All Rights reserved.</p></div>
            </div>
        </div>
    </div>
</footer>
<?php echo $this->fetch('modal'); ?>
<?php
echo $this->Html->script([
    // jQuery 2.1.3
    'jquery.min.js',
    // Bootstrap 3.3.2 JS
    'bootstrap.min.js',
    'common.js',

]);
?>

<?php echo $this->Html->script('jquery.validate.min'); ?>
<script type="text/javascript">
    $(document).ready(function () {
        <?php
        echo $this->element('jquery_validate');
        ?>
    });
</script>



<?php echo $this->fetch('script'); ?>
</body>
</html>