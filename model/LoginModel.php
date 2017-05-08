<?php
require_once "DB/MYSQL.php";

class LoginModel {
	private $db;

	public function __construct(){
		$this->db = new MYSQL('localhostMYSQL');
	}

	public function autenticar($login, $senha){
		$dados = $this->db->buscar(
			'USUARIO',
			array(
				"EMAIL = " => $login,
				"SENHA = " => $senha,
				"ATIVO = " => 1
			)
		);

		// echo "<pre>"; print_r($dados); echo "</pre>";exit;
		return $dados;
	}

	public function sair(){
		return $this->db->desconectar();
	}
}