<?php

class ORACLE extends Conexao {
	public function __construct($user){
		$this->setDriver("oracle");
		$this->conectar($user, "ORACLE");

		if ( !$this->conn ) {
			// DEBUG
			echo "<pre>"; print_r($this->conn); echo "</pre>";
			echo "CRUD::__construct() Erro: não foi possivel estabelecer conexão com BD.";

		    $e = oci_error();
			trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
		}
	}

	public function __destruct(){
		$this->desconectar();
	}

	/*
	* SELECT
	* @param String $tabela - nome tabela
	* @param Array $campos - colunaTb => valor
	* @param Array $condicao - colunaTb => valor
	* @param Array $inner - tabela => valor, campo(ON...) => valor, tipo(INNER, LEFT...) => valor
	* @param String $group - campos agrupados separado por virgula
	* @param String $order - campos ordenados separado por virgula
	*/
	public function buscar($tabela, $condicao = array(), $campos = array(), $inner = array(), $group = '', $order = '', $inicio = '', $final = ''){
		$sql = "SELECT ";

		if ( count($campos) > 0 && !empty($campos[0]) ) {
			foreach ($campos as $k => $campo) {
				if ( $k > 0 ) {
					$sql .= ", ".$campo;
				} else {
					$sql .= $campo;
				}
			}
		} else {
			$sql .= " * ";
		}

		$sql .= " FROM ".$tabela." ";

		if ( !empty($inner) ) {
			if ( is_array($inner["tb"]) ) {
				if ( 
					count($inner["tb"]) === count($inner["on"]) && 
					count($inner["tb"]) === count($inner["tipo"]) && 
					count($inner["on"]) === count($inner["tipo"]) 
				) {
					for ( $i = 0; $i < count($inner["tb"]); $i++ ) { 
						$sql .= $this->setInner($inner["tb"][$i], $inner["on"][$i], $inner["tipo"][$i]);
					}
				} else {
					die( "CRUD::buscar() Erro #1: Faltam argumentos na condicao do JOIN. <br /><br /> SQL: ".$sql );
				}
			} else {
				$sql .= $this->setInner($inner["tb"], $inner["on"], $inner["tipo"]);
			}
		}

		if ( count($condicao) > 0 ) {
			$sql .= " WHERE ";
			$cond = array();

			foreach ($condicao as $campo => $valor) {
				$cond[] = $campo." '".$valor."'";
			}

			$sql .= implode(" AND ", $cond);
		}

		if ( !empty($group) ) {
			$sql .= " GROUP BY ".$group;
		}

		if ( !empty($order) ) {
			$sql .= " ORDER BY ".$order;
		}

		// echo $sql;

		if (!empty($final) && !empty($inicio) ) {
			return $this->query("SELECT * FROM ( SELECT /*+ FIRST_ROWS(8) */ topn.*, ROWNUM rnum FROM (".$sql.") topn WHERE ROWNUM <= ".$final." ) WHERE rnum  >= ".$inicio."");
		}

		return $this->query( $sql );
	}


	/*
	* INSERT
	* @param String $tabela - nome tabela
	* @param Array $campos - colunaTb => valor
	*/
	public function salvar($tabela, $campos = array()){
		$cols = array();
		$vals = array();

		foreach ($campos as $campo => $valor) {
			$cols[] = $campo;

			if (is_null($valor) || $valor == "") {
				$vals[] = "null";
			}elseif (is_numeric($valor)) {
				$vals[] = $valor;
			}else{
				$vals[] = "'".$valor."'";
			}
		}

		$sql = "INSERT INTO ".$tabela." ( ".implode(", ", $cols)." ) VALUES( ".implode(", ", $vals)." )";
		return $this->query( $sql );
	}


	/*
	* DELETE
	* @param String $tabela - nome tabela
	* @param Array $condicao - colunaTb => valor
	*/
	public function excluir($tabela, $condicao = array()){
		$sql = "DELETE FROM ".$tabela." WHERE ";

		$cond = array();

		foreach ($condicao as $campo => $valor) {
			$cond[] = $campo." = '".$valor."'";
		}

		$sql .= implode(" AND ", $cond);
		return $this->query( $sql );
	}


