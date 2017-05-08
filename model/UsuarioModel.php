<?php
require_once "DB/MYSQL.php";

class UsuarioModel {
	private $db;
	private $tb;
	private $condicao;

	public function __construct(){
		$this->db = new MYSQL("localhostMYSQL");
		$this->tb = "USUARIO";
		$this->condicao = null;
	}

	public function consultar($url = '', $pagina = '', $inicio = '', $limite = '', $total_por_pagina = '', $id = ''){
		if ( !empty($id) ) {
			$this->condicao = array("id = " => $id);
		}

		$dados = $this->db->buscar(
			$this->tb, 
			$this->condicao, 
			array(
				'id','nome','email',
				'senha','cpf','ativo'
			),'','', 
			'ativo desc, nome asc',
			$inicio,
			$limite
		);

		// echo "<pre>"; print_r($dados); echo "</pre>";
		return $dados;
	}

	public function consultarId($url = '', $pagina = '', $inicio = '', $limite = '', $total_por_pagina = '', $id = ''){
		return $this->consultar($url, $pagina, $inicio, $limite, $total_por_pagina, $id);
	}

	public function salvar(array $dados){
		// echo "<pre>"; print_r($dados); echo "</pre>";exit;
		return $this->db->salvar($this->tb, $dados);
	}

	public function verificarUsuarioCadastrado($campo, $valor){
		if (empty($campo) && empty($valor)) {
			return true;
		}

		return $this->db->buscar(
			$this->tb, array("{$campo} = " => $valor)
		);
	}

	public function excluir($id){
		return $this->db->excluir(
			$this->tb, array("id" => $id)
		);
	}

	public function alterar(array $dados){
		$id = $dados["id"];
		unset($dados["id"]);

		return $this->db->alterar(
			$this->tb, $dados, array("id" => $id)
		);
	}
}