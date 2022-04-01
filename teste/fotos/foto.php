<?php

/* m2brimagem.class.php */
include_once('../zpainel/class/inc.imagem.php');
$arquivo = $_GET['src'];
$largura = $_GET['w'];
$altura = $_GET['h'];
$qualidade = $_GET['q'];
$modo = $_GET['m'];

/* validacao pneutur */
if (!is_file($arquivo) || (!$arquivo)) {
    $arquivo = 'nao_disponivel.jpg';
}

/* pre-validacao */
if (!$arquivo) {
    exit('Arquivo não existente.');
}

if (!$largura) {
    $largura = 100;
}

if (!$altura) {
    $altura = 75;
}

if (!$qualidade) {
    $qualidade = 85;
}

if (!$modo) {
    $modo = 'p';
}

/* verificacao de criacao */
$nome = explode('.', $arquivo);
$ext = strtolower($nome[1]);
$nome = ($nome[0] . '_' . $largura . '_' . $altura . '_' . $qualidade . '_' . $modo . '.' . $nome[1]);

/* confirma data de ultima alteracao */
$create = true;
if (is_file($nome)){
    $old = filemtime($arquivo);
    $new = filemtime($nome);
    
    $create = !($old < $new);
}

/* caso nao tenha, cria */
if ($create) {
    $img = new m2brimagem($arquivo);
    $val = $img->valida();
    if ($val == 'OK') {
        $img->redimensiona($largura, $altura, $modo);
        $img->grava($nome, $qualidade);
    } else {
        exit($val);
    }
}

switch ($ext) {
    case 'jpg':
    case 'jpeg':
    case 'bmp':
        header("Content-type: image/jpeg");
        break;
    case 'png':
        header("Content-type: image/png");
        break;
    case 'gif':
        header("Content-type: image/gif");
        break;
    default:
        exit('Extensão inválida.');
}

readfile($nome);

exit;
?>
