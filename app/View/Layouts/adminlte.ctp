<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="description" content="Caregiverjapan様 日次決算システム">
    <meta name="author" content="Caregiverjapan様 日次決算システム">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

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

    <?php
        echo $this->Html->css([
            // Bootstrap 3.3.2
            'bootstrap.min.css',
            // Font Awesome Icons
           'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css',
            // Ionicons
            '/assets/components/ionicons/css/ionicons.min.css',
            // Select2
            '/assets/components/select2/select2.min.css',
            // Datetimepicker
            '/assets/components/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css',
            // Color picker
            '/assets/components/colorpicker/bootstrap-colorpicker.css',

            // Aweetalert
            '/assets/components/sweetalert/sweetalert.css',
            // Theme style
            'AdminLTE.min.css',
            // AdminLTE Skins. Choose a skin from the css/skins folder instead of downloading all of them to reduce the load.
            '_all-skins.min.css',
            'amain.css',
            // Custom style
            'style.css',
        ]);
    ?>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

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
<body<?php echo isset($pageId) ? ' id="'.$pageId.'"' : ''; ?> class="skin-blue">
    <!-- Site wrapper -->
    <div class="wrapper">
        <header class="main-header">
            <?php echo $this->Html->image('/img/logo_goodjob_white.png', array('class'=>'logo', 'alt' => 'Timeset', 'url' => array('controller' => 'dashboard', 'action' => 'admin_index'))); ?>
            <!-- Header Navbar: style can be found in header.less -->
            <?php echo $this->element('atopnav'); ?>
        </header>

        <!-- =============================================== -->

        <!-- Left side column. contains the sidebar -->
        <aside class="main-sidebar">
            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">
                <!-- sidebar menu: : style can be found in sidebar.less -->
                <?php echo $this->element('asidebarmenu'); ?>
            </section>
            <!-- /.sidebar -->
        </aside>

        <!-- =============================================== -->

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
           <!--  <section class="content-header">
                <div class="row">
                    <div class="col-xs-12 no-padding clearfix">
                        <legend> -->
                            <?php // echo $this->fetch('title'); ?>
                            <?php // echo $this->fetch('action_bar'); ?>
                       <!--  </legend>
                    </div>
                </div>
            </section> -->
            <?php echo $this->fetch('content-header'); ?>
            <!-- Main content -->
            <section class="content">
                <?php echo $this->fetch('content'); ?>

                <?php //echo $this->element('sql_dump'); ?>
            </section><!-- /.content -->
        </div><!-- /.content-wrapper -->

        <footer class="main-footer">
            2017 Good-Job! All Rights reserved.
        </footer>
    </div><!-- ./wrapper -->

    <?php echo $this->fetch('modal'); ?>

    <?php
        echo $this->Html->script([
            // jQuery 2.1.3
            'jquery.min.js',
            // Bootstrap 3.3.2 JS
            'bootstrap.min.js',
            // Underscore
            'underscore-min.js',
            // // SlimScroll
            'jquery.slimscroll.min.js',
            // // FastClick
            // 'fastclick.min.js',
            // // Select2
            '/assets/components/select2/select2.full.min.js',
            // // Datetimepciker
            '/assets/components/bootstrap-datetimepicker/moment-with-locales.min.js',
            '/assets/components/bootstrap-datetimepicker/bootstrap-datetimepicker.js',

            // Color picker
            '/assets/components/colorpicker/bootstrap-colorpicker.min.js',

            // // Sweetalert
            '/assets/components/sweetalert/sweetalert.min.js',
            // // jQuery validation
            '/assets/components/jquery-validation/jquery.validate.min.js',
            // // AdminLTE App
            'app.min.js',
            'amain.js',
        ]);
    ?>

    <?php echo $this->fetch('script'); ?>

    <script type="text/javascript">
        $(document).ready(function(e) {
            //Initialize Select2 Elements
            $(".select2").select2();
        });
    </script>

    <?php echo $this->Html->script('jquery.validate.min'); ?>
    <script type="text/javascript">
        $(document).ready(function(){
            <?php
            echo $this->element('jquery_validate');
            ?>

        });
    </script>
</body>
</html>