<?php
ini_set("max_execution_time", 0);
ini_set('memory_limit', '2048M');
set_time_limit(0);

$rootPath = "";
if (strtolower($_SERVER["HTTP_HOST"]) == 'localhost' || 
	strtolower($_SERVER["SERVER_NAME"]) == 'localhost'
) {
	$rootPath = "projetos" . DIRECTORY_SEPARATOR . "teste" . DIRECTORY_SEPARATOR;
	$rootPathUrl = str_replace(DIRECTORY_SEPARATOR, "/", $rootPath);
}

define('REALPATH', realpath($_SERVER["DOCUMENT_ROOT"]));

$sess_save_path = REALPATH.DIRECTORY_SEPARATOR.$rootPath.'_temp';
session_save_path($sess_save_path);
ini_set('session.save_path', $sess_save_path);

ini_set('session.gc_probability', 100);
ini_set('session.gc_divisor', 100);

$sess_name = "sessionphp";
session_name($sess_name);
ini_set('session.name', $sess_name);

session_start();
$base_url = "http://".$_SERVER["SERVER_NAME"]."/".$rootPathUrl;

// REDIRECIONAMENTO DE HTTPS
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
	header("Location: ".$base_url);
	exit();
}

$allowed = array(
	"/{$rootPathUrl}index.php",
	"/{$rootPathUrl}entrar.php"
);

// Bloqueio por Autenticação
if( !in_array($_SERVER['SCRIPT_NAME'], $allowed) ) {
	if( !isset($_SESSION["authority_license"]) || $_SESSION["authority_license"] != true || $_SESSION["sess_limite"] < time() ) {
		$_SESSION['msg_tipo'] = 'Erro';
		$_SESSION['msg'] = '<p class="erro">Sessão expirada! Acesse novamente.</p>';

		header('Location: '.$base_url);
		exit;
	}
}

// FUNÇÃO DE AUTOLOAD DE CLASSES
function __autoload($classe){
	global $rootPath;

	if (file_exists(REALPATH.DIRECTORY_SEPARATOR.$rootPath."utils".DIRECTORY_SEPARATOR.$classe.".php")) {
		include_once REALPATH.DIRECTORY_SEPARATOR.$rootPath."utils".DIRECTORY_SEPARATOR.$classe.".php";
	} 
	
	if (file_exists(REALPATH.DIRECTORY_SEPARATOR.$rootPath."model".DIRECTORY_SEPARATOR.$classe.".php")) {
		include_once REALPATH.DIRECTORY_SEPARATOR.$rootPath."model".DIRECTORY_SEPARATOR.$classe.".php";
	} 
	
	if (file_exists(REALPATH.DIRECTORY_SEPARATOR.$rootPath."controller".DIRECTORY_SEPARATOR.$classe.".php")) {
		include_once REALPATH.DIRECTORY_SEPARATOR.$rootPath."controller".DIRECTORY_SEPARATOR.$classe.".php";
	} 
	
	if (file_exists(REALPATH.DIRECTORY_SEPARATOR.$rootPath."utils".DIRECTORY_SEPARATOR.$classe.".php") && 
		file_exists(REALPATH.DIRECTORY_SEPARATOR.$rootPath."model".DIRECTORY_SEPARATOR.$classe.".php") && 
		file_exists(REALPATH.DIRECTORY_SEPARATOR.$rootPath."controller".DIRECTORY_SEPARATOR.$classe.".php")
	) {
		die("Classe ".$classe." não localizada!");
	}
}