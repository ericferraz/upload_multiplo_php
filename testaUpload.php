<?php  require('utilidades/Upload.class.php');
 ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Testa upload</title>
</head>

<body>
	<form method="post" enctype="multipart/form-data">
    <!-- só funciona corretamente se tiver essa propriedade multiple e for um array, exemplo img[]-->
		<input type="file" multiple  name="img[]">
		<button name="enviar">enviar</button>
	</form>
</body>
</html>
<?php

if(isset($_POST['enviar'])){

	$imagens = $_FILES['img'];

	/**
	 Você pode passar somente um diretório como sendo a primeira posição do array
	 nesse caso a imagem irá para esse diretório, é preciso que o número de diretórios, seja igual ao
	  número de dimensões
	  $dimensao = array(900);
	  $pasta = array("banners/")
	*/
	
	/**
	 Nesse outro caso, quando existirem mais de um diretório, cada minuatura sera movida para 
	 os subdiretórios, sendo o primeiro diretório sempre considerado como pricipal, e os demais
	 serão criados dentro dele.
	*/
	$dimensao = array(900,800,400,200);
	$pasta = array("galeria/","grande/","media/","pequena/");

	$instancia  = Upload::getInstancia($imagens,$pasta,$dimensao);
	$retorno = $instancia->fazerUpload();
	print_r($retorno);
	
	if(empty($retorno)){
	  echo "Nenhuma imagem válida foi selecionada!";
	}
	
	
}

?>