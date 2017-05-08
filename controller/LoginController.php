<?php

class LoginController {
	private $loginModel;

	public function __construct(){
		$this->loginModel = new loginModel();
	}

	public function autenticar($login, $senha){
		$dados = $this->loginModel->autenticar(
			trim($login), base64_encode(trim($senha))
		);

		// echo "<pre>{$login}<br/>{$senha}"; print_r($dados); echo "</pre>";exit;

		if (!empty($dados[0]) && count($dados[0]) > 0) {
			$_SESSION['authority_license'] = true;

			// sessão expira depois de 3h logado
			$_SESSION['sess_limite'] = time() + 10800;

			// configs usuario
			$_SESSION['id']    	= $dados[0]['id'];
			$_SESSION['email'] 	= $dados[0]['email'];
			$_SESSION['nome'] 	= $dados[0]['nome'];

			header('Location: view/index.php');
			exit;
		}

		$_SESSION['masg_tipo'] = 'Erro';
		$_SESSION['msg'] = 'Login e/ou Senha incorreto(s).';
		header('Location: index.php' );
		exit;
	}

	public function sair(){
		session_destroy();
		$this->loginModel->sair();

		header('Location: ../index.php' );
		exit;
	}
}