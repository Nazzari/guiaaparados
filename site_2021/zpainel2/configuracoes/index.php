<?
session_start("zpainel");
if (!$_SESSION['sessionZpainelLogin']) exit();
if ($_SESSION['sessionZpainelLogin'] != "zaib") exit();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
	<title>Configurações</title>
	<link rel="stylesheet" type="text/css" href="../class/automates/css/screen.css" />
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

</head>
<body style="background-image: none; background-color: #e9ecef;">
<div class="" style="padding-top: 2%; padding-left: 2%; padding-right: 2%">
<form class="cmxform">
	
		<div class="titulosSistemas">Configurações</div>
		<p>
			<input type="button" value="Projeto" class="btn btn-primary" onclick="window.location = 'projeto.php?action=update&codigo=1';" />
			<input type="button" value="Módulos" class="btn btn-primary" onclick="window.location = 'modulos.php?';" />
		</p>

</form>
</div>
</body>
</html>