<?
$pagina = $_GET['pagina'];
if (empty($pagina)) $pagina = "mapa";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">						
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt" lang="pt">
<head>
	<title>Guia Aparados da Serra</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<meta http-equiv="Content-Language" content="pt-br" />
	<meta http-equiv="Cache-Control" content="no-cache, no-store" />
	<meta http-equiv="Pragma" content="no-cache, no-store" />
	<meta name="description" content="" />
	<meta name="keywords" content="" />
	<meta name="robots" content="index, follow" />
	<meta name="author" content="Zaib Tecnologia" />
	<style type="text/css">
		body { margin: 0; padding: 0;}
		p { margin: 0; padding: 0; width: 100%; height: 25px; float: left; display: inline; margin-top: 5px; font: 13px Arial, Helvetica, sans-serif;}
		p a { color: #666666; }
		p a:hover { color: #010101; }
		#flashMapa { float: left; display: inline; }
	</style>
	<script type="text/javascript" src="../jscript/flash.js"></script>
</head>
<body>
<script type="text/javascript">exibeFlash("carrega.swf?pagina=<? print($pagina); ?>.swf","775","500","flashMapa");</script>

</body>
</html>