<?php 
	$rootPath = "";
	
	if ($_SERVER["HTTP_HOST"] == 'localhost') {
		$rootPath = "projetos/teste/";
		$rootPathUrl = str_replace(DIRECTORY_SEPARATOR, "/", $rootPath);
	}
	
	$base_url = "http://".$_SERVER["SERVER_NAME"]."/".$rootPath;
?>

<!DOCTYPE html>
<html lang="pt-BR">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<title>Projeto Entrevista</title>
		<link rel="stylesheet" href="<?=$base_url;?>libs/bootstrap_3.3.6/css/bootstrap.min.css">
		<link rel="stylesheet" href="<?=$base_url;?>css/main.css?<?=date("YmdHis");?>">
		<link rel="stylesheet" href="<?=$base_url;?>libs/bootstrap-datepicker-1.6.1/css/bootstrap-datepicker3.min.css">
		<link rel="stylesheet" href="<?=$base_url;?>libs/bootstrap-datepicker-1.6.1/css/bootstrap-datepicker3.min.css.map">
		<link rel="stylesheet" href="<?=$base_url;?>libs/bootstrap-select-1.11.2/dist/css/bootstrap-select.css">

		<script src="<?=$base_url;?>libs/jquery/jquery-2.2.0.js" type="text/javascript" charset="utf-8"></script>
		<script src="<?=$base_url;?>libs/bootstrap_3.3.6/js/bootstrap.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="<?=$base_url;?>js/main.js?<?=date("YmdHis");?>" type="text/javascript" charset="utf-8"></script>
		<script src="<?=$base_url;?>js/jQuery-blockUI-v2.70.js" type="text/javascript" charset="utf-8"></script>
		<script src="<?=$base_url;?>js/jquery.mask.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="<?=$base_url;?>libs/bootstrap-datepicker-1.6.1/js/bootstrap-datepicker.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="<?=$base_url;?>libs/bootstrap-datepicker-1.6.1/locales/bootstrap-datepicker.pt-BR.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="<?=$base_url;?>libs/bootstrap-select-1.11.2/dist/js/bootstrap-select.js"></script>

		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	    <!--[if lt IE 9]>
	      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	    <![endif]-->
	</head>
	<body>
		<div class="container<?=($_SERVER['SCRIPT_NAME'] == "/{$rootPathUrl}index.php" ? " index" : "");?>">
		<input type="hidden" id="base_url" value="<?=$base_url;?>">