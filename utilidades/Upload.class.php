<?php
/**
 *
 *Classe que representa um objeto responsável pelo upload de imagens
 *@autor Eric Luiz Ferras<ciencias_exatas@hotmail.com.br>
 *@version 1.0
 *@since  15/04/2014
 */

final class Upload{

	private $pasta = array();
	private $imagem;
	private $nome;
	private $nomeTemp;
	private $tamanho;
	private $tipo;
	private $novoNome;
	private $local;
	private $cont = 0;
	private $erros;
	private $envios;
	private $sucesso;
	private $dir = "uploads/";
	private $dimensao = array();


	/*retornara uma instancia do proprio objeto*/
	private static $instancia;

	/*array de imagens que será preenchido e retornado*/
	private  $arrayImagens = array();

	/**
	 *
	 *Método contrutor responsável por inicializar o objeto com algumas propriedades
	 *@autor Eric Luiz Ferras<ciencias_exatas@hotmail.com.br>
	 *@version 1.0
	 *@since  15/04/2014
	 *
	 */
	public function __construct($imagem,$pasta,$dimensao){
		$this->imagem    = $imagem;
		$this->dimensao  = $dimensao;
		$this->pasta 	 = $pasta;
		$this->cont      = count($imagem['name']);
		$this->erros     = 0;
		$this->envios    = 0;
		$this->sucesso   = 0;

		$this->criarDiretorio();
	}

	/**
	 *
	 *Método responsável por retornar uma instância do proprio objeto
	 *@autor Eric Luiz Ferras<ciencias_exatas@hotmail.com.br>
	 *@version 1.0
	 *@since  15/04/2014
	 *@param: imagens vindas do form
	 *@param array de pastas
	 *@param array dimensoes
	 *obs: o numero de dimensoes passadas, deve ser igual ao número de pastas
	 *exemplo:
	 * $pastas = array("imagens/","grande/","media/","pequena");
	 * $dimensao = array(900,800,600,300);
	 */

	public static function getInstancia(array $imagem,array $pasta,array $dimensao){
		self::$instancia = new Upload($imagem,$pasta,$dimensao);
		return(self::$instancia);
	}

	/**
	 *
	 *Método responsável por verificar se um diretório já existe
	 *se não existir deverá cria-ló da seguinte forma:
	 *1º o primeiro diretório ficará dentro do diretório principal: uploads/pasta1
	 *2º se for passado mais de 1 diretório, esses outros diretórios ficarão
	 *da seguinte forma:
	 * uploads/pasta1/subPasta1
	 * uploads/pasta1/subPasta2
	 *@autor Eric Luiz Ferras<ciencias_exatas@hotmail.com.br>
	 *@version 1.0
	 *@since  15/04/2014
	 *
	 */
	private function criarDiretorio(){
		$cont = 0;
		for($i=0;$i<count($this->pasta);$i++){
			if($cont==0){
				if(!is_dir($this->dir.$this->pasta[0])){
					mkdir($this->dir.$this->pasta[0],0777,true);
				}
			}else{
				if(!is_dir($this->dir.$this->pasta[0].$this->pasta[$i])){
					mkdir($this->dir.$this->pasta[0].$this->pasta[$i],0777,true);
				}
			}
			$cont++;
		}
	}

	/**
	 *
	 *Método responsável por validar a extensão das imagens
	 *@autor Eric Luiz Ferras<ciencias_exatas@hotmail.com.br>
	 *@version 1.0
	 *@since  02/05/2014
	 *@param imagem
	 *@return true or false
	 *
	 */
	private function validarImagem($imagem){
		$imagens = array(

			"image/png",
			"image/jpg",
		 	"image/gif",
			"image/jpeg",
			"image/pjpeg"
		
			);

			//se a imagem estiver em qualquer posição do array de imagens, será válida
			if(in_array($imagem, $imagens)){
				return true;
			}
			return false;
	}

	/**
	 *
	 *Método responsável por válidar o tamanho da imagem
	 *@autor Eric Luiz Ferras<ciencias_exatas@hotmail.com.br>
	 *@version 1.0
	 *@since  02/05/2014
	 *@param tamanho da imagem
	 *@return true or false
	 *
	 */
	private function validarTamanho($size){
		//em mb(fica fácil para alterar o tamanho máximo da imagem)
		$mb = 7;
		$kb = $mb*1024;
		//por fim o tamanho em bites, que será usado na validação
		$bites = $kb*1024;

		//passou na validação
		if($size <=$bites){
			return true;
		}
		return  false;
	}

	/**
	 *
	 *Método responsável por fazer upload
	 *@autor Eric Luiz Ferras<ciencias_exatas@hotmail.com.br>
	 *@version 1.0
	 *@since  15/04/2014
	 *@return arrayDeImagens
	 */

