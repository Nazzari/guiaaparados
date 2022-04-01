<?
header("Content-Type: text/html; charset=iso-8859-1",true);
session_start("zpainel");

switch($_GET['zaib-tecnologia']) {

	/**********************************
	AJUDA
	***********************************/
	case "ajuda":
	include "class/inc.template.php";
	$cTpl = new cTemplate("tpl.ajuda.htm");
	$cTpl->mShow();
	break;

	/**********************************
	SAIR
	***********************************/
	case "sair":
	if ($_SESSION['sessionZpainelLogin']) {
		$_SESSION['sessionZpainelLogin'] = null;
		$_SESSION['sessionZpainelNome'] = null;
		$_SESSION['sessionZpainelDataHora'] = null;
	}
	header("location: ../");
	break;

	/**********************************
	ADMINISTRAÇÃO
	***********************************/
	case "administração":
	if ($_SESSION['sessionZpainelLogin']) {
		include "class/inc.template.php";
		include "class/inc.conexao.php";
		include "class/automates/inc.automates.php";
		$cTpl = new cTemplate("tpl.admin.htm");
		$cAutomates = new cAutomates("zpainel_config");
		$titulo = $cAutomates->mRetornaDados();
		$cTpl->mSet("tituloSite",$titulo[0]["nm_zpainel_config"]);

		$cTpl->mSet("sessionZpainelNome",$_SESSION['sessionZpainelNome']);
		$cTpl->mSet("sessionZpainelDataHora",$_SESSION['sessionZpainelDataHora']);
		$cTpl->mShow("inicio");

		$cAutomates = new cAutomates("zpainel_links");
		$cAutomatesModulos = new cAutomates("zpainel_modulos");

			// if ($_SESSION['sessionZpainelLogin'] == "zaib") 
		if ($_SESSION['sessionZpainelLogin'] == "zaib") 
		{ 
			$links = $cAutomates->mRetornaDados(); 
			$categorias = $cAutomatesModulos->mRetornaDados();
			$cTpl->mShow("configuracoes");
		}
		else {

			$cAutomatesUsuario = new cAutomates("zpainel_usuarios");

			$dadosUsuario = $cAutomatesUsuario->mRetornaDados("","login_usuario_zpainel = '".$_SESSION['sessionZpainelLogin']."' AND situacao_usuario_zpainel = 'Ativo'");

			$linksMenuUsuario = explode(",",$dadosUsuario[0]["cd_link_zpainel"]);
			$linksMenuUsuario = implode("' OR cd_link_zpainel = '",$linksMenuUsuario);
			$links = $cAutomates->mRetornaDados("","cd_link_zpainel = '".$linksMenuUsuario."'");

			$modulosMenuUsuario = explode(",",$dadosUsuario[0]["cd_modulo_zpainel"]);
			$modulosMenuUsuario = implode("' OR cd_modulo_zpainel = '",$modulosMenuUsuario);
			$categorias = $cAutomatesModulos->mRetornaDados("","cd_modulo_zpainel = '".$modulosMenuUsuario."'");

		}
		for($i=0;$i<count($links);$i++) {
			$cTpl->mSet("nm_link_zpainel",$links[$i]["nm_link_zpainel"]);
			$cTpl->mSet("link_link_zpainel",$links[$i]["link_link_zpainel"]);
			$cTpl->mShow("links");
		}
		$cTpl->mShow("links_fim");

			//categorias
		for($i=0;$i<count($categorias);$i++) {
			$cTpl->mSet("nome_modulo",$categorias[$i]["nm_modulo_zpainel"]);
			$cTpl->mSet("link_modulo",$categorias[$i]["link_modulo_zpainel"]);
			$cTpl->mShow("while_menu");
			//itens
			$cAutomates = new cAutomates("zpainel_modulos_itens");
			$itens = $cAutomates->mRetornaDados("","zpainel_modulos_itens.cd_modulo_zpainel = '".$categorias[$i]["cd_modulo_zpainel"]."'","zpainel_modulos_itens.cd_item_modulo_zpainel ASC");
			for($j=0;$j<count($itens);$j++) {
				$cTpl->mSet("nome_modulo_link", $itens[$j]["nm_item_modulo_zpainel"]);
				$cTpl->mSet("modulo_link_acao", $itens[$j]["link_item_modulo_zpainel"]);
				$cTpl->mShow("while_submenu");	
			}
			$cTpl->mShow("fim_menu");
		}
		$cTpl->mShow("fim");

	} else {
		header("location: ?zaib-tecnologia=autenticação");
	}
	break;

	/**********************************
	MENU XML
	***********************************/
	case "menu-xml":
	if ($_SESSION['sessionZpainelLogin']) {
		include "class/inc.conexao.php";
		include "class/automates/inc.automates.php";


		$cAutomates = new cAutomates("zpainel_modulos");
		if ($_SESSION['sessionZpainelLogin'] == "zaib") { $categorias = $cAutomates->mRetornaDados(); }
		else {
			$cAutomatesUsuario = new cAutomates("zpainel_usuarios");
			$dadosUsuario = $cAutomatesUsuario->mRetornaDados("","login_usuario_zpainel = '".$_SESSION['sessionZpainelLogin']."' AND situacao_usuario_zpainel = 'Ativo'");
			$modulosMenuUsuario = explode(",",$dadosUsuario[0]["cd_modulo_zpainel"]);
			$modulosMenuUsuario = implode("' OR cd_modulo_zpainel = '",$modulosMenuUsuario);
			$categorias = $cAutomates->mRetornaDados("","cd_modulo_zpainel = '".$modulosMenuUsuario."'");
		}
	} else {
		exit();
	}
	break;


	/**********************************
	AUTENTICAÇÃO
	***********************************/
	case "autenticação":
	if ($_GET['submit']) {
		if (($_POST['zpainel_login'] != "") && ($_POST['zpainel_pass'] != "")) {
			
			include "class/inc.conexao.php";
			include "class/automates/inc.automates.php";
			$cAutomates = new cAutomates("zpainel_usuarios");
			$dadosUsuario = $cAutomates->mRetornaDados("","(login_usuario_zpainel = '".$_POST['zpainel_login']."' AND pass_usuario_zpainel = '".$_POST['zpainel_pass']."' AND situacao_usuario_zpainel = 'Ativo')");
			if (count($dadosUsuario) > 0) {
				$_SESSION['sessionZpainelLogin'] = $_POST['zpainel_login'];
				$_SESSION['sessionZpainelNome'] = $dadosUsuario[0]["nm_usuario_zpainel"];
				$_SESSION['sessionZpainelDataHora'] = date("d/m/Y H:i");
				header("location: ?zaib-tecnologia=administração");
			} else {
				$s_obj = xmlLoadFile("http://www2.zaib.com.br/zpainel/autentica_zaib.xml");

				if (((string) $s_obj->logar->login == md5($_POST['zpainel_login'])) && ((string) $s_obj->logar->password == md5($_POST['zpainel_pass']))) {
					$_SESSION['sessionZpainelLogin'] = $_POST['zpainel_login'];
					$_SESSION['sessionZpainelNome'] = (string) $s_obj->logar->name;
					$_SESSION['sessionZpainelDataHora'] = date("d/m/Y H:i");
					header("location: ./?zaib-tecnologia=administração"); 
				} else {
					header("location: ./?zaib-tecnologia=autenticação&f=y");
				}
			}
		} else {
			exit();
		}
	} else {
		if ($_SESSION['sessionZpainelLogin']) {
			header("location: ?zaib-tecnologia=administração");
		} else {
			include "class/inc.template.php";
			include "class/inc.conexao.php";
			include "class/automates/inc.automates.php";
			
			$cTpl = new cTemplate("tpl.login.htm");
			$cTpl->mSet("destino","index.php?zaib-tecnologia=autenticação&submit=true");
			
			$cAutomates = new cAutomates("zpainel_config");
			$titulo = $cAutomates->mRetornaDados();
			$cTpl->mSet("tituloSite",$titulo[0]["nm_zpainel_config"]);
			
			if ($_GET['f'] == "y") $cTpl->mSet("falha","<script type=\"text/javascript\">alert(\"Nome de usuário ou senha inválidos!\");</script>");
			
			$cTpl->mShow();
		}
	}
	break;

	/**********************************
		DEFAULT
		***********************************/
		default:
		if ($_SESSION['sessionZpainelLogin']) header("location: ?zaib-tecnologia=administração");
		else header("location: ?zaib-tecnologia=autenticação");
		break;


	}

	function xmlLoadFile($f) {
		$aux = !function_exists("simplexml_load_file");

    // para sites com falha na funcao "simplexml_load_file"
		$aux = 1;
		if ($aux) {
			require("class/inc.simplexml.php");
			$sx = new simplexml;
			return $sx->xml_load_file($f);
		} else {
			return simplexml_load_file($f);
		}
	}

	?>