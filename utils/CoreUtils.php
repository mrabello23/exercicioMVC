<?php
class CoreUtils{
	public function validarDados(array $dataPost){
		$retorno = array();

		foreach ($dataPost as $key => $value) {
			$retorno[$key] = trim(addslashes(strip_tags($value)));
		}

		return $retorno;
	}

	public function upload(array $dados, $pathUpload = ''){
		// echo "<pre>"; print_r($dados); echo "</pre>";exit;

		// Tamanho máximo do arquivo (em Bytes)
		$_UP['tamanho_maximo'] = (1024 * 1024) * 15; // 15 MB

		// Myme Types permitidas
		$_UP['tipos_permitidos'] = array(
			'pdf'  => 'application/pdf',
			'xls'  => 'application/vnd.ms-excel',
			'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
		);

		$destinatarios = array(
			"Admin" => 'desenvolvimento.fenix@portaldedocumentos.com.br'
		);

		$local = $pathUpload.date("Y_m_d");
		$this->criarPasta($local);

		$valida = 0;
		$vazios = 0;
		$total  = count($dados["arquivo"]["name"]);
		$arquivos = array();
		$retorno  = array("sucesso" => "", "caminho" => "", "arquivos" => $arquivos);

		if (is_array($dados['arquivo']['tmp_name'])) {
			for ($i = 0; $i < $total; $i++) {
				if ( empty($dados['arquivo']['tmp_name'][$i]) ) {
					$vazios++;
					continue;
				}

				// Verificação do tipo de arquivo
				if ( $dados['arquivo']['type'][$i] != $_UP['tipos_permitidos']["pdf"] ) {
					$retorno["msg"] = "Arquivo enviado não é PDF - Falha no documento: ".$i;
					$retorno["sucesso"] = false;

					return $retorno;
				}

				// Faz a verificação do tamanho do arquivo
				if ( $_UP['tamanho_maximo'] < $dados['arquivo']['size'][$i] ) {
					$retorno["msg"] = "O arquivo enviado é muito grande.<br/>Limite".($_UP['tamanho_maximo'] / 1024)."<br/>Tamanho: ".($dados['arquivo']['size'][$i] / 1024)." - Falha no documento: ".$i;
					$retorno["sucesso"] = false;

					return $retorno;
				}

				$nome = uniqid("imagem_");
				$arquivos[] = $local."/".$nome.".pdf";
				$valida += move_uploaded_file($dados['arquivo']['tmp_name'][$i], $local."/".$nome.".pdf");
			}

			$retorno["sucesso"] = ($valida == ($total - $vazios) ? true : false);
		} else {
			if ( !empty($dados['arquivo']['tmp_name']) ) {
				// Verificação do tipo de arquivo
				if ( $dados['arquivo']['type'] != $_UP['tipos_permitidos']["xlsx"] ) {
					$retorno["msg"] = "Arquivo enviado não é XLSX";
					$retorno["sucesso"] = false;

					return $retorno;
				}

				// Faz a verificação do tamanho do arquivo
				if ( $_UP['tamanho_maximo'] < $dados['arquivo']['size'] ) {
					$retorno["msg"] = "O arquivo enviado é muito grande.<br/>Limite".($_UP['tamanho_maximo'] / 1024)."<br/>Tamanho: ".($dados['arquivo']['size'] / 1024);
					$retorno["sucesso"] = false;

					return $retorno;
				}

				$nome = uniqid("arquivo_");
				$arquivos = $local."/".$nome.".xlsx";
				$valida = move_uploaded_file($dados['arquivo']['tmp_name'], $arquivos);
			}

			$retorno["sucesso"] = $valida;
		}

		$retorno["caminho"] = $local."/";
		$retorno["arquivos"] = $arquivos;

		return $retorno;
	}

	public function criarPasta($caminho){
		if ( !is_dir($caminho) ) {
			mkdir($caminho, 0777) or die("Erro ao criar diretório");
			chmod($caminho, 0777) or die("Erro ao dar permissões ao diretório");
		}
	}

	public function paginacao($url, $inicio, $total, $limite){
		//calcula o número de páginas arredondando o resultado para cima
		$numPaginas = ceil($total / $limite);

		//retorno da paginação
		$html ='<nav aria-label="Page navigation" style="text-align:center;">';
			$html .='<ul class="pagination">';
				$html .='<li '.($inicio < 2 ? 'class="disabled"' : '').'>';
					$html .='<a href="'.$url.'?pg=1" aria-label="Previous" title="Primeira Pag">';
						$html .='<span aria-hidden="true">&laquo;</span> Primeira';
					$html .='</a>';
				$html .='</li>';
				for($i = 1; $i <= $numPaginas; $i++) {
					if ($i >= ($inicio - 5) && $i <= ($inicio + 5)) {
						if ($i == $inicio) {
							$html .='<li class="active"><a href="'.$url."?pg=".$i.'">'.$i.'</a></li>';
						} else {
							$html .='<li><a href="'.$url."?pg=".$i.'">'.$i.'</a></li>';
						}
					}
				}
				$html .='<li '.($inicio == $numPaginas ? 'class="disabled"' : '').'>';
					$html .='<a href="'.$url.'?pg='.$numPaginas.'" aria-label="Next" title="Ultima Pag">';
						$html .='Ultima <span aria-hidden="true">&raquo;</span>';
					$html .='</a>';
				$html .='</li>';
			$html .='</ul>';
		$html .='</nav>';

		return $html;
	}

