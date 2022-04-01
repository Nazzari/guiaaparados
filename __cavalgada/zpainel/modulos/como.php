<?
session_start("zpainel");

include "../class/inc.conexao.php";
include "../class/automates/inc.automates.php";

//autenticação de segurança
if (!$_SESSION['sessionZpainelLogin']) exit("Sem conexão com o servidor. Favor fazer login novamente!");

$cAutomates = new cAutomates("como_chegar");
$cAutomates->v_html_extra_campo["ds_como"] = "style=\"width: 90%; height: 400px;\"";

$cAutomates->mAdmin();
?>