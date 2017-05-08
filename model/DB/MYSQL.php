<?php
require_once "Conexao.php";

class MYSQL extends Conexao {
	public function __construct($user){
		$this->setDriver("mysql");
		$this->conectar($user);

		if ( !$this->conn ) {
			// DEBUG
			echo "<pre>"; print_r($this->conn); echo "</pre>";
			echo "CRUD::__construct() Erro: não foi possivel estabelecer conexão com BD." . PHP_EOL;

			echo "Errno: " . mysqli_connect_errno() . PHP_EOL;
			echo "Error: " . mysqli_connect_error() . PHP_EOL;

		    exit;
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
				$cond[] = $campo." \"".$valor."\"";
			}

			$sql .= implode(" AND ", $cond);
		}

		if ( !empty($group) ) {
			$sql .= " GROUP BY ".$group;
		}

		if ( !empty($order) ) {
			$sql .= " ORDER BY ".$order;
		}

		if (!empty($final) && !empty($inicio) ) {
			$sql .= " LIMIT {$inicio}, {$final}";
		}

		// echo $sql;
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
				$vals[] = "\"".$valor."\"";
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
			$cond[] = $campo." = \"".$valor."\"";
		}

		$sql .= implode(" AND ", $cond);
		return $this->query( $sql );
	}


	/*
	* EXECUTA QUERY
	* @param Strin $sql - Query literal que será executada
	*/
	public function query($sql){
		if (!empty($sql)) {
			// Set autocommit to off
			mysqli_autocommit($this->conn, FALSE);

			$dados = mysqli_query($this->conn, $sql);
			$retorno = "";
			// echo "<pre>"; print_r($this->conn); print_r($dados); echo "</pre>";

			if (mysqli_error($this->conn)) {
				// Rollback transaction
				mysqli_rollback($this->conn);

				echo "<pre>"; print_r($this->conn); echo "</pre>";
				echo "CRUD::query Erro: não foi possivel executar query. <p>{$sql}</p>" . PHP_EOL;

				echo "Errno: " . mysqli_errno($this->conn) . PHP_EOL;
				echo "Error: " . mysqli_error($this->conn) . PHP_EOL;

				exit;
			}

			if ($dados) {
				if (!stristr($sql, "SELECT")) {
					// Commit transaction
					$retorno = mysqli_affected_rows($this->conn);
					mysqli_commit($this->conn);
				} else {
					if (mysqli_num_rows($dados) > 0) {
						// Caso SELECT monta o array de retorno dos dados
						$retorno = array();

						while ( $row = mysqli_fetch_assoc($dados) ) {
							$retorno[] = $row;
						}
					}
				}
				
				mysqli_free_result($dados);
			}
		}

		// echo "<pre>"; print_r($retorno); echo "</pre>";
		return $retorno;
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
				$val = "\"".$valor."\"";
			}

			$cols[] = $campo." = ".$val;
		}

		$sql .= implode(", ", $cols);
		$sql .= " WHERE ";

		$cond = array();

		foreach ($condicao as $campo => $valor) {
			$cond[] = $campo." = \"".$valor."\"";
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
}