<?php
/**
 *
 *Classe que contém métodos estaticos, para serem utilizados em diversos pontos da
 *aplicação
 *@autor Eric Luiz Ferras<ciencias_exatas@hotmail.com.br>
 *@version 1.0
 *@since  09/04/2014
 */
class Utilidades{

	/**
	 *
	 *Método contrutor, privado para impedir o objeto
	 *de ser instânciada
	 *@autor Eric Luiz Ferras<ciencias_exatas@hotmail.com.br>
	 *@version 1.0
	 *@since  09/05/2014
	 *
	 */
	private function __construct(){

	}


	/**
	 *
	 *Método responsável por deletar arquivos
	 *@autor Eric Luiz Ferras<ciencias_exatas@hotmail.com.br>
	 *@version 1.0
	 *@since  09/05/2014
	 *@param $pasta
	 *@param $arquivo
	 *@return true or false
	 *
	 */
	public static function deletarArquivo($pasta,$arquivo){
		if(!file_exists($pasta)){
			return false;
		}
		if(!file_exists($pasta.$arquivo)){
			return false;
		}
		if(@unlink($pasta.$arquivo)){
			return true;
		}
		return false;
	}

	/**
	 *
	 *Método responsável por retornar uma senha aleatória
	 *@autor Eric Luiz Ferras<ciencias_exatas@hotmail.com.br>
	 *@version 1.0
	 *@since  11/04/2014
	 *
	 */
	public static  function geraSenha($tamanho = 8, $maiusculas = true, $numeros = true, $simbolos = false){

		// Caracteres de cada tipo
		$lmin = 'abcdefghijklmnopqrstuvwxyz';
		$lmai = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$num = '1234567890';
		$simb = '!@#$%*-';

		// Variáveis internas
		$retorno = '';
		$caracteres = '';

		// Agrupamos todos os caracteres que poderão ser utilizados
		$caracteres .= $lmin;

		if ($maiusculas) $caracteres .= $lmai;
		if ($numeros) $caracteres .= $num;
		if ($simbolos) $caracteres .= $simb;

		// Calculamos o total de caracteres possíveis
		$len = strlen($caracteres);

		for ($n = 1; $n <= $tamanho; $n++) {
			// Criamos um número aleatório de 1 até $len para pegar um dos caracteres
			$rand = mt_rand(1, $len);

			// Concatenamos um dos caracteres na variável $retorno
			$retorno .= $caracteres[$rand-1];
		}
		return $retorno;

	}
}