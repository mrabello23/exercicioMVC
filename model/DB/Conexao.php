<?php 

class Conexao {
	protected $conn;
	protected $driver = null;
	private $config = array();

	/*
	* Abrir conexao
	*/
	protected function conectar($user){
		$this->setConfig($user);

		if ( !array_key_exists($user, $this->config) ) {
			die("Configurações do BD informado(".$user.") não estão mapeadas.");
		}

		if (empty($this->driver)) {
			die("Driver não foi setado. Tipos disponíveis: mysql e oracle");
		}

		try {
			switch ($this->driver) {
				case 'mysql':
					$this->conn = mysqli_connect(
						$this->config[$user]["host"], $this->config[$user]["usuario"], $this->config[$user]["senha"], $this->config[$user]["database"]
					);
				break;
				case 'oracle':
					$tns  = "(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(HOST=".$this->config[$user]["host"].")(PORT=".$this->config[$user]["port"].")))(CONNECT_DATA=(SID=".$this->config[$user]["sid"].")(SERVER=DEDICATED)))";
					$this->conn = oci_connect(
						$this->config[$user]["usuario"], $this->config[$user]["senha"], $tns
					);
				break;
			}

			return $this->conn;
		} catch (Exception $e) {
			die( "Connection Error #1: ".$e->getMessage() );
		}
	}

	protected function setDriver($driver){
		$this->driver = $driver;
	}

	/*
	* Fechar conexao
	*/
	public function desconectar(){
		switch ($this->driver) {
			case 'mysql':
				mysqli_close($this->conn);
			break;
			case 'oracle':
				oci_close($this->conn);
			break;
		}

		$this->conn = null;
	}

	/*
	* Seta as configurações do usuario do BD
	*/
	private function setConfig($user){
		$this->config["localhostMYSQL"] = array(
			"host" 		=> "localhost",
			"database" 	=> "testeMVC",
			"usuario" 	=> "root",
			"senha" 	=> ""
		);

		$this->config["localhostORACLE"] = array(
			"host" 		=> "127.0.0.1",
			"usuario" 	=> "root",
			"senha" 	=> "",
			"port" 		=> "1521",
			"sid" 		=> "XE"
		);

		return $this->config;
	}
}