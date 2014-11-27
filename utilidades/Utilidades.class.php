<?php
/**
 *
 *Classe que cont�m m�todos estaticos, para serem utilizados em diversos pontos da
 *aplica��o
 *@autor Eric Luiz Ferras<ciencias_exatas@hotmail.com.br>
 *@version 1.0
 *@since  09/04/2014
 */
class Utilidades{

	/**
	 *
	 *M�todo contrutor, privado para impedir o objeto
	 *de ser inst�nciada
	 *@autor Eric Luiz Ferras<ciencias_exatas@hotmail.com.br>
	 *@version 1.0
	 *@since  09/05/2014
	 *
	 */
	private function __construct(){

	}


	/**
	 *
	 *M�todo respons�vel por deletar arquivos
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
	 *M�todo respons�vel por retornar uma senha aleat�ria
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

		// Vari�veis internas
		$retorno = '';
		$caracteres = '';

		// Agrupamos todos os caracteres que poder�o ser utilizados
		$caracteres .= $lmin;

		if ($maiusculas) $caracteres .= $lmai;
		if ($numeros) $caracteres .= $num;
		if ($simbolos) $caracteres .= $simb;

		// Calculamos o total de caracteres poss�veis
		$len = strlen($caracteres);

		for ($n = 1; $n <= $tamanho; $n++) {
			// Criamos um n�mero aleat�rio de 1 at� $len para pegar um dos caracteres
			$rand = mt_rand(1, $len);

			// Concatenamos um dos caracteres na vari�vel $retorno
			$retorno .= $caracteres[$rand-1];
		}
		return $retorno;

	}
}