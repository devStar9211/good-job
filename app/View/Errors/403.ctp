<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
    <meta name="description" content="Caregiverjapan様 日次決算システム">
    <meta name="author" content="Caregiverjapan様 日次決算システム">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
	<title><?php echo $title_for_layout ?></title>
	<?php echo $this->html->meta('icon','favicon.ico', array('type' =>'icon')); ?>

	<style type="text/css">
		body {
			background-color:#fffffa;
			margin:auto;
			padding:0;
			-webkit-user-select: none;
			-moz-user-select: none;
			-o-user-select: none;
			user-select: none;
			width:800px;
			cursor: default;
		}
		div {
			margin-top: 100px;
			font-family:arial, serif;
			color: #272727 ;
			text-align: center;
		}
		h1 {
			font-size: 76px;
			margin: 0;
			padding: 0;
			line-height: 80px;
			color: #665e53 ;
		}
		strong {
			font-size: 60px;
			line-height: 45px;
			color:#423735;
		}
		p.notfound {
			font-size: 24px;
			color: #665e53 ;
		}
		p.small {
			font-size:14px;
			color:#423735;
			line-height:18px;
		}
		input{
			padding: 5px 0 ;
		}
		p a {
			color:#423735;
			font-weight:bold;
			text-decoration:none;
		}
		p a:hover {
			color:#d6c6af;
			font-weight:bold;
			text-decoration:underline;
		}
		input {
			background-color : #efece6 ;
			border: 1px solid  #d6c6af;
			-webkit-border-radius: 5px;
			-moz-border-radius: 5px;
			border-radius: 5px;
		}
	</style>
</head>
 
<body>
	<div>
		<h1>403</h1>
		<strong>Forbidden </strong>
		<p class="notfound"><?php echo __('あなたはこのページの権限がありません。') ?></p>
		<p class="small"><?php echo __('お手数です。') ?></p>
		<p><?php echo $this->Html->link(__('ホームページ'), array('controller' => 'frontend', 'action' => 'index', 'admin' => false)) ?></p>
	</div>
</body>
</html>