	public function fazerUpload(){

		//conta o total elementos do tipo dimensão passadas como parametro
		$contarDimensoes = count($this->dimensao);

		/* Recebe os n arquivos*/
		for($i=0;$i<$this->cont;$i++){
			//valida o tamanho dos arquivos
			if($this->validarTamanho($this->imagem['size'][$i])){
				//verifica se as imagens são válidas
				if($this->validarImagem($this->imagem['type'][$i])){
					@$this->nome     = $this->imagem['name'][$i];
					@$this->nomeTemp = $this->imagem['tmp_name'][$i];
					@$this->tamanho  = $this->imagem['size'][$i];
					@$this->tipo     = $this->imagem['type'][$i];

					if(move_uploaded_file($this->nomeTemp,$this->dir.$this->pasta[0].$this->nome)){

						/*Posso executar o método redimensionar*/
						$img = $this->carregarImagem($this->dir.$this->pasta[0].$this->nome,$this->tipo);

						/*Salvo todas imagens com essa extenção*/
						$ext = '.jpg';

						if ($img) {
							$nome = md5(uniqid(rand(), 1));

							$this->arrayImagens[] = $nome.$ext;

							flush();

							/*Para redimensionar a mesma imagem, mais de 1 vez seguida, é preciso estar em diretórios diferentes*/
							//$this->criarMiniatura($img,$this->dimensao[0],$this->dir.$this->pasta[0].$nome);

							for($j=0;$j<$contarDimensoes;$j++){
								//precisa saber se está sendo passado mais de 1 pasta/dimensão para upload
								if ($contarDimensoes<=1){
									//se está sendo passado no mínimo 1 direório/dimensão, significa que este diretório será o unico dentro de uplods/
									$this->criarMiniatura($img,$this->dimensao[$j],$this->dir.$this->pasta[$j].$nome);

								}else{
									//estão sendo passados mais de 1 diretório/dimensão
									if($j>0){
										$this->criarMiniatura($img,$this->dimensao[$j],$this->dir.$this->pasta[0].$this->pasta[$j].$nome);
									}
								}

							}

							imagedestroy($img);

							/* Esvazia da memória a cada looop*/
							@unlink($this->nomeTemp);
							@unlink($this->tipo);

							/*O arquivo é movido para pasta, de lá são feitos os redimensionamentos, então é preciso apagar o arquivo
							 *depois de terminada cada alteração
							 *caso contrário, ficará sujeira, algo inutil no diretório.
							 */
							@unlink($this->dir.$this->pasta[0].$this->nome);

						}//if carregar img

					}//fim move_uploaded

				}//fim if validar tipo
			}//fim if validar tamanho
		}//fim for

		return $this->arrayImagens;

	}

	/**
	 *
	 *Método responsável por carregar corretamente o tipo de arquivo para ser manipulado
	 *@autor Eric Luiz Ferras<ciencias_exatas@hotmail.com.br>
	 *@autor Marlon David Oliveira<marlon_dg_oliveira@outlook.com>
	 *@version 1.0
	 *@since  15/04/2014
	 *@param arquivo
	 *@param tipo
	 *@return imagecreate
	 */
	private function carregarImagem($arquivo, $tipo) {
		if ($tipo == 'image/jpeg' || $tipo == 'image/pjpeg' || $tipo == 'image/jpg')
		return imagecreatefromjpeg($arquivo);
		elseif ($tipo == 'image/png')
		return imagecreatefrompng($arquivo);
		elseif ($tipo == 'image/gif')
		return imagecreatefromgif($arquivo);
	}

	/**
	 *
	 *Método responsável por criar a miniatura
	 *@autor Eric Luiz Ferras<ciencias_exatas@hotmail.com.br>
	 *@autor Marlon David Oliveira<marlon_dg_oliveira@outlook.com>
	 *@version 1.0
	 *@since  15/04/2014
	 *@param (OBJETO_IMAGEM, TAMANHO, NOME)
	 *
	 */
	private function criarMiniatura($i, $s, $nome) {
		$o_x = imagesx($i); // largura original
		$o_y = imagesy($i); // altura original

		$m_x = $s;
		$m_y = $s;
		if ($o_x <= $s && $o_y <= $s) {
			$m_x = $o_x;
			$m_y = $o_y;
		}
		elseif ($s>100) {
			if ($o_x > $o_y)
			$m_y = round($o_y / ($o_x / $m_x));
			else if ($o_y > $o_x)
			$m_x = round($o_x / ($o_y / $m_y));
		}

		$mini = imagecreatetruecolor($m_x, $m_y);

		imagealphablending($mini, true); // Definir que a imagem aceita transparência
		imagefill($mini, 0, 0, 0xFFFFFF); // Pintar o fundo de branco (caso a imagem carregada tenha transparência)

		imagecopyresampled($mini, $i, 0, 0, 0, 0, $m_x, $m_y, $o_x, $o_y);
		imagejpeg($mini, $nome.'.jpg', 75);

		imagedestroy($mini);
	}
	/**
	 *
	 *Método responsável por redimensionar a imagem
	 *@autor Eric Luiz Ferras<ciencias_exatas@hotmail.com.br>
	 *@autor Marlon David Oliveira<marlon_dg_oliveira@outlook.com>
	 *@version 1.0
	 *@since  15/04/2014
	 */

	private function redimensionarImagem($i, $larg, $alt) {
		$o_x = imagesx($i); // largura original
		$o_y = imagesy($i); // altura original

		$mini = imagecreatetruecolor($larg, $alt);

		imagealphablending($mini, true); // Definir que a imagem aceita transparência
		imagefill($mini, 0, 0, 0xFFFFFF); // Pintar o fundo de branco (caso a imagem carregada tenha transparência)

		imagecopyresampled($mini, $i, 0, 0, 0, 0, $larg, $alt, $o_x, $o_y);

		return $mini;
	}


}

?>