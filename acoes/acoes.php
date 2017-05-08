<?php 
require "../config.php";

$_SESSION['msg_tipo'] = 'Erro';
$_SESSION['msg'] = 'Classe não foi setada corretamente!';

if(isset($_POST) && !empty($_POST['class'])){
	$_SESSION['msg_tipo'] = 'Erro';
	$_SESSION['msg'] = 'Ocorreu um erro ao efetuar a ação!';

	$class = strip_tags( base64_decode($_POST['class']) );
	$path  = strip_tags( base64_decode($_POST['path']) );

	unset($_POST['class']);
	unset($_POST['path']);

	// Default
	$method = "listar";

	if ( isset($_POST['method']) ) {
		$method = strip_tags(base64_decode($_POST["method"])); 
		unset($_POST["method"]);
	}

	$obj = new $class();
	$valida = new CoreUtils();

	$dados = $obj->$method($valida->validarDados($_POST));
	if($dados){
		$_SESSION['msg_tipo'] = 'Sucesso';
		$_SESSION['msg'] = 'Ação efetuada com sucesso!';
	}
} 

header("Location:".$base_url."view/".$path."/");