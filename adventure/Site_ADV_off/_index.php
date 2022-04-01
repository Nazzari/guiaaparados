<?
include "zpainel/class/inc.template.php";
include "zpainel/class/inc.conexao.php";
include "zpainel/class/inc.tools.php";
include "zpainel/class/inc.url.php";
include "zpainel/class/automates/inc.automates.php";

$cToo = new cTools;
$cUrl = new cUrl;
$cCon = new cConexao;

include "zpainel/class/inc.gd.php";
$cGd = new cGd;

$caminhoFotos = "fotos/";

switch($cUrl->mGetParam(1)) {
	
	/**************************
	   CONTATO
	**************************/
	case "contato":
		if ($cUrl->mGetParam(2) == "enviar") {
			if ($_POST['nome'] != "") {
				//GRAVA NO BANCO
				$cAutomates = new cAutomates("contato");
				$dados["dt_faleconosco"] = "'".date("Y-m-d H:i:s")."'";
				$dados["nm_faleconosco"] = "'".$_POST["nome"]."'";
				$dados["tel_faleconosco"] = "'".$_POST["telefone"]."'";
				$dados["em_faleconosco"] = "'".$_POST["email"]."'";
				$dados["cid_faleconosco"] = "'".$_POST["cidade"]."'";
				$dados["uf_faleconosco"] = "'".$_POST["uf"]."'";
				$dados["dt_ini_faleconosco"] = "'".$_POST["data_ini"]."'";
				$dados["dt_fim_faleconosco"] = "'".$_POST["data_fim"]."'";
				$dados["numero_faleconosco"] = "'".$_POST["numeroPessoas"]."'";
				$dados["assunto_faleconosco"] = "'".$_POST["assunto"]."'";
				
				$dados["mensagem_faleconosco"] = "'".$_POST["mensagem"]."'";
				$cAutomates->mAtualizaDados($dados);
				
				//MONTA EMAIL
				$cTpl = new cTemplate("tpl.email.htm");
				$cTpl->mSet("site","Guia Aparados da Serra - Adventure Aparados");
				$cTpl->mSet("pagina","Contato");
				$cTpl->mSet("hora",date("H:i"));
				$cTpl->mSet("dia",date("d/m/Y"));
				
				$conteudo.= "<p><strong>Nome</strong><br />".$_POST["nome"]."</p>";
				$conteudo.= "<p><strong>Telefone/Fax</strong><br />".$_POST["telefone"]."</p>";
				$conteudo.= "<p><strong>Email</strong><br />".$_POST["email"]."</p>";
				$conteudo.= "<p><strong>Cidade</strong><br />".$_POST["cidade"]."</p>";
				$conteudo.= "<p><strong>UF</strong><br />".$_POST["uf"]."</p>";
				$conteudo.= "<p><strong>Possivel Data da Chegada</strong><br />".$_POST["data_ini"]."</p>";				
				$conteudo.= "<p><strong>Possivel Data da Saida</strong><br />".$_POST["data_fim"]."</p>";
				$conteudo.= "<p><strong>Numero de Pessoas</strong><br />".$_POST["numeroPessoas"]."</p>";
				$conteudo.= "<p><strong>Assuntos</strong><br />".$_POST["assunto"]."</p>";
				
				
				$conteudo.= "<p><strong>Observações</strong><br />".$_POST["mensagem"]."</p>";
				
				
				$cTpl->mSet("conteudo",$conteudo);
				$body = $cTpl->mShow("",1);
				
				include "zpainel/class/inc.phpmailer.php";
				$PHPMailer = new PHPMailer();
				
				$PHPMailer->From = "sistema@guiaaparadosdaserra.com.br";
                $PHPMailer->FromName = "sistema@guiaaparadosdaserra.com.br";
                $PHPMailer->Password = "si123654789";
                $PHPMailer->Port = "587";

				$cAutomates = new cAutomates("contato_dados");
				$faleconosco = $cAutomates->mRetornaDados();
				$PHPMailer->AddAddress($faleconosco[0]["email_faleconosco_dados"]);
				
				$PHPMailer->Subject = "Guia Aparados da Serra - Adventure Aparados (Contato)";
				$PHPMailer->Body = $body;
				$PHPMailer->AltBody = $body;
				$PHPMailer->WordWrap = 50;
				
				if (!$PHPMailer->Send()) {
					print("Erro ao enviar email, favor tentar novamente!<br />");
					print("Erro: " . $PHPMailer->ErrorInfo . "");
				} else {
					header("location: /contato/ok/");
				}
			} else {
				header("location: /contato/");
			}
		} else {
			include "principalInicio.php";
			
			$cTpl = new cTemplate("tpl.contato.htm");
			
			$cAutomates = new cAutomates("contato_dados");
			$contato = $cAutomates->mRetornaDados();
			
			$cTpl->mSet("descricao",nl2br($contato[0]['ds_faleconosco_dados']));
			
			$cTpl->mShow("inicio");
			
			include "principalFim.php";
			
			if ($cUrl->mGetParam(2) == "ok") $cToo->mAlert("Enviado com sucesso! Aguarde uma resposta de nossa equipe. Obrigado!");
			
		}
	break;
	
	
	/**************************
	   DEPOIMENTO
	**************************/
	case "depoimentos":
		if ($cUrl->mGetParam(2) == "enviar") {
			if ($_POST['nome'] != "") {
				//GRAVA NO BANCO
				$cAutomates = new cAutomates("depoimentos");
				$dados["data_depoimento"] = "'".date("Y-m-d H:i:s")."'";
				$dados["nome_depoimento"] = "'".$_POST["nome"]."'";
				$dados["email_depoimento"] = "'".$_POST["email"]."'";
				$dados["cidade_depoimento"] = "'".$_POST["cidade"]."'";
				$dados["estado_depoimento"] = "'".$_POST["uf"]."'";
				
				$dados["texto_depoimento"] = "'".$_POST["mensagem"]."'";
				$cAutomates->mAtualizaDados($dados);
				
				//MONTA EMAIL
				$cTpl = new cTemplate("tpl.email.htm");
				$cTpl->mSet("site","Guia Aparados da Serra - Adventure Aparados");
				$cTpl->mSet("pagina","Depoimentos");
				$cTpl->mSet("hora",date("H:i"));
				$cTpl->mSet("dia",date("d/m/Y"));
				
				$conteudo.= "<p><strong>Nome</strong><br />".$_POST["nome"]."</p>";
				$conteudo.= "<p><strong>Email</strong><br />".$_POST["email"]."</p>";
				$conteudo.= "<p><strong>Cidade</strong><br />".$_POST["cidade"]."</p>";
				$conteudo.= "<p><strong>UF</strong><br />".$_POST["uf"]."</p>";
				$conteudo.= "<p><strong>Depoimento</strong><br />".$_POST["mensagem"]."</p>";
				
				
				$cTpl->mSet("conteudo",$conteudo);
				$body = $cTpl->mShow("",1);
				
				include "zpainel/class/inc.phpmailer.php";
				$PHPMailer = new PHPMailer();
				$PHPMailer->From = $_POST["email"];
				$PHPMailer->FromName = $_POST["nome"];
				$cAutomates = new cAutomates("contato_dados");
				$faleconosco = $cAutomates->mRetornaDados();
				$PHPMailer->AddAddress($faleconosco[0]["email_faleconosco_dados"]);
				
				$PHPMailer->Subject = "Guia Aparados da Serra - Adventure Aparados (Depoimento)";
				$PHPMailer->Body = $body;
				$PHPMailer->AltBody = $body;
				$PHPMailer->WordWrap = 50;
				
				if (!$PHPMailer->Send()) {
					print("Erro ao enviar email, favor tentar novamente!<br />");
					print("Erro: " . $PHPMailer->ErrorInfo . "");
				} else {
					header("location: /depoimentos/ok/");
				}
			} else {
				header("location: /depoimentos/");
			}	
		} else {
			include "principalInicio.php";
			
			$cTpl = new cTemplate("tpl.depoimento.htm");
			
			$cAutomates = new cAutomates("contato_dados");
			$contato = $cAutomates->mRetornaDados();
			$cTpl->mShow("inicio");

			$cAutoDepo = new cAutomates("depoimentos");
			$var = $cAutoDepo->mRetornaDados("", "online_depoimento='Sim'");
			
			for ($i=0; $i<count($var); $i++){
				$cTpl->mSet("nome", $var[$i]['nome_depoimento']);
				$cTpl->mSet("cidade", $var[$i]['cidade_depoimento']);
				$cTpl->mSet("estado", $var[$i]['estado_depoimento']);
				$cTpl->mSet("texto", $var[$i]['texto_depoimento']);
				
				$cTpl->mShow("depoimentos");
			}
			$cTpl->mShow("fim_depoimentos");
			
			include "principalFim.php";
			
			if ($cUrl->mGetParam(2) == "ok") $cToo->mAlert("Enviado com sucesso! Aguarde uma resposta de nossa equipe. Obrigado!");
			
		}
	break;
	
	
	/***************************
		ROTEIRO FAMILIA
	***************************/
	case "excursao-para-grupos":
		include "principalInicio.php";
			
		$cTpl = new cTemplate("tpl.excursao.htm");
		$cTpl->mShow("inicio");
		
		$cAutomates = new cAutomates("excursao_fotos");
		$fotos = $cAutomates->mRetornaDados();
		
		if (count($fotos) > 0) {
			
			$cTpl->mShow("foto");
			for ($i=0; $i < count($fotos); $i++) { 
				$cTpl->mSet("tt_foto",$fotos[$i]['tt_foto']);
				$cTpl->mSet("foto",$cGd->mGeraGd($caminhoFotos,$fotos[$i]['nm_foto'],168,1));
				$cTpl->mSet("link",$cGd->mGeraGd($caminhoFotos,$fotos[$i]['nm_foto'],500,3));
				$cTpl->mShow("while");				
			}
			$cTpl->mShow("fim_while");
				
		} 		
		
		$cAutomates = new cAutomates("excursao");
		$excursao = $cAutomates->mRetornaDados();
		
		$cTpl->mSet("descricao",nl2br($excursao[0]['ds_excursao']));
		
		
		$cTpl->mShow("fim");
		
		include "principalFim.php";
	break;
	
	/***************************
		ROTEIRO FAMILIA
	***************************/
	case "roteiro-familia":
		include "principalInicio.php";
			
		$cTpl = new cTemplate("tpl.familia.htm");
		$cTpl->mShow("inicio");
		
		$cAutomates = new cAutomates("familia_fotos");
		$fotos = $cAutomates->mRetornaDados();
		
		if (count($fotos) > 0) {
			
			$cTpl->mShow("foto");
			for ($i=0; $i < count($fotos); $i++) { 
				$cTpl->mSet("tt_foto",$fotos[$i]['tt_foto']);
				$cTpl->mSet("foto",$cGd->mGeraGd($caminhoFotos,$fotos[$i]['nm_foto'],168,1));
				$cTpl->mSet("link",$cGd->mGeraGd($caminhoFotos,$fotos[$i]['nm_foto'],500,3));
				$cTpl->mShow("while");				
			}
			$cTpl->mShow("fim_while");
				
		} 		
		
		$cAutomates = new cAutomates("familia");
		$familia = $cAutomates->mRetornaDados();
		
		$cTpl->mSet("descricao",nl2br($familia[0]['ds_familia']));
		
		
		$cTpl->mShow("fim");
		
		include "principalFim.php";
	break;
		
	/***************************
		TRANSLADO TRANSFER
	***************************/
	case "translado-transfer":
		include "principalInicio.php";
			
		$cTpl = new cTemplate("tpl.translado.htm");
		$cTpl->mShow("inicio");
		
		$cAutomates = new cAutomates("translado_fotos");
		$fotos = $cAutomates->mRetornaDados();
		
		if (count($fotos) > 0) {
			
			$cTpl->mShow("foto");
			for ($i=0; $i < count($fotos); $i++) { 
				$cTpl->mSet("tt_foto",$fotos[$i]['tt_foto']);
				$cTpl->mSet("foto",$cGd->mGeraGd($caminhoFotos,$fotos[$i]['nm_foto'],168,1));
				$cTpl->mSet("link",$cGd->mGeraGd($caminhoFotos,$fotos[$i]['nm_foto'],500,3));
				$cTpl->mShow("while");				
			}
			$cTpl->mShow("fim_while");
				
		} 		
		
		$cAutomates = new cAutomates("translado");
		$translado = $cAutomates->mRetornaDados();
		
		$cTpl->mSet("descricao",nl2br($translado[0]['ds_translado']));
		
		
		$cTpl->mShow("fim");
		
		include "principalFim.php";
	break;
	
	/***************************
		4x4
	***************************/
	case "4x4":
		include "principalInicio.php";
			
		$cTpl = new cTemplate("tpl.4x4.htm");
		$cTpl->mShow("inicio");
		
		$cAutomates = new cAutomates("expedicao_fotos");
		$fotos = $cAutomates->mRetornaDados();
		
		if (count($fotos) > 0) {
			
			$cTpl->mShow("foto");
			for ($i=0; $i < count($fotos); $i++) { 
				$cTpl->mSet("tt_foto",$fotos[$i]['tt_foto']);
				$cTpl->mSet("foto",$cGd->mGeraGd($caminhoFotos,$fotos[$i]['nm_foto'],168,1));
				$cTpl->mSet("link",$cGd->mGeraGd($caminhoFotos,$fotos[$i]['nm_foto'],500,3));
				$cTpl->mShow("while");				
			}
			$cTpl->mShow("fim_while");
				
		} 		
		
		$cAutomates = new cAutomates("expedicao");
		$expedicao = $cAutomates->mRetornaDados();
		
		$cTpl->mSet("descricao",nl2br($expedicao[0]['ds_expedicao']));
		
		
		$cTpl->mShow("fim");
		
		include "principalFim.php";
	break;
	
	/***************************
		RAPEL
	***************************/
	case "rapel":
		include "principalInicio.php";
			
		$cTpl = new cTemplate("tpl.rapel.htm");
		$cTpl->mShow("inicio");
		
		$cAutomates = new cAutomates("rapel_fotos");
		$fotos = $cAutomates->mRetornaDados();
		
		if (count($fotos) > 0) {
			
			$cTpl->mShow("foto");
			for ($i=0; $i < count($fotos); $i++) { 
				$cTpl->mSet("tt_foto",$fotos[$i]['tt_foto']);
				$cTpl->mSet("foto",$cGd->mGeraGd($caminhoFotos,$fotos[$i]['nm_foto'],168,1));
				$cTpl->mSet("link",$cGd->mGeraGd($caminhoFotos,$fotos[$i]['nm_foto'],500,3));
				$cTpl->mShow("while");				
			}
			$cTpl->mShow("fim_while");
				
		} 		
		
		$cAutomates = new cAutomates("rapel");
		$rapel = $cAutomates->mRetornaDados();
		
		$cTpl->mSet("descricao",nl2br($rapel[0]['ds_rapel']));
		
		
		$cTpl->mShow("fim");
		
		include "principalFim.php";
	break;
	
	
	/***************************
		CANIONISMO
	***************************/
	case "canionismo":
		include "principalInicio.php";
			
		$cTpl = new cTemplate("tpl.canionismo.htm");
		$cTpl->mShow("inicio");
		
		$cAutomates = new cAutomates("canionismo_fotos");
		$fotos = $cAutomates->mRetornaDados();
		
		if (count($fotos) > 0) {
			
			$cTpl->mShow("foto");
			for ($i=0; $i < count($fotos); $i++) { 
				$cTpl->mSet("tt_foto",$fotos[$i]['tt_foto']);
				$cTpl->mSet("foto",$cGd->mGeraGd($caminhoFotos,$fotos[$i]['nm_foto'],168,1));
				$cTpl->mSet("link",$cGd->mGeraGd($caminhoFotos,$fotos[$i]['nm_foto'],500,3));
				$cTpl->mShow("while");				
			}
			$cTpl->mShow("fim_while");
				
		} 		
		
		$cAutomates = new cAutomates("canionismo");
		$canionismo = $cAutomates->mRetornaDados();
		
		$cTpl->mSet("descricao",nl2br($canionismo[0]['ds_canionismo']));
		
		
		$cTpl->mShow("fim");
		
		include "principalFim.php";
	break;
	
	/***************************
		NIVEL DAS ATIVIDADES
	***************************/
	case "nivel-das-atividades":
		include "principalInicio.php";
			
		$cTpl = new cTemplate("tpl.nivel.htm");
		$cTpl->mShow("inicio");
		
		$cAutomates = new cAutomates("nivel_fotos");
		$fotos = $cAutomates->mRetornaDados();
		
		if (count($fotos) > 0) {
			
			$cTpl->mShow("foto");
			for ($i=0; $i < count($fotos); $i++) { 
				$cTpl->mSet("tt_foto",$fotos[$i]['tt_foto']);
				$cTpl->mSet("foto",$cGd->mGeraGd($caminhoFotos,$fotos[$i]['nm_foto'],168,1));
				$cTpl->mSet("link",$cGd->mGeraGd($caminhoFotos,$fotos[$i]['nm_foto'],500,3));
				$cTpl->mShow("while");				
			}
			$cTpl->mShow("fim_while");
				
		} 		
		
		$cAutomates = new cAutomates("nivel");
		$nivel = $cAutomates->mRetornaDados();
		
		$cTpl->mSet("descricao",nl2br($nivel[0]['ds_nivel']));
		
		
		$cTpl->mShow("fim");
		
		include "principalFim.php";
	break;
	
	/**************************
	 AVENTURAS PERSONALIZADAS
	**************************/
	case "aventuras-personalizadas":
		include "principalInicio.php";
			
		$cTpl = new cTemplate("tpl.personalizados.htm");
		$cTpl->mShow("inicio");
		
		$cAutomates = new cAutomates("personalizados_fotos");
		$fotos = $cAutomates->mRetornaDados();
		
		if (count($fotos) > 0) {
			
			$cTpl->mShow("foto");
			for ($i=0; $i < count($fotos); $i++) { 
				$cTpl->mSet("tt_foto",$fotos[$i]['tt_foto']);
				$cTpl->mSet("foto",$cGd->mGeraGd($caminhoFotos,$fotos[$i]['nm_foto'],168,1));
				$cTpl->mSet("link",$cGd->mGeraGd($caminhoFotos,$fotos[$i]['nm_foto'],500,3));
				$cTpl->mShow("while");				
			}
			$cTpl->mShow("fim_while");
				
		} 		
		
		$cAutomates = new cAutomates("personalizados");
		$personalizados = $cAutomates->mRetornaDados();
		
		$cTpl->mSet("descricao",nl2br($personalizados[0]['ds_personalizado']));
		
		
		$cTpl->mShow("fim");
		
		include "principalFim.php";
	break;	
	
	/**************************
		PACOTES
	**************************/
	case "pacotes":
		include "principalInicio.php";
		
		if ($cUrl->mGetParam(2)) {
		
			$cTpl = new cTemplate("tpl.pacotesInt.htm");
			$cTpl->mShow("inicio");
			
			$cAutomates = new cAutomates("pacotes_fotos");
			$fotos = $cAutomates->mRetornaDados("","cd_pacote ='".$cUrl->mGetParam(2)."'");
			
			if (count($fotos) > 0) {
				
				$cTpl->mShow("foto");
				for ($i=0; $i < count($fotos); $i++) { 
					$cTpl->mSet("tt_foto",$fotos[$i]['tt_foto']);
					$cTpl->mSet("foto",$cGd->mGeraGd($caminhoFotos,$fotos[$i]['nm_foto'],168,1));
					$cTpl->mSet("link",$cGd->mGeraGd($caminhoFotos,$fotos[$i]['nm_foto'],500,3));
					$cTpl->mShow("while");				
				}
				$cTpl->mShow("fim_while");
					
			} 		
			
			$cAutomates = new cAutomates("pacotes");
			$pacote = $cAutomates->mRetornaDados($cUrl->mGetParam(2));
			
			$cTpl->mSet("nome",$pacote[0]['nm_pacote']);
			$cTpl->mSet("descricao",nl2br($pacote[0]['ds_pacote']));
			
			
			$cTpl->mShow("fim");
			
		} else {
			
			$cTpl = new cTemplate("tpl.pacotes.htm");
			$cTpl->mShow("inicio");
			
			$cAutomates = new cAutomates("pacotes");
			$pacote = $cAutomates->mRetornaDados();
			
			for ($i=0; $i < count($pacote); $i++) { 
				$cTpl->mShow("while");
				
				$cAutomates = new cAutomates("pacotes_fotos");
				$fotos = $cAutomates->mRetornaDados("","cd_pacote ='".$pacote[$i]['cd_pacote']."'");
				$cTpl->mSet("link","pacotes/".$pacote[$i]['cd_pacote']."/".$cUrl->mTrataString($pacote[$i]['nm_pacote']).".html");
				
				
				if(count($fotos) > 0) {
					$cTpl->mSet("foto",$cGd->mGeraGd($caminhoFotos,$fotos[0]['nm_foto'],168,1));
					$cTpl->mShow("foto");
				}
				
				$cTpl->mSet("nome",$pacote[$i]['nm_pacote']);
				$cTpl->mSet("descricao",$cAutomates->mBreveDescricao($pacote[$i]['ds_pacote'],400));
				$cTpl->mShow("dados");
			}
			
			$cTpl->mShow("fim");
		
		}
		include "principalFim.php";
	break;
	
	/**************************
		PASSEIOS
	**************************/
	case "passeios":
		include "principalInicio.php";
		
		if ($cUrl->mGetParam(2)) {
			
			$cTpl = new cTemplate("tpl.passeiosInt.htm");
			$cTpl->mShow("inicio");
			
			$cAutomates = new cAutomates("passeios_fotos");
			$fotos = $cAutomates->mRetornaDados("","cd_passeio ='".$cUrl->mGetParam(2)."'");
			
			if (count($fotos) > 0) {
				
				$cTpl->mShow("foto");
				for ($i=0; $i < count($fotos); $i++) { 
					$cTpl->mSet("tt_foto",$fotos[$i]['tt_foto']);
					$cTpl->mSet("foto",$cGd->mGeraGd($caminhoFotos,$fotos[$i]['nm_foto'],168,1));
					$cTpl->mSet("link",$cGd->mGeraGd($caminhoFotos,$fotos[$i]['nm_foto'],500,3));
					$cTpl->mShow("while");				
				}
				$cTpl->mShow("fim_while");
					
			} 		
			
			$cAutomates = new cAutomates("passeios");
			$passeio = $cAutomates->mRetornaDados($cUrl->mGetParam(2));
			
			$cTpl->mSet("nome",$passeio[0]['nm_passeio']);
			$cTpl->mSet("descricao",nl2br($passeio[0]['ds_passeio']));
			
			
			$cTpl->mShow("fim");
			
		} else {
			
			$cTpl = new cTemplate("tpl.passeios.htm");
			$cTpl->mShow("inicio");
			
			$cAutomates = new cAutomates("passeios");
			$passeio = $cAutomates->mRetornaDados();
			
			for ($i=0; $i < count($passeio); $i++) { 
				$cTpl->mShow("while");
				
				$cAutomates = new cAutomates("passeios_fotos");
				$fotos = $cAutomates->mRetornaDados("","cd_passeio ='".$passeio[$i]['cd_passeio']."'");
				$cTpl->mSet("link","passeios/".$passeio[$i]['cd_passeio']."/".$cUrl->mTrataString($passeio[$i]['nm_passeio']).".html");
				
				
				if(count($fotos) > 0) {
					$cTpl->mSet("foto",$cGd->mGeraGd($caminhoFotos,$fotos[0]['nm_foto'],168,1));
					$cTpl->mShow("foto");
				}
				
				$cTpl->mSet("nome",$passeio[$i]['nm_passeio']);
				$cTpl->mSet("descricao",$cAutomates->mBreveDescricao($passeio[$i]['ds_passeio'],400));
				$cTpl->mShow("dados");
			}
			
			$cTpl->mShow("fim");
		
		}
		include "principalFim.php";
	break;
	
	/**************************
		INFORMACOES
	**************************/
	case "informacoes":
		include "principalInicio.php";
			
		$cTpl = new cTemplate("tpl.informacoes.htm");
		$cTpl->mShow("inicio");
		
		$cAutomates = new cAutomates("informe_fotos");
		$fotos = $cAutomates->mRetornaDados();
		
		if (count($fotos) > 0) {
			
			$cTpl->mShow("foto");
			for ($i=0; $i < count($fotos); $i++) { 
				$cTpl->mSet("tt_foto",$fotos[$i]['tt_foto']);
				$cTpl->mSet("foto",$cGd->mGeraGd($caminhoFotos,$fotos[$i]['nm_foto'],168,1));
				$cTpl->mSet("link",$cGd->mGeraGd($caminhoFotos,$fotos[$i]['nm_foto'],500,3));
				$cTpl->mShow("while");				
			}
			$cTpl->mShow("fim_while");
				
		} 		
		
		$cAutomates = new cAutomates("informe");
		$informe = $cAutomates->mRetornaDados();
		
		$cTpl->mSet("descricao",nl2br($informe[0]['ds_informe']));
		
		
		$cTpl->mShow("fim");
		
		include "principalFim.php";
	break;
	
	/**************************
		FOTOS
	**************************/
	case "fotos":
		include "principalInicio.php";
			
		$cTpl = new cTemplate("tpl.fotos.htm");
		
		$cAutomates = new cAutomates("galeria");
		$galeria = $cAutomates->mRetornaDados("");
		
		$cTpl->mSet("descricao",nl2br($galeria[0]['ds_galeria']));
		
		$cTpl->mShow("inicio");
		
		$cAutomates = new cAutomates("galeria_fotos");
		$fotos = $cAutomates->mRetornaDados();
		
		for ($i=0; $i < count($fotos); $i++) { 
			$cTpl->mSet("tt_foto",$fotos[$i]['tt_foto']);
			$cTpl->mSet("foto",$cGd->mGeraGd($caminhoFotos,$fotos[$i]['nm_foto'],168,1));
			$cTpl->mSet("link",$cGd->mGeraGd($caminhoFotos,$fotos[$i]['nm_foto'],500,3));
			$cTpl->mShow("while");	
		}
		$cTpl->mShow("fim");
		
		
		include "principalFim.php";
	break;
	
	/**************************
		Guias
	**************************/
	case "guias":
		include "principalInicio.php";
		
		if ($cUrl->mGetParam(2)) {
			
			$cTpl = new cTemplate("tpl.guiasInt.htm");
			$cTpl->mShow("inicio");
			
			$cAutomates = new cAutomates("guias_fotos");
			$fotos = $cAutomates->mRetornaDados("","cd_guias ='".$cUrl->mGetParam(2)."'");
			
			if (count($fotos) > 0) {
				
				$cTpl->mShow("foto");
				for ($i=0; $i < count($fotos); $i++) { 
					$cTpl->mSet("tt_foto",$fotos[$i]['tt_foto']);
					$cTpl->mSet("foto",$cGd->mGeraGd($caminhoFotos,$fotos[$i]['nm_foto'],168,1));
					$cTpl->mSet("link",$cGd->mGeraGd($caminhoFotos,$fotos[$i]['nm_foto'],500,3));
					$cTpl->mShow("while");				
				}
				$cTpl->mShow("fim_while");
					
			} 		
			
			$cAutomates = new cAutomates("guias");
			$guias = $cAutomates->mRetornaDados($cUrl->mGetParam(2));
			
			$cTpl->mSet("nome",$guias[0]['nm_guias']);
			$cTpl->mSet("descricao",nl2br($guias[0]['ds_guias']));
			
			
			$cTpl->mShow("fim");
			
		} else {
			
			$cTpl = new cTemplate("tpl.guias.htm");
			$cTpl->mShow("inicio");
			
			$cAutomates = new cAutomates("guias");
			$guias = $cAutomates->mRetornaDados();
			
			for ($i=0; $i < count($guias); $i++) { 
				$cTpl->mShow("while");
				
				$cAutomates = new cAutomates("guias_fotos");
				$fotos = $cAutomates->mRetornaDados("","cd_guias ='".$guias[$i]['cd_guias']."'");
				$cTpl->mSet("link","guias/".$guias[$i]['cd_guias']."/".$cUrl->mTrataString($guias[$i]['nm_guias']).".html");
				
				
				if(count($fotos) > 0) {
					$cTpl->mSet("foto",$cGd->mGeraGd($caminhoFotos,$fotos[0]['nm_foto'],168,1));
					$cTpl->mShow("foto");
				}
				
				$cTpl->mSet("nome",$guias[$i]['nm_guias']);
				$cTpl->mSet("descricao",$cAutomates->mBreveDescricao($guias[$i]['ds_guias'],400));
				$cTpl->mShow("dados");
			}
			
			$cTpl->mShow("fim");
		
		}
		include "principalFim.php";
	break;
	
	/**************************
		REPORTAGENS
	**************************/
	case "reportagens":
		include "principalInicio.php";
		
		if ($cUrl->mGetParam(2)) {
			
			$cTpl = new cTemplate("tpl.reportagensInt.htm");			
			
			$cAutomates = new cAutomates("reportagens");
			$reportagens = $cAutomates->mRetornaDados($cUrl->mGetParam(2));
			
			$cTpl->mSet("nome",$reportagens[0]['tt_reportagens']);
			$cTpl->mSet("descricao",nl2br($reportagens[0]['ds_reportagens']));
			
			$cTpl->mShow("inicio");
			
			$cAutomates = new cAutomates("reportagens_fotos");
			$fotos = $cAutomates->mRetornaDados();
			
			for ($i=0; $i < count($fotos); $i++) { 
				$cTpl->mSet("tt_foto",$fotos[$i]['tt_foto']);
				$cTpl->mSet("foto",$cGd->mGeraGd($caminhoFotos,$fotos[$i]['nm_foto'],168,1));
				$cTpl->mSet("link",$cGd->mGeraGd($caminhoFotos,$fotos[$i]['nm_foto'],800,3));
				$cTpl->mShow("while");	
			}
			$cTpl->mShow("fim");
			
		} else {
			
			$cTpl = new cTemplate("tpl.reportagens.htm");
			$cTpl->mShow("inicio");
			
			$cAutomates = new cAutomates("reportagens");
			$reportagens = $cAutomates->mRetornaDados();
			
			for ($i=0; $i < count($reportagens); $i++) { 
				$cTpl->mShow("while");
				
				$cAutomates = new cAutomates("reportagens_fotos");
				$fotos = $cAutomates->mRetornaDados("","cd_reportagens ='".$reportagens[$i]['cd_reportagens']."'");
				$cTpl->mSet("link","reportagens/".$reportagens[$i]['cd_reportagens']."/".$cUrl->mTrataString($reportagens[$i]['tt_reportagens']).".html");
				
				
				if(count($fotos) > 0) {
					$cTpl->mSet("foto",$cGd->mGeraGd($caminhoFotos,$fotos[0]['nm_foto'],168,1));
					$cTpl->mShow("foto");
				}
				
				$cTpl->mSet("nome",$reportagens[$i]['tt_reportagens']);
				$cTpl->mSet("descricao",$cAutomates->mBreveDescricao($reportagens[$i]['ds_reportagens'],400));
				$cTpl->mShow("dados");
			}
			
			$cTpl->mShow("fim");
		
		}
		include "principalFim.php";
	break;
	
	/**************************
		TREKKING
	**************************/
	case "trekking":
		include "principalInicio.php";
			
		$cTpl = new cTemplate("tpl.trekking.htm");
		$cTpl->mShow("inicio");
		
		$cAutomates = new cAutomates("trekkings_fotos");
		$fotos = $cAutomates->mRetornaDados();
		
		if (count($fotos) > 0) {
			
			$cTpl->mShow("foto");
			for ($i=0; $i < count($fotos); $i++) { 
				$cTpl->mSet("tt_foto",$fotos[$i]['tt_foto']);
				$cTpl->mSet("foto",$cGd->mGeraGd($caminhoFotos,$fotos[$i]['nm_foto'],168,1));
				$cTpl->mSet("link",$cGd->mGeraGd($caminhoFotos,$fotos[$i]['nm_foto'],500,3));
				$cTpl->mShow("while");				
			}
			$cTpl->mShow("fim_while");
				
		} 		
		
		$cAutomates = new cAutomates("trekkings");
		$trekkings = $cAutomates->mRetornaDados();
		
		$cTpl->mSet("descricao",nl2br($trekkings[0]['ds_trekkings']));
		
		
		$cTpl->mShow("fim");
		
		include "principalFim.php";
	break;
	
	/**************************
		DESTAQUES XML
	**************************/
	case "destaques-xml":
		print("
		<?xml version=\"1.0\" encoding=\"UTF-8\"?>
		<gallery title=\"\" frameColor=\"0xE7E9EA\" frameWidth=\"0\" imagePadding=\"20\" displayTime=\"4\">");
		
		$cAutomates = new cAutomates("destaques");
		$destaques = $cAutomates->mRetornaDados();
		
		for ($i=0;$i<count($destaques);$i++) {
			$dimensoes = getimagesize($caminhoFotos.$destaques[$i]["arq_destaque"]);
			print("
				<image>
				   <filename>".$destaques[$i]["arq_destaque"]."</filename>
				   <caption></caption>
				   <width>".$dimensoes[0]."</width>
				   <height>".$dimensoes[1]."</height>
				</image>
			");
		}

		print("
		</gallery>
		");
	break;
	
	/**************************
		PÁGINA INICIAL
	**************************/
	default:
		include "principalInicio.php";
			
		$cTpl = new cTemplate("tpl.principal.htm");
		
		
		$cAutomates = new cAutomates("capa");
		$capa = $cAutomates->mRetornaDados();
		
		for ($i=0; $i < count($capa); $i++) {
			
			$cAutomates = new cAutomates("pacotes");
			$pacotes = $cAutomates->mRetornaDados($capa[$i]['cd_pacote']);
			
			$cTpl->mSet("nome",$pacotes[0]['nm_pacote']);
			$cTpl->mSet("descricao",$cAutomates->mBreveDescricao($pacotes[0]['ds_pacote'],180-strlen($pacotes[0]['nm_pacote'])));	
			$cTpl->mSet("link","pacotes/".$pacotes[0]['cd_pacote']."/".$cUrl->mTrataString($pacotes[0]['nm_pacote']).".html");
			
			$cAutomates = new cAutomates("pacotes_fotos");
			$foto = $cAutomates->mRetornaDados("","cd_pacote ='".$pacotes[0]['cd_pacote']."'");	
			
			if (count($foto) > 0) {
				$cTpl->mSet("foto",$cGd->mGeraGd($caminhoFotos,$foto[0]['nm_foto'],159,2));
			} else {
				$cTpl->mSet("foto","imagens/imgNaoDisponivel.jpg");
			}
			
			$cTpl->mShow("while");
		}
		$cTpl->mShow("fim_while");
		
		$cAutomates = new cAutomates("capa_texto");
		$texto = $cAutomates->mRetornaDados();	
		
		$cTpl->mSet("video1",$texto[0]['lk_1_capa_texto']);
		// $cTpl->mSet("video2",$texto[0]['lk_2_capa_texto']);

		
		$cTpl->mShow("videos");
		
		
		include "principalFim.php";
	break;
}
?>