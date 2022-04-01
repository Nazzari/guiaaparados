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



	/*********************************
        VÍDEOS
    **********************************/
    case "videos":

        include "principalInicio.php";

        $cTpl = new cTemplate("tpl.videos.htm");
        $cTpl->mShow("inicio");
        $cAutomates = new cAutomates("videos");
        $tvs = $cAutomates->mRetornaDados();
        
        for ($i=0; $i <count($tvs) ; $i++) { 
            
            $youtube = explode('/', $tvs[$i]["lk_video"]);
            $youtube = array_pop($youtube);
            $cTpl->mSet("youtube", $youtube);

            $cTpl->mShow("while_video");
        }
        $cTpl->mShow("fim_while");

        include "principalFim.php";


        break;
	
	/*********************************
        CONTATO
    **********************************/
    // case "contato":

    //     include "principalInicio.php";

    //     $cTpl = new cTemplate("tpl.contato.htm");
        
    //     $cTpl->mShow("inicio");

    //     $cTpl->mShow("fim");

    //     include "principalFim.php";


    //     break;



	
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


                $cAutomates = new cAutomates("contato_dados");
                $faleconosco = $cAutomates->mRetornaDados();
                
                $headers = "MIME-Version: 1.1\n";
                $headers .= "Content-type: text/html; charset=iso-8859-1\n";
                $headers .= "From: sistema@guiaaparadosdaserra.com.br\n";

                $assunto = "Guia Aparados da Serra - Adventure Aparados (Contato)";

                if (!mail($faleconosco[0]["email_faleconosco_dados"],$assunto,$body,$headers, "-r"."sistema@guiaaparadosdaserra.com.br")){

                    $headers .= "Return-Path: sistema@guiaaparadosdaserra.com.br\n";

                    if (! mail($faleconosco[0]["email_faleconosco_dados"],$assunto,$body,$headers) ){
                        print_r("Erro ao enviar emails.");
                    }
                    else {
                        header("location: /contato/ok/");
                    }
                }
                else{
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
		O COSTAO
	***************************/
	case "o-costao":
		include "principalInicio.php";
			
		$cTpl = new cTemplate("tpl.texto-fotos.htm");

        $cTpl->mSet("titulo_interna", "O Costão");

        $cAutomates = new cAutomates("excursao");
        $excursao = $cAutomates->mRetornaDados();
        
        $cTpl->mSet("descricao",nl2br($excursao[0]['ds_excursao']));
		$cTpl->mShow("inicio");
		
		$cAutomates = new cAutomates("excursao_fotos");
		$fotos = $cAutomates->mRetornaDados();
		
		if (count($fotos) > 0) {
			
			$cTpl->mShow("foto");
			for ($i=0; $i < count($fotos); $i++) { 
				$cTpl->mSet("tt_foto",$fotos[$i]['tt_foto']);
				$cTpl->mSet("foto",$cGd->mGeraGd($caminhoFotos,$fotos[$i]['nm_foto'],360,1));
				$cTpl->mSet("link",$cGd->mGeraGd($caminhoFotos,$fotos[$i]['nm_foto'],500,3));
				$cTpl->mShow("while");				
			}
			$cTpl->mShow("fim_while");
				
		} 		
		

		
		
		$cTpl->mShow("fim");
		
		include "principalFim.php";
	break;
	
	/***************************
		ATIVIDADES
	***************************/
	case "atividades":
		include "principalInicio.php";
			
		$cTpl = new cTemplate("tpl.texto-fotos.htm");

        $cTpl->mSet("titulo_interna","Atividades");

        $cAutomates = new cAutomates("familia");
        $familia = $cAutomates->mRetornaDados();
        
        $cTpl->mSet("descricao",nl2br($familia[0]['ds_familia']));

		$cTpl->mShow("inicio");
		
		$cAutomates = new cAutomates("familia_fotos");
		$fotos = $cAutomates->mRetornaDados();
		
		if (count($fotos) > 0) {
			
			$cTpl->mShow("foto");
			for ($i=0; $i < count($fotos); $i++) { 
				$cTpl->mSet("tt_foto",$fotos[$i]['tt_foto']);
				$cTpl->mSet("foto",$cGd->mGeraGd($caminhoFotos,$fotos[$i]['nm_foto'],360,1));
				$cTpl->mSet("link",$cGd->mGeraGd($caminhoFotos,$fotos[$i]['nm_foto'],500,3));
				$cTpl->mShow("while");				
			}
			$cTpl->mShow("fim_while");
				
		} 		
		
		
		
		
		$cTpl->mShow("fim");
		
		include "principalFim.php";
	break;
		
	/***************************
		TRANSLADO TRANSFER
	***************************/
	case "translado-transfer":
		include "principalInicio.php";
			
		$cTpl = new cTemplate("tpl.texto-fotos.htm");

        $cTpl->mSet("titulo_interna", "Translado/Transfer");
        $cAutomates = new cAutomates("translado");
        $translado = $cAutomates->mRetornaDados();
        
        $cTpl->mSet("descricao",nl2br($translado[0]['ds_translado']));
		$cTpl->mShow("inicio");
		
		$cAutomates = new cAutomates("translado_fotos");
		$fotos = $cAutomates->mRetornaDados();
		
		if (count($fotos) > 0) {
			
			$cTpl->mShow("foto");
			for ($i=0; $i < count($fotos); $i++) { 
				$cTpl->mSet("tt_foto",$fotos[$i]['tt_foto']);
				$cTpl->mSet("foto",$cGd->mGeraGd($caminhoFotos,$fotos[$i]['nm_foto'],360,1));
				$cTpl->mSet("link",$cGd->mGeraGd($caminhoFotos,$fotos[$i]['nm_foto'],500,3));
				$cTpl->mShow("while");				
			}
			$cTpl->mShow("fim_while");
				
		} 		
		
		
		
		
		$cTpl->mShow("fim");
		
		include "principalFim.php";
	break;
	
	/***************************
		PASSAGENS
	***************************/
	case "passagens":
		include "principalInicio.php";
			
		$cTpl = new cTemplate("tpl.texto-fotos.htm");

        $cTpl->mSet("titulo_interna", "Passagens Aéreas");

        $cAutomates = new cAutomates("passagens");
        $translado = $cAutomates->mRetornaDados();
        
        $cTpl->mSet("descricao",nl2br($translado[0]['ds_passagens']));

		$cTpl->mShow("inicio");
		
		$cAutomates = new cAutomates("passagens_fotos");
		$fotos = $cAutomates->mRetornaDados();
		
		if (count($fotos) > 0) {
			
			$cTpl->mShow("foto");
			for ($i=0; $i < count($fotos); $i++) { 
				$cTpl->mSet("tt_foto",$fotos[$i]['tt_foto']);
				$cTpl->mSet("foto",$cGd->mGeraGd($caminhoFotos,$fotos[$i]['nm_foto'],360,1));
				$cTpl->mSet("link",$cGd->mGeraGd($caminhoFotos,$fotos[$i]['nm_foto'],500,3));
				$cTpl->mShow("while");				
			}
			$cTpl->mShow("fim_while");
				
		} 		
		
		
		
		
		$cTpl->mShow("fim");
		
		include "principalFim.php";
	break;
	
	/***************************
		QUEM SOMOS
	***************************/
	case "quem-somos":
		include "principalInicio.php";
			
		$cTpl = new cTemplate("tpl.quem-somos.htm");

        $cTpl->mSet("titulo_interna","Quem Somos");

        $cAutomates = new cAutomates("capa");
		$capa = $cAutomates->mRetornaDados();

		$cAutomates = new cAutomates("capa_texto");
		$texto = $cAutomates->mRetornaDados();

		$cTpl->mSet("texto", $texto[0]['ds_capa_texto']);	

		$cTpl->mShow("inicio");
		

		
		$cTpl->mShow("fim_while");
		
		include "principalFim.php";
	break;
	

	/***************************
		RESTAURANTE
	***************************/
	case "restaurante":
		include "principalInicio.php";
			
		$cTpl = new cTemplate("tpl.texto-fotos.htm");

        $cTpl->mSet("titulo_interna","Restaurante");

        $cAutomates = new cAutomates("restaurante");
        $expedicao = $cAutomates->mRetornaDados();
        
        $cTpl->mSet("descricao",nl2br($expedicao[0]['ds_restaurante']));
		$cTpl->mShow("inicio");
		
		$cAutomates = new cAutomates("restaurante_fotos");
		$fotos = $cAutomates->mRetornaDados();
		
		if (count($fotos) > 0) {
			
			$cTpl->mShow("foto");
			for ($i=0; $i < count($fotos); $i++) { 
				$cTpl->mSet("tt_foto",$fotos[$i]['tt_foto']);
				$cTpl->mSet("foto",$cGd->mGeraGd($caminhoFotos,$fotos[$i]['nm_foto'],360,1));
				$cTpl->mSet("link",$cGd->mGeraGd($caminhoFotos,$fotos[$i]['nm_foto'],500,3));
				$cTpl->mShow("while");				
			}
			$cTpl->mShow("fim_while");
				
		} 		
		
		
		
		
		$cTpl->mShow("fim");
		
		include "principalFim.php";
	break;
	
	/***************************
		LOCALIZAÇÃo
	***************************/
	case "localizacao":
		include "principalInicio.php";
			
		$cTpl = new cTemplate("tpl.texto-fotos.htm");

        $cTpl->mSet("titulo_interna","Localização");
        $cAutomates = new cAutomates("rapel");
        $rapel = $cAutomates->mRetornaDados();
        
        $cTpl->mSet("descricao",nl2br($rapel[0]['ds_rapel']));
        

		$cTpl->mShow("inicio");
		
		$cAutomates = new cAutomates("rapel_fotos");
		$fotos = $cAutomates->mRetornaDados();
		
		if (count($fotos) > 0) {
			
			$cTpl->mShow("foto");
			for ($i=0; $i < count($fotos); $i++) { 
				$cTpl->mSet("tt_foto",$fotos[$i]['tt_foto']);
				$cTpl->mSet("foto",$cGd->mGeraGd($caminhoFotos,$fotos[$i]['nm_foto'],360,1));
				$cTpl->mSet("link",$cGd->mGeraGd($caminhoFotos,$fotos[$i]['nm_foto'],500,3));
				$cTpl->mShow("while");				
			}
			$cTpl->mShow("fim_while");
				
		} 		
		
		
		
		$cTpl->mShow("fim_while");
		
		include "principalFim.php";
	break;
	
	
	/***************************
		TARIFÁRIO
	***************************/
	case "tarifario":
		include "principalInicio.php";
			
		$cTpl = new cTemplate("tpl.texto-fotos.htm");

        $cTpl->mSet("titulo_interna","Tarifário");

        $cAutomates = new cAutomates("canionismo");
        $canionismo = $cAutomates->mRetornaDados();
        
        $cTpl->mSet("descricao",nl2br($canionismo[0]['ds_canionismo']));

		$cTpl->mShow("inicio");
		
		$cAutomates = new cAutomates("canionismo_fotos");
		$fotos = $cAutomates->mRetornaDados();
		
		if (count($fotos) > 0) {
			
			$cTpl->mShow("foto");
			for ($i=0; $i < count($fotos); $i++) { 
				$cTpl->mSet("tt_foto",$fotos[$i]['tt_foto']);
				$cTpl->mSet("foto",$cGd->mGeraGd($caminhoFotos,$fotos[$i]['nm_foto'],360,1));
				$cTpl->mSet("link",$cGd->mGeraGd($caminhoFotos,$fotos[$i]['nm_foto'],500,3));
				$cTpl->mShow("while");				
			}
			$cTpl->mShow("fim_while");
				
		} 		
		
		
		$cTpl->mShow("fim_while");
		
		include "principalFim.php";
	break;
	
	/***************************
		CABANAS
	***************************/
	case "cabanas":
		include "principalInicio.php";
			
		$cTpl = new cTemplate("tpl.texto-fotos.htm");

        $cTpl->mSet("titulo_interna", "Cabanas");
        $cAutomates = new cAutomates("nivel");
        $nivel = $cAutomates->mRetornaDados();
        
        $cTpl->mSet("descricao",nl2br($nivel[0]['ds_nivel']));
		$cTpl->mShow("inicio");
		
		$cAutomates = new cAutomates("nivel_fotos");
		$fotos = $cAutomates->mRetornaDados();
		
		if (count($fotos) > 0) {
			
			$cTpl->mShow("foto");
			for ($i=0; $i < count($fotos); $i++) { 
				$cTpl->mSet("tt_foto",$fotos[$i]['tt_foto']);
				$cTpl->mSet("foto",$cGd->mGeraGd($caminhoFotos,$fotos[$i]['nm_foto'],360,1));
				$cTpl->mSet("link",$cGd->mGeraGd($caminhoFotos,$fotos[$i]['nm_foto'],500,3));
				$cTpl->mShow("while");				
			}
			$cTpl->mShow("fim_while");
				
		} 		
		
		
		
		
		$cTpl->mShow("fim");
		
		include "principalFim.php";
	break;
	
	/**************************
	 AVENTURAS PERSONALIZADAS
	**************************/
	case "aventuras-personalizadas":

		if($cUrl->mGetParam(2) == "enviar"){

			$cTpl = new cTemplate("tpl.email.htm");
			$cTpl->mSet("site","Guia Aparados da Serra - Adventure Aparados");
			$cTpl->mSet("pagina","Aventuras Personalizadas");
			$cTpl->mSet("hora",date("H:i"));
			$cTpl->mSet("dia",date("d/m/Y"));
			
			$conteudo.= "<p><strong>Nome</strong><br />".$_POST["nome"]."</p>";
			$conteudo.= "<p><strong>Telefone/Fax</strong><br />".$_POST["telefone"]."</p>";
			$conteudo.= "<p><strong>Email</strong><br />".$_POST["email"]."</p>";
			$conteudo.= "<p><strong>Cidade</strong><br />".$_POST["cidade"]."</p>";
			$conteudo.= "<p><strong>UF</strong><br />".$_POST["uf"]."</p>";
			$conteudo.= "<p><strong>Transporte para chegar ao destino</strong><br />".$_POST["transporte"]."</p>";
			$conteudo.= "<p><strong>Possivel Data da Chegada</strong><br />".$_POST["data_ini"]."</p>";
			$conteudo.= "<p><strong>Possivel Data da Saida</strong><br />".$_POST["data_fim"]."</p>";
			$conteudo.= "<p><strong>Categoria de Hospedagem:</strong><br />".$_POST["categoria_hospedagem"]."</p>";
			$conteudo.= "<p><strong>Numero de Pessoas</strong><br />".$_POST["numeroPessoas"]."</p>";
			$conteudo.= "<p><strong>Crianças</strong><br />".$_POST["criancas"]."</p>";
			$conteudo.= "<p><strong>Com Crianças</strong><br />".$_POST["idade_criancas"]."</p>";
			$conteudo.= "<p><strong>Participante acima de 65 anos que costuma realizar curtas ou média caminhadas</strong><br />".$_POST["participante_idoso"]."</p>";
			$conteudo.= "<p><strong>Participante: Gestante acima de 5 meses \ Deficiência Física \ Se considera Idoso \ Se considera muito Obeso</strong><br />".$_POST["participante_opcao"]."</p>";
			$conteudo.= "<p><strong>Participante Cadeirante</strong><br />".$_POST["participante_cadeirante"]."</p>";

			$passeio_itaimbezinho = isset($_POST['passeio_itaimbezinho']) ? "Sim" : "Não";
			$passeio_fortaleza = isset($_POST['passeio_fortaleza']) ? "Sim" : "Não";
			$passeio_cachoeiras = isset($_POST['passeio_cachoeiras']) ? "Sim" : "Não";
			$passeio_offroad = isset($_POST['passeio_offroad']) ? "Sim" : "Não";
			$passeio_boi = isset($_POST['passeio_boi']) ? "Sim" : "Não";
			$passeio_pedra = isset($_POST['passeio_pedra']) ? "Sim" : "Não";
			$passeio_tigre = isset($_POST['passeio_tigre']) ? "Sim" : "Não";
			$passeio_realengo = isset($_POST['passeio_realengo']) ? "Sim" : "Não";
			$passeio_5h = isset($_POST['passeio_5h']) ? "Sim" : "Não";
			$passeio_pinheirinho = isset($_POST['passeio_pinheirinho']) ? "Sim" : "Não";
			$passeio_nasssucar = isset($_POST['passeio_nasssucar']) ? "Sim" : "Não";
			$passeio_rural = isset($_POST['passeio_rural']) ? "Sim" : "Não";
			$passeio_outros = isset($_POST['passeio_outros']) ? "Sim" : "Não";

			$conteudo.= "<p><strong>Passeio Canyon Itaimbézinho</strong><br />".$passeio_itaimbezinho."</p>";
			$conteudo.= "<p><strong>Passeio Canyon Fortaleza</strong><br />".$passeio_fortaleza."</p>";
			$conteudo.= "<p><strong>Passeio Rota das Cachoeiras 4x4</strong><br />".$passeio_cachoeiras."</p>";
			$conteudo.= "<p><strong>Passeio Off Road Canyons e Cachoeiras</strong><br />".$passeio_offroad."</p>";
			$conteudo.= "<p><strong>Aventura Trilha do Boi</strong><br />".$passeio_boi."</p>";
			$conteudo.= "<p><strong>Aventura Canyon da Pedra</strong><br />".$passeio_pedra."</p>";
			$conteudo.= "<p><strong>Aventura Trilha do Tigre Preto</strong><br />".$passeio_tigre."</p>";
			$conteudo.= "<p><strong>Travessia do Canyon Realengo</strong><br />".$passeio_realengo."</p>";
			$conteudo.= "<p><strong>Cavalgada de 05hs margeando os Canyons</strong><br />".$passeio_5h."</p>";
			$conteudo.= "<p><strong>Cavalgada de 02:30h ao Canyon Pinheirinho</strong><br />".$passeio_pinheirinho."</p>";
			$conteudo.= "<p><strong>Cavalgada de 02hs a Cachoeira do Nassucar</strong><br />".$passeio_nasssucar."</p>";
			$conteudo.= "<p><strong>Tur Rural - Tur em Fazendas</strong><br />".$passeio_rural."</p>";
			$conteudo.= "<p><strong>Outros passeios</strong><br />".$passeio_outros."</p>";

			$passeio_canionismo = isset($_POST['passeio_canionismo']) ? "Sim" : "Não";
			$passeio_bote = isset($_POST['passeio_bote']) ? "Sim" : "Não";
			$passeio_bike = isset($_POST['passeio_bike']) ? "Sim" : "Não";
			$passeio_4x4 = isset($_POST['passeio_4x4']) ? "Sim" : "Não";
			$passeio_cavalgada = isset($_POST['passeio_cavalgada']) ? "Sim" : "Não";

			$conteudo.= "<p><strong>Canionismo - Para pessoas com boa experiência em atividade vertical</strong><br />".$passeio_canionismo."</p>";
			$conteudo.= "<p><strong>Passeio de Bote</strong><br />".$passeio_bote."</p>";
			$conteudo.= "<p><strong>Rapel</strong><br />".$passeio_rapel."</p>";
			$conteudo.= "<p><strong>Passeio de Bike</strong><br />".$passeio_bike."</p>";
			$conteudo.= "<p><strong>Outros passeios de 4x4</strong><br />".$passeio_4x4."</p>";
			$conteudo.= "<p><strong>Outros passeios de Cavalgada</strong><br />".$passeio_cavalgada."</p>";
			
			$conteudo.= "<p><strong>Observações</strong><br />".$_POST["mensagem"]."</p>";
						
			$cTpl->mSet("conteudo",$conteudo);
			$body = $cTpl->mShow("",1);


            $cAutomates = new cAutomates("contato_dados");
            $faleconosco = $cAutomates->mRetornaDados();
            
            $headers = "MIME-Version: 1.1\n";
            $headers .= "Content-type: text/html; charset=iso-8859-1\n";
            $headers .= "From: sistema@guiaaparadosdaserra.com.br\n";

            $assunto = "Guia Aparados da Serra - Adventure Aparados (Aventuras Personalizadas)";

            if (!mail($faleconosco[0]["email_faleconosco_dados"],$assunto,$body,$headers, "-r"."sistema@guiaaparadosdaserra.com.br")){

                $headers .= "Return-Path: sistema@guiaaparadosdaserra.com.br\n";

                if (! mail($faleconosco[0]["email_faleconosco_dados"],$assunto,$body,$headers) ){
                    print_r("Erro ao enviar emails.");
                }
                else {
                    header("location: /aventuras-personalizadas/ok/");
                }
            }
            else{
            	header("location: /aventuras-personalizadas/ok/");
            }
		}
		else{

			include "principalInicio.php";
				
			$cTpl = new cTemplate("tpl.personalizados-novo.htm");

			$cTpl->mShow("inicio");
			
			include "principalFim.php";

			if ($cUrl->mGetParam(2) == "ok") $cToo->mAlert("Enviado com sucesso! Aguarde uma resposta de nossa equipe. Obrigado!");
		}

	break;	
	
	/**************************
		Turismo Rural
	**************************/
	case "turismo-rural":
		include "principalInicio.php";
		
		if ($cUrl->mGetParam(2)) {
		
			$cTpl = new cTemplate("tpl.produtos-lista-interna.htm");
            

            $cAutomates = new cAutomates("pacotes");
            $pacote = $cAutomates->mRetornaDados($cUrl->mGetParam(2));
            
            $cTpl->mSet("nome",$pacote[0]['nm_pacote']);
            $cTpl->mSet("descricao",nl2br($pacote[0]['ds_pacote']));

			$cTpl->mShow("inicio");
			
			$cAutomates = new cAutomates("pacotes_fotos");
			$fotos = $cAutomates->mRetornaDados("","cd_pacote ='".$cUrl->mGetParam(2)."'");
			
			if (count($fotos) > 0) {
				
				$cTpl->mShow("foto");
				for ($i=0; $i < count($fotos); $i++) { 
					$cTpl->mSet("tt_foto",$fotos[$i]['tt_foto']);
					$cTpl->mSet("foto",$cGd->mGeraGd($caminhoFotos,$fotos[$i]['nm_foto'],360,1));
					$cTpl->mSet("link",$cGd->mGeraGd($caminhoFotos,$fotos[$i]['nm_foto'],500,3));
					$cTpl->mShow("while");				
				}
				$cTpl->mShow("fim_while");
					
			} 		
			
			
			
			
			$cTpl->mShow("fim");
			
		} else {
			
			$cTpl = new cTemplate("tpl.produtos-lista.htm");
			$cTpl->mSet("titulo_pagina","Turismo Rural");
            $cTpl->mShow("inicio");
			
			$cAutomates = new cAutomates("pacotes");
			$pacote = $cAutomates->mRetornaDados();
			
			for ($i=0; $i < count($pacote); $i++) { 
				$cTpl->mShow("while");
				
				$cAutomates = new cAutomates("pacotes_fotos");
				$fotos = $cAutomates->mRetornaDados("","cd_pacote ='".$pacote[$i]['cd_pacote']."'");
				$cTpl->mSet("link","turismo-rural/".$pacote[$i]['cd_pacote']."/".$cUrl->mTrataString($pacote[$i]['nm_pacote']).".html");
				
				
				if(count($fotos) > 0) {
					$cTpl->mSet("foto",$cGd->mGeraGd($caminhoFotos,$fotos[0]['nm_foto'],360,1));
					$cTpl->mShow("foto");
				}
				
				$cTpl->mSet("nome",$pacote[$i]['nm_pacote']);
				$cTpl->mSet("descricao",$cAutomates->mBreveDescricao($pacote[$i]['ds_pacote'],150));
				$cTpl->mShow("dados");
			}
			
			$cTpl->mShow("fim");
		
		}
		include "principalFim.php";
	break;
	
	/**************************
		AVENTURAS
	**************************/
	case "aventuras":
		include "principalInicio.php";
		
		if ($cUrl->mGetParam(2)) {
			
			$cTpl = new cTemplate("tpl.produtos-lista-interna.htm");

            $cAutomates = new cAutomates("passeios");
            $passeio = $cAutomates->mRetornaDados($cUrl->mGetParam(2));
            
            $cTpl->mSet("nome",$passeio[0]['nm_passeio']);
            $cTpl->mSet("descricao",nl2br($passeio[0]['ds_passeio']));
            
			$cTpl->mShow("inicio");
			
			$cAutomates = new cAutomates("passeios_fotos");
			$fotos = $cAutomates->mRetornaDados("","cd_passeio ='".$cUrl->mGetParam(2)."'");
			
			if (count($fotos) > 0) {
				
				$cTpl->mShow("foto");
				for ($i=0; $i < count($fotos); $i++) { 
					$cTpl->mSet("tt_foto",$fotos[$i]['tt_foto']);
					$cTpl->mSet("foto",$cGd->mGeraGd($caminhoFotos,$fotos[$i]['nm_foto'],360,1));
					$cTpl->mSet("link",$cGd->mGeraGd($caminhoFotos,$fotos[$i]['nm_foto'],500,3));
					$cTpl->mShow("while");				
				}
				$cTpl->mShow("fim_while");
					
			} 		
			
			
			
			$cTpl->mShow("fim");
			
		} else {
			
			$cTpl = new cTemplate("tpl.produtos-lista.htm");
             $cTpl->mSet("titulo_pagina","Aventuras");

			$cTpl->mShow("inicio");
			
			$cAutomates = new cAutomates("passeios");
			$passeio = $cAutomates->mRetornaDados();
			
			for ($i=0; $i < count($passeio); $i++) { 
				$cTpl->mShow("while");
				
				$cAutomates = new cAutomates("passeios_fotos");
				$fotos = $cAutomates->mRetornaDados("","cd_passeio ='".$passeio[$i]['cd_passeio']."'");
				$cTpl->mSet("link","aventuras/".$passeio[$i]['cd_passeio']."/".$cUrl->mTrataString($passeio[$i]['nm_passeio']).".html");
				
				
				if(count($fotos) > 0) {
					$cTpl->mSet("foto",$cGd->mGeraGd($caminhoFotos,$fotos[0]['nm_foto'],360,1));
					$cTpl->mShow("foto");
				}
				
				$cTpl->mSet("nome",$passeio[$i]['nm_passeio']);
				$cTpl->mSet("descricao",$cAutomates->mBreveDescricao($passeio[$i]['ds_passeio'],150));
				$cTpl->mShow("dados");
			}
			
			$cTpl->mShow("fim");
		
		}
		include "principalFim.php";
	break;
	
	/**************************
		POLITICA DE RESERVA
	**************************/
	case "politica-de-reserva":
		include "principalInicio.php";
			
		$cTpl = new cTemplate("tpl.texto-fotos.htm");
        $cTpl->mSet("titulo_interna", "Política de Reserva");

        $cAutomates = new cAutomates("informe");
        $informe = $cAutomates->mRetornaDados();
        
        $cTpl->mSet("descricao",nl2br($informe[0]['ds_informe']));

		$cTpl->mShow("inicio");
		
		$cAutomates = new cAutomates("informe_fotos");
		$fotos = $cAutomates->mRetornaDados();
		
		if (count($fotos) > 0) {
			
			$cTpl->mShow("foto");
			for ($i=0; $i < count($fotos); $i++) { 
				$cTpl->mSet("tt_foto",$fotos[$i]['tt_foto']);
				$cTpl->mSet("foto",$cGd->mGeraGd($caminhoFotos,$fotos[$i]['nm_foto'],360,1));
				$cTpl->mSet("link",$cGd->mGeraGd($caminhoFotos,$fotos[$i]['nm_foto'],500,3));
				$cTpl->mShow("while");				
			}
			$cTpl->mShow("fim_while");
				
		} 		
		
		
		
		
		$cTpl->mShow("fim_while");
		
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
			$cTpl->mSet("foto",$cGd->mGeraGd($caminhoFotos,$fotos[$i]['nm_foto'],360,1));
			$cTpl->mSet("link",$cGd->mGeraGd($caminhoFotos,$fotos[$i]['nm_foto'],500,3));
			$cTpl->mShow("while");	
		}
		$cTpl->mShow("fim");
		
		
		include "principalFim.php";
	break;
	
	/**************************
		PACOTES
	**************************/
	case "pacotes":
		include "principalInicio.php";
		
		if ($cUrl->mGetParam(2)) {
			
			$cTpl = new cTemplate("tpl.produtos-lista-interna.htm");
			$cAutomates = new cAutomates("guias");
			$guias = $cAutomates->mRetornaDados($cUrl->mGetParam(2));
			
			$cTpl->mSet("nome",$guias[0]['nm_guias']);
			$cTpl->mSet("descricao",nl2br($guias[0]['ds_guias']));

			$cTpl->mShow("inicio");
			
			$cAutomates = new cAutomates("guias_fotos");
			$fotos = $cAutomates->mRetornaDados("","cd_guias ='".$cUrl->mGetParam(2)."'");
			
			if (count($fotos) > 0) {
				
				$cTpl->mShow("foto");
				for ($i=0; $i < count($fotos); $i++) { 
					$cTpl->mSet("tt_foto",$fotos[$i]['tt_foto']);
					$cTpl->mSet("foto",$cGd->mGeraGd($caminhoFotos,$fotos[$i]['nm_foto'],360,1));
					$cTpl->mSet("link",$cGd->mGeraGd($caminhoFotos,$fotos[$i]['nm_foto'],500,3));
					$cTpl->mShow("while");				
				}
				$cTpl->mShow("fim_while");
					
			} 		
			
			
			
			
			$cTpl->mShow("fim");
			
		} else {
			
			$cTpl = new cTemplate("tpl.produtos-lista.htm");
			$cTpl->mSet("titulo_pagina","Pacotes");
			$cTpl->mShow("inicio");
			
			$cAutomates = new cAutomates("guias");
			$guias = $cAutomates->mRetornaDados();
			
			for ($i=0; $i < count($guias); $i++) { 
				$cTpl->mShow("while");
				
				$cAutomates = new cAutomates("guias_fotos");
				$fotos = $cAutomates->mRetornaDados("","cd_guias ='".$guias[$i]['cd_guias']."'");
				$cTpl->mSet("link","pacotes/".$guias[$i]['cd_guias']."/".$cUrl->mTrataString($guias[$i]['nm_guias']).".html");
				
				
				if(count($fotos) > 0) {
					$cTpl->mSet("foto",$cGd->mGeraGd($caminhoFotos,$fotos[0]['nm_foto'],360,1));
					$cTpl->mShow("foto");
				}
				
				$cTpl->mSet("nome",$guias[$i]['nm_guias']);
				$cTpl->mSet("descricao",$cAutomates->mBreveDescricao($guias[$i]['ds_guias'],150));
				$cTpl->mShow("dados");
			}
			
			$cTpl->mShow("fim");
		
		}
		include "principalFim.php";
	break;
	
	/**************************
		100% SINCERO
	**************************/
	case "sincero":
		include "principalInicio.php";
        
        if ($cUrl->mGetParam(2)) {
            
            $cTpl = new cTemplate("tpl.reportagens-int.htm");            
            
            $cAutomates = new cAutomates("reportagens");
            $reportagens = $cAutomates->mRetornaDados($cUrl->mGetParam(2));
            
            $cTpl->mSet("nome",$reportagens[0]['tt_reportagens']);
            $cTpl->mSet("descricao",nl2br($reportagens[0]['ds_reportagens']));
            
            $cTpl->mShow("inicio");
            
            $cAutomates = new cAutomates("reportagens_fotos");
            $fotos = $cAutomates->mRetornaDados();
            
            for ($i=0; $i < count($fotos); $i++) { 
                $cTpl->mSet("tt_foto",$fotos[$i]['tt_foto']);
                $cTpl->mSet("foto",$cGd->mGeraGd($caminhoFotos,$fotos[$i]['nm_foto'],360,1));
                $cTpl->mSet("link",$cGd->mGeraGd($caminhoFotos,$fotos[$i]['nm_foto'],800,3));
                $cTpl->mShow("while");  
            }
            $cTpl->mShow("fim_while");
            
        } else {
            
            $cTpl = new cTemplate("tpl.reportagens.htm");
            $cTpl->mShow("inicio");
            
            $cAutomates = new cAutomates("reportagens");
            $reportagens = $cAutomates->mRetornaDados();
            
            for ($i=0; $i < count($reportagens); $i++) { 
                $cTpl->mShow("while");
                
                $cAutomates = new cAutomates("reportagens_fotos");
                $fotos = $cAutomates->mRetornaDados("","cd_reportagens ='".$reportagens[$i]['cd_reportagens']."'");
                $cTpl->mSet("link","sincero/".$reportagens[$i]['cd_reportagens']."/".$cUrl->mTrataString($reportagens[$i]['tt_reportagens']).".html");
                
                
                if(count($fotos) > 0) {
                    $cTpl->mSet("foto",$cGd->mGeraGd($caminhoFotos,$fotos[0]['nm_foto'],360,1));
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
				$cTpl->mSet("foto",$cGd->mGeraGd($caminhoFotos,$fotos[$i]['nm_foto'],360,1));
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
		CHALÉS
	**************************/
	case "chales":
		include "principalInicio.php";
			
		$cTpl = new cTemplate("tpl.texto-fotos.htm");

        $cTpl->mSet("titulo_interna","Chalés");

        $cAutomates = new cAutomates("mountainbike");
        $trekkings = $cAutomates->mRetornaDados();
        
        $cTpl->mSet("descricao",nl2br($trekkings[0]['ds_mountainbike']));
        
		$cTpl->mShow("inicio");
		
		$cAutomates = new cAutomates("mountainbike_fotos");
		$fotos = $cAutomates->mRetornaDados();
		
		if (count($fotos) > 0) {
			
			$cTpl->mShow("foto");
			for ($i=0; $i < count($fotos); $i++) { 
				$cTpl->mSet("tt_foto",$fotos[$i]['tt_foto']);
				$cTpl->mSet("foto",$cGd->mGeraGd($caminhoFotos,$fotos[$i]['nm_foto'],360,1));
				$cTpl->mSet("link",$cGd->mGeraGd($caminhoFotos,$fotos[$i]['nm_foto'],500,3));
				$cTpl->mShow("while");				
			}
			$cTpl->mShow("fim_while");
				
		} 		
		
		
		
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
		
		
        $cTpl->mShow("inicio");
        $cAutomates = new cAutomates("destaques");
        $destaques = $cAutomates->mRetornaDados();

        for ($i=0;$i<count($destaques);$i++) { 
            $cTpl->mSet("foto_destaque","/fotos/".$destaques[$i]["arq_destaque"]);
            $cTpl->mShow("while_destaques");
        }
        $cTpl->mShow("fim_while_destaques");
        
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

		$cAutomates = new cAutomates("parceiros");
        $parceiros = $cAutomates->mRetornaDados();

        for ($i=0; $i < count($parceiros) ; $i++) { 
            
            $cTpl->mSet("foto", "/fotos/".$parceiros[$i]["arq_parceiros"]);
            $cTpl->mShow("while_parc");
        }
        $cTpl->mShow("fim_");
		
		$cAutomates = new cAutomates("capa_texto");
		$texto = $cAutomates->mRetornaDados();	
		
		$cTpl->mSet("video1",$texto[0]['lk_1_capa_texto']);
		$cTpl->mSet("video2",$texto[0]['lk_2_capa_texto']);
		
		$cTpl->mShow("videos");
		
		
		include "principalFim.php";
	break;
}
?>