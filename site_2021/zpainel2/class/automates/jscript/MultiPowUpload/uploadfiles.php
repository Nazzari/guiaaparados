<?php
include "../../../inc.conexao.php";
include "../../../automates/inc.automates.php";
$cAutomates = new cAutomates($_POST['tabela']);

$caminhoFotos = "../../../".$_POST['caminho'];

//die;
// In PHP versions earlier than 4.1.0, $HTTP_POST_FILES should be used instead
// of $_FILES.

echo 'Upload result:<br>'; // At least one symbol should be sent to response!!!

$target_encoding = "ISO-8859-1";
echo '<pre>';
print_r($_FILES);
// die();
$vezes = count($_FILES);
$cont = $vezes;
for ($i=0; $i < $cont ; $i++) {
	$arrfile = $_FILES[$i];
	$alfabeto = array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z");
	$extensao = explode(".",$arrfile['name']);
	
	$extensao = strtolower($extensao[count($extensao)-1]);
	$nmArquivo = $_POST['tabela'].date("_Y_m_d__H_i_s_")."c".$_POST['codigo']."_r".str_pad(rand(0,99),2,"0",STR_PAD_LEFT).$alfabeto[rand(0,count($alfabeto)-1)].str_pad(rand(0,99),2,"0",STR_PAD_LEFT).$alfabeto[rand(0,count($alfabeto)-1)].str_pad(rand(0,99),2,"0",STR_PAD_LEFT).$alfabeto[rand(0,count($alfabeto)-1)].str_pad(rand(0,99),2,"0",STR_PAD_LEFT).$alfabeto[rand(0,count($alfabeto)-1)].".".$extensao;
	
	move_uploaded_file($arrfile['tmp_name'], $caminhoFotos.$nmArquivo);
	
	$dados = array();
	$dados["".$_POST['campoRelacao'].""] = $_POST['codigo'];
	$dados["".$_POST['campoArquivo'].""] = "'".$nmArquivo."'";
	$cAutomates->mAtualizaDados($dados);
	
	echo $i.'<br>';
	print_r($nmArquivo);
}
echo "</pre>";
?>