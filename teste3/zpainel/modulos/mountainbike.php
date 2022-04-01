<?
session_start("zpainel");

include "../class/inc.conexao.php";
include "../class/automates/inc.automates.php";

//autenticaчуo de seguranчa
if (!$_SESSION['sessionZpainelLogin']) exit("Sem conexуo com o servidor. Favor fazer login novamente!");

$cAutomates = new cAutomates("mountainbike");
$cAutomates->v_html_extra_campo["ds_mountainbike"] = "style=\"width: 90%; height: 400px;\"";

$cAutomates->mAdmin();
?>