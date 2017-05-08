<?php
# Ricardo Herrero em 11/2010

class DatasUtils{
	/** Calcular a diferença entre duas datas. Fazemos uma reversão da maior sobre a menor para não ter resultado negativo. */
	public function calculaDias($xDataInicial, $xDataFinal, $ignore = false){
		$time1 = $this->dataToTimestamp($xDataInicial);
		$time2 = $this->dataToTimestamp($xDataFinal);

		if ($ignore) {
			$diff = $time1 - $time2;
			return $diff / 86400;
		}

		// Converte para retornar sempre numeros positivos
		$tMaior = $time1 > $time2 ? $time1 : $time2;
		$tMenor = $time1 < $time2 ? $time1 : $time2;

		$diff = $tMaior - $tMenor;
		// $diff = $time1 - $time2;

		// Dia em segundos
		return $diff / 86400;
	}

	//LISTA DE FERIADOS NO ANO
	/** Criar um array para registrar todos os feriados nacionais durante o ano. */
	private function feriados($ano, $posicao){
		$dia = 86400;
		$datas = array();

		$datas['pascoa'] = easter_date($ano);
		$datas['sexta_santa'] = $datas['pascoa'] - (2 * $dia);
		$datas['carnaval'] = $datas['pascoa'] - (47 * $dia);
		$datas['corpus_cristi'] = $datas['pascoa'] + (60 * $dia);

		$feriados = array (
			'01/01',
			date('d/m',$datas['carnaval']),
			date('d/m',$datas['sexta_santa']),
			date('d/m',$datas['pascoa']),
			'21/04',
			'01/05',
			date('d/m',$datas['corpus_cristi']),
			'07/09',
			'12/10',
			'02/11',
			'15/11',
			'20/11',
			'25/12',
		);

		return $feriados[$posicao]."/".$ano;
	}

	//FORMATA COMO TIMESTAMP
	/** Formatar a data em TimeStamp para facilitar a soma de dias para uma data. */
	public function dataToTimestamp($data){
		$ano = substr($data, 6, 4);
		$mes = substr($data, 3, 2);
		$dia = substr($data, 0, 2);

		return mktime(0, 0, 0, $mes, $dia, $ano);
	}

	//SOMA 01 DIA
	private function somaDia($data){
		$ano = substr($data, 6, 4);
		$mes = substr($data, 3, 2);
		$dia = substr($data, 0, 2);

		return date("d/m/Y", mktime(0, 0, 0, $mes, $dia + 1, $ano));
	}

	//CALCULA DIAS UTEIS
	/** Fazer cálculo de dias após fazer a comparação verificando se dia é sábado, domingo ou feriado (caso positivo incrementa contador) */
	public function calcularDiasUteis($yDataInicial, $yDataFinal){
		//dias não úteis(Sábado=6 Domingo=0)
		$diaFDS = 0;
		//número de dias entre a data inicial e a final
		$calculoDias = $this->calculaDias($yDataInicial, $yDataFinal);
		$diasUteis = 0;

		while($yDataInicial!=$yDataFinal){
			$diaSemana = date("w", $this->dataToTimestamp($yDataInicial));

			if($diaSemana == 0 || $diaSemana == 6){
				//se SABADO OU DOMINGO, SOMA 01
				$diaFDS++;
			}else{
				//senão vemos se este dia é FERIADO
				for($i = 0; $i <= 12; $i++){
					if($yDataInicial == $this->feriados(date("Y"), $i)){
						$diaFDS++;
					}
				}
			}

			//dia + 1
			$yDataInicial = $this->somaDia($yDataInicial);
		}

		return $calculoDias - $diaFDS;
	}
}
?>