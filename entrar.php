<?php
require_once "config.php";

if (isset($_POST["login"]) && isset($_POST["senha"])) {
	if (!empty($_POST["login"]) && !empty($_POST["senha"])) {
		$auth = new LoginController();
		$auth->autenticar(strip_tags($_POST["login"]), strip_tags($_POST["senha"]));
	}
}

$_SESSION['msg_tipo'] = 'Erro';
$_SESSION['msg'] = 'Preencha os campos de Login e Senha corretamente!';
header('Location: index.php');