<?
session_start("zpainel");

include "../class/inc.conexao.php";
include "../class/automates/inc.automates.php";

//autenticaчуo de seguranчa
if (!$_SESSION['sessionZpainelLogin']) exit("Sem conexуo com o servidor. Favor fazer login novamente!");

$cAutomates = new cAutomates("passeios");
$cAutomates->v_html_extra_campo["ds_passeio"] = "style=\"width: 520px; height: 300px;\"";

$cAutomates->mAdmin();
?>