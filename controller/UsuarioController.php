<?php

class UsuarioController {
	private $usuarioModel;

	public function __construct(){
		$this->usuarioModel = new UsuarioModel();
	}

	public function listar($id = '', $url = '', $pagina = ''){
		/** Paginação */
		$inicio = 0;
		$limite = 15;
		$total_por_pagina = 15;

		if (empty($id)) {
			if ($pagina > 1) {
				$limite = $pagina * $limite;
				$inicio = $limite - ($total_por_pagina - 1);
			}

			$retorno["dados"] = $this->usuarioModel->consultar(
				$url, $pagina, $inicio, $limite, $total_por_pagina
			);

			if ( !empty($url) && !empty($pagina) ) {
				$total_de_paginas = $this->usuarioModel->consultar();

				$utils = new CoreUtils();
				$retorno["paginacao"] = $utils->paginacao($url, $pagina, count($total_de_paginas), $total_por_pagina);
			}
		} else {
			$retorno["dados"] = $this->usuarioModel->consultarId(
				$url, $pagina, $inicio, $limite, $total_por_pagina, $id
			);
		}

		// echo "<pre>"; print_r($retorno); echo "</pre>";
		return $retorno;
	}

	public function salvar(array $dados){
		$utils = new CoreUtils();
		$_email = strtolower(trim($utils->sanitizeString($dados["email"])));
		$_nome = strtoupper($utils->sanitizeString($dados["nome"]));

		$dadosSalvar = array(
			"nome"	=> $_nome,
			"email"	=> $_email,
			"senha"	=> base64_encode($dados["senha"]),
			"cpf"	=> $dados["cpf"],
			"ativo" => $dados["ativo"]
		);

		if (!$utils->validarCPF($dados["cpf"])) {
			$_SESSION['msg_tipo'] = 'Erro';
			$_SESSION['msg'] = 'CPF inválido! Informe um CPF verdadeiro.';

			return false;
		}

		if (isset($dados["codigo"]) && !empty($dados["codigo"])) {
			$metodo = "alterar";
			$dadosSalvar["id"] = $dados["codigo"];
		} else {
			$metodo = "salvar";
			
			if ($this->usuarioModel->verificarUsuarioCadastrado("email", $_email) ||  
				$this->usuarioModel->verificarUsuarioCadastrado("cpf", $dados["cpf"])
			) {
				$_SESSION['msg_tipo'] = 'Erro';
				$_SESSION['msg'] = 'Existe um usuário cadastrado com o EMAIL e/ou CPF informado!';

				return false;
			}
		}
		
		$this->usuarioModel->$metodo($dadosSalvar);
		return true;
	}

	public function excluir($dados){
		$this->usuarioModel->excluir($dados["codigo"]);
		return true;
	}
}