	/*
	* EXECUTA QUERY
	* @param Strin $sql - Query literal que será executada
	*/
	public function query($sql){
		// Prepara statement para execução
		$stmt = oci_parse($this->conn, $sql);

		if (!$stmt) {
			// DEBUG
			echo "<pre>"; print_r($this->conn);  print_r($stmt); echo "</pre> <br/><br/> SQL:".$sql;

			$e = oci_error($this->conn);
			trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
		}

		// Executa query mas NÃO faz Commit
		$execute = oci_execute($stmt, OCI_DEFAULT);
		if (!$execute) {
			// DEBUG
			echo "<pre>"; print_r($this->conn);  print_r($stmt); echo "</pre><br/><br/> SQL:".$sql;

			$e = oci_error($stmt);

			// Rollback das alterações em caso de falha
			oci_rollback($this->conn);
			trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
		}

		if ( !stristr($sql, "SELECT") ) {
			// Commit das alterações em caso de sucesso
			$dados = oci_commit($this->conn);

			if (!$dados) {
				$e = oci_error($this->conn);
				trigger_error(htmlentities($e['message']), E_USER_ERROR);
			}
		} else {
			// Caso SELECT monta o array de retorno dos dados
			$dados = array();
			while ( $row = oci_fetch_array($stmt, OCI_ASSOC + OCI_RETURN_NULLS) ) {
				$dados[] = $row;
			}
		}

		// Libera o ponteiro de statement
		oci_free_statement($stmt);
		return $dados;
	}


	/*
	* UPDATE
	* @param String $tabela - nome tabela
	* @param Array $campos - colunaTb => valor
	* @param Array $condicao - colunaTb => valor
	*/
	public function alterar($tabela, $campos = array(), $condicao = array()){
		$sql = "UPDATE ".$tabela." SET ";

		$cols = array();
		$val  = "";

		foreach ($campos as $campo => $valor) {
			if (is_null($valor) || $valor == "") {
				$val = "null";
			}elseif (is_numeric($valor)) {
				$val = $valor;
			}else{
				$val = "'".$valor."'";
			}

			$cols[] = $campo." = ".$val;
		}

		$sql .= implode(", ", $cols);
		$sql .= " WHERE ";

		$cond = array();

		foreach ($condicao as $campo => $valor) {
			$cond[] = $campo." = '".$valor."'";
		}

		$sql .= implode(" AND ", $cond);
		$this->query( $sql );
	}


	/*
	* Prepara os JOINs da consulta
	* @param String $tabela - tabela que usada para join
	* @param String $campos - condições para estabelcer join
	* @param String $tipo - tipo de join (Inner, Left, Right...)
	*/ 
	public function setInner($tabela, $campos, $tipo){
		return $tipo." JOIN ".$tabela." ON ".$campos." ";
	}


	/*
	* Pega o maior valor de PK para inserção
	*/ 
	public function pegarMaxId($tabela, $campoPK){
		$dados = $this->buscar( $tabela, null, array($campoPK), '', '',  $campoPK." DESC" );
		return $dados[0][$campoPK];
	}


	/*
	* Ocorre um erro no Prepared Statement ao executar qualquer query que envolva datas com PDO e Oracle
	* Foi criado esse Recurso para não travar a classe CRUD
	*/ 
	public function atualizaDatas($tabela, array $campos, array $condicao){
		$sql = "UPDATE ".$tabela." SET ";

		foreach ($campos as $campo) {
			$cols[] = $campo." = TO_DATE('".date("d/m/y H:i:s", time())."', 'DD/MM/YY HH24:MI:SS')";
		}

		$sql .= implode(", ", $cols);
		$sql .= " WHERE ";

		foreach ($condicao as $campo => $valor) {
			$cond[] = $campo." = ".$valor;
		}

		$sql .= implode(" AND ", $cond);
		return $this->query( $sql );
	}
}