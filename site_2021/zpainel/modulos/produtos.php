<?
session_start("zpainel");

include "../class/inc.conexao.php";
include "../class/automates/inc.automates.php";

//autenticação de segurança
if (!$_SESSION['sessionZpainelLogin']) exit("Sem conexão com o servidor. Favor fazer login novamente!");

$cAutomates = new cAutomates("produtos");
$cAutomates->v_html_extra_campo["ds_produto"] = "style=\"width: 520px; height: 300px;\"";

$cAutomates->mAdmin();
?>