	public function gerarSenha(){
		/**
		* A-Z
		* chr(65); A
		* chr(90); Z
		*
		* a-z
		* chr(97); a
		* chr(122); z
		*/

		$especiais = array("@","#");
		$senha = "";
		$senha .= chr( rand(97,122) ); // letra minuscula
		$senha .= rand(0,99); // numeros
		$senha .= chr( rand(97,122) ); // letra minuscula
		$senha .= chr( rand(65,90) ); // letra maiuscula
		$senha .= $especiais[rand(0,1)]; // caractere especial
		$senha .= chr( rand(65,90) ); // letra maiuscula
		$senha .= chr( rand(97,122) ); // letra minuscula
		$senha .= chr( rand(65,90) ); // letra maiuscula
		$senha .= rand(0,99); // numeros
		$senha .= $especiais[rand(0,1)]; // caractere especial

		return $senha;
	}

	public function sanitizeString($str) {
		$str = preg_replace('/[áàãâä]/ui', 'a', $str);
		$str = preg_replace('/[éèêë]/ui', 'e', $str);
		$str = preg_replace('/[íìîï]/ui', 'i', $str);
		$str = preg_replace('/[óòõôö]/ui', 'o', $str);
		$str = preg_replace('/[úùûü]/ui', 'u', $str);
		$str = preg_replace('/[ç]/ui', 'c', $str);
		$str = preg_replace('/_+/', '_', $str);

		return $str;
	}

	public function excelParse($file){
		try {
			$xlsx = new SimpleXLSX($file);
			list($cols) = $xlsx->dimension();
			$rows = $xlsx->rows();
			$retorno = array();

			foreach($rows as $k => $r) {
				if ($k == 0) {
					continue;
				}
				for( $i = 0; $i < $cols; $i++){
					(isset($r[$i]) ? $retorno[$rows[0][$i]][] = $r[$i] : '');
				}
			}
			return $retorno;
		} catch (Exception $e) {
			$this->enviaEmail(
				array("Admin" => 'desenvolvimento.fenix@portaldedocumentos.com.br'),
				"Erro no Parse do Excel - Renegociações",
				$e->getMessage()
			);
			return false;
		}
	}

	public function encodeUrl($string) {
		$entities = array(
			'%21','%2A','%27','%28','%29','%3B','%3A','%40',
			'%26','%3D','%2B','%24','%2C','%2F','%3F','%25',
			'%23','%5B','%5D'
		);
		$replacements = array(
			'!','*',"'","(",")",";",":","@","&","=",
			"+","$",",","/","?","%","#","[","]"
		);

		return str_replace($entities, $replacements, urlencode($string));
	}

	public function formatarDataExcel($data){
		# 86400 = number of seconds in a day (24 * 60 * 60).
		# 25569 = number of days from Jan 1, 1900 to Jan 1, 1970.
		# EXCEL base date: Jan 1, 1900
		# UNIX  base date: Jan 1, 1970.
		# UNIX date values are in seconds from Jan 1, 1970 (midnight Dec 31, 1969).
		# To convert from EXCEL you must subtract the number of days and then convert to seconds

		if ( !is_numeric($data) ) {
			return $data;
		}

		$unix_date = ($data - 25569) * 86400;
		return gmdate("d/m/Y H:i:s", $unix_date);
	}

	public function validarCPF($cpf = null) {
		$invalidos = array(
			'00000000000','11111111111','22222222222','33333333333',
			'44444444444','55555555555','66666666666','77777777777',
			'88888888888','99999999999','12345678900','98765432100'
		);

		# Verifica se um número foi informado
		if(empty($cpf)) {
			return false;
		}

		# Verifica se nenhuma das sequências invalidas abaixo foi digitada. Caso afirmativo, retorna falso
		if (in_array($cpf, $invalidos)){
			return false;
		}

		# Elimina possivel mascara
		$cpf = preg_replace('/[^0-9]/', '', (string) $cpf);
		$cpf = str_pad($cpf, 11, '0', STR_PAD_LEFT);

		# Verifica se o numero de digitos informados é igual a 11 
		if (strlen($cpf) != 11) {
			return false;
		}

		# Calcula os digitos verificadores para verificar se o CPF é válido
		for ($t = 9; $t < 11; $t++) {
			for ($d = 0, $c = 0; $c < $t; $c++) {
				$d += $cpf{$c} * (($t + 1) - $c);
			}

			$d = ((10 * $d) % 11) % 10;

			if ($cpf{$c} != $d) {
				return false;
			}
		}

		return true;
	}

	public function validarCnpj($cnpj = null){
		// Lista de CNPJs inválidos
		$invalidos = array(
			'00000000000000','11111111111111','22222222222222',
			'33333333333333','44444444444444','55555555555555',
			'66666666666666','77777777777777','88888888888888',
			'99999999999999'
		);

		# Verifica se um número foi informado
		if(empty($cnpj)) {
			return false;
		}

		# Verifica se o CNPJ está na lista de inválidos
		if (in_array($cnpj, $invalidos)) {
			return false;
		}

		$cnpj = preg_replace('/[^0-9]/', '', (string) $cnpj);
		$cnpj = str_pad($cnpj, 14, '0', STR_PAD_LEFT);

		# Valida tamanho
		if (strlen($cnpj) != 14){
			return false;
		}

		# Valida primeiro dígito verificador
		for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++){
			$soma += $cnpj{$i} * $j;
			$j = ($j == 2) ? 9 : $j - 1;
		}

		$resto = $soma % 11;
		if ($cnpj{12} != ($resto < 2 ? 0 : 11 - $resto)){
			return false;
		}

		# Valida segundo dígito verificador
		for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++){
			$soma += $cnpj{$i} * $j;
			$j = ($j == 2) ? 9 : $j - 1;
		}

		$resto = $soma % 11;
		return $cnpj{13} == ($resto < 2 ? 0 : 11 - $resto);
	}
}