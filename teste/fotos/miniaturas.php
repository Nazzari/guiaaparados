<?php
$fonte = "";
$arquivos=0;
$miniaturas = 0;

foreach (glob ($fonte."*.jpg") as $arquivo){
	$a[$i] = $arquivo;
	
	$pos_u = strpos($a[$i], "_u.jpg");
	$pos_p = strpos($a[$i], "_p.jpg");
	
	if(($pos_u) or ($pos_p)) {
		unlink($a[$i]);
		$miniaturas++;
	}
	
	$i++;
}
echo "<br /><br /><p>Existem ". count($a) ." Arquivos! <br /> Sendo que ".$miniaturas." são miniaturas e foram excluidas</p>";
?>

