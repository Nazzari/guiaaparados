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
        QUEM SOMOS
    **********************************/
    case "quem-somos":

        include "principalInicio.php";

        $cTpl = new cTemplate("tpl.quem-somos.htm");
        
        $cAutomates = new cAutomates("quem_somos");
        $quem = $cAutomates->mRetornaDados("");
        //$cAutomates->mRetornaDados("","cd_pacote ='""'");

         $cTpl->mSet("quem_somos", nl2br($quem[0]["ds_quem_somos"]));      
        
        $cTpl->mShow("inicio");

        


        $cTpl->mShow("fim");

        include "principalFim.php";


        break;
    
    
    
    /**************************
        COMO CHEGAR
    **************************/
    case "como-chegar":
        include "principalInicio.php";
            
        $cTpl = new cTemplate("tpl.texto-fotos.htm");
        
        $cTpl->mSet("titulo_interna","Como Chegar");
        $cAutomates = new cAutomates("como_chegar");
        $como_chegar = $cAutomates->mRetornaDados("");
        
        $cTpl->mSet("descricao",nl2br($como_chegar[0]['ds_como']));
        
        $cTpl->mShow("");
        
        
        include "principalFim.php";
    break;

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

	/**************************
        REPORTAGENS
    **************************/
    case "reportagens":
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
                $cTpl->mSet("site","Guia Aparados da Serra - Fazenda Santana");
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

                $assunto = "Guia Aparados da Serra - Fazenda Santana (Contato)";

                if (!mail($faleconosco[0]["email_faleconosco_dados"],$assunto,$body,$headers, "-r"."sistema@guiaaparadosdaserra.com.br")){

                    $headers .= "Return-Path: sistema@guiaaparadosdaserra.com.br\n";

                    if (! mail($faleconosco[0]["email_faleconosco_dados"],$assunto,$body,$headers) ){
                        print_r("Erro ao enviar emails.");
                    }
                    else {
                        header("location: /contato/ok/");
                    }
                }
                else {
                        header("location: /contato/ok/");
                    }
                
                // include "zpainel/class/inc.phpmailer.php";
                // $PHPMailer = new PHPMailer();
                
                // $PHPMailer->From = "sistema@guiaaparadosdaserra.com.br";
    //             $PHPMailer->FromName = "sistema@guiaaparadosdaserra.com.br";
    //             $PHPMailer->Password = "si123654789";
    //             $PHPMailer->Port = "587";
                
                // $cAutomates = new cAutomates("contato_dados");
                // $faleconosco = $cAutomates->mRetornaDados();
                // $PHPMailer->AddAddress($faleconosco[0]["email_faleconosco_dados"]);
                
                // $PHPMailer->Subject = "Guia Aparados da Serra - Cavalgadas Aparados (Contato)";
                // $PHPMailer->Body = $body;
                // $PHPMailer->AltBody = $body;
                // $PHPMailer->WordWrap = 50;
                
                // if (!$PHPMailer->Send()) {
                //  print("Erro ao enviar email, favor tentar novamente!<br />");
                //  print("Erro: " . $PHPMailer->ErrorInfo . "");
                // } else {
                //  header("location: /contato/ok/");
                // }
            } else {
                header("location: /contato/");
            }
        } else {
            include "principalInicio.php";
            
            $cTpl = new cTemplate("tpl.contato.htm");
            
            $cAutomates = new cAutomates("contato_dados");
            $contato = $cAutomates->mRetornaDados();
            
            $cTpl->mSet("descricao",nl2br($contato[0]['ds_faleconosco_dados']));
            
            $cTpl->mShow("");
            
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
                $cTpl->mSet("site","Guia Aparados da Serra - Cavalgadas Aparados");
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
        RESTAURANTE FAZENDA
    ***************************/
    case "restaurante-fazenda":
        include "principalInicio.php";
            
        $cTpl = new cTemplate("tpl.texto-fotos.htm");
        
        $cTpl->mSet("titulo_interna", "RESTAURANTE DA FAZENDA");

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
        NIVEL DE ATIVIDADE
    ***************************/
    case "nivel-de-cavalgada":
        include "principalInicio.php";
            
        $cTpl = new cTemplate("tpl.texto-fotos.htm");
        $cAutomates = new cAutomates("nivel");
        $nivel = $cAutomates->mRetornaDados();
        
        $cTpl->mSet("descricao",nl2br($nivel[0]['ds_nivel']));
        $cTpl->mSet("titulo_interna","Nível de Cavalgadas Aparados da Serra");
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
     CAVALGADAS PERSONALIZADAS
    **************************/
    case "cavalgadas-personalizadas":
        include "principalInicio.php";
            
        $cTpl = new cTemplate("tpl.texto-fotos.htm");

        $cAutomates = new cAutomates("personalizados");
        $personalizados = $cAutomates->mRetornaDados();
        
        $cTpl->mSet("descricao",nl2br($personalizados[0]['ds_personalizado']));

        $cTpl->mSet("titulo_interna","Cavalgadas Personalizadas Aparados da Serra");

        $cTpl->mShow("inicio");
        
        $cAutomates = new cAutomates("personalizados_fotos");
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
        TRANSLADO TRANSFER
    **************************/
    case "translado-transfer":
        include "principalInicio.php";
            
        $cTpl = new cTemplate("tpl.texto-fotos.htm");

        $cAutomates = new cAutomates("translado");
        $translado = $cAutomates->mRetornaDados();
        
        $cTpl->mSet("descricao",nl2br($translado[0]['ds_translado']));

        $cTpl->mSet("titulo_interna","Translado Transfer");
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
    
    /**************************
        PACOTES
    **************************/
    case "pacotes":
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
            
            $cTpl->mSet("titulo_pagina","PACOTES NOS APARADOS DA SERRA");
            $cTpl->mShow("inicio");
            
            $cAutomates = new cAutomates("pacotes");
            $pacote = $cAutomates->mRetornaDados();
            
            for ($i=0; $i < count($pacote); $i++) { 
                $cTpl->mShow("while");
                
                $cAutomates = new cAutomates("pacotes_fotos");
                $fotos = $cAutomates->mRetornaDados("","cd_pacote ='".$pacote[$i]['cd_pacote']."'");
                $cTpl->mSet("link","pacotes/".$pacote[$i]['cd_pacote']."/".$cUrl->mTrataString($pacote[$i]['nm_pacote']).".html");
                
                
                if(count($fotos) > 0) {
                    $cTpl->mSet("foto",$cGd->mGeraGd($caminhoFotos,$fotos[0]['nm_foto'],360,3));
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
        AS FAZENDAS
    **************************/
    case "fazendas-roteiros":
        include "principalInicio.php";
        
        if ($cUrl->mGetParam(2)) {
            
            $cTpl = new cTemplate("tpl.produtos-lista-interna-ex.htm");
            
            $cTpl->mSet("titulo_interna", "FAZENDA DE ROTEIROS LONGOS");
            
            $cAutomates = new cAutomates("pousadas");
            $pousadas = $cAutomates->mRetornaDados($cUrl->mGetParam(2));
            
            $cTpl->mSet("nome",$pousadas[0]['nm_pousadas']);
            $cTpl->mSet("descricao",nl2br($pousadas[0]['ds_pousadas']));

            $cTpl->mShow("inicio");
            
            $cAutomates = new cAutomates("pousadas_fotos");
            $fotos = $cAutomates->mRetornaDados("","cd_pousadas ='".$cUrl->mGetParam(2)."'");
            
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
            
             $cTpl->mSet("titulo_pagina", "FAZENDA DE ROTEIROS LONGOS");

            $cTpl->mShow("inicio");
            
            $cAutomates = new cAutomates("pousadas");
            $pousadas = $cAutomates->mRetornaDados();
            
            for ($i=0; $i < count($pousadas); $i++) { 
                $cTpl->mShow("while");
                
                $cAutomates = new cAutomates("pousadas_fotos");
                $fotos = $cAutomates->mRetornaDados("","cd_pousadas ='".$pousadas[$i]['cd_pousadas']."'"); 
                // $cTpl->mSet("link","as-fazendas/".$pousadas[$i]['cd_pousadas']."/".$cUrl->mTrataString($pousadas[$i]['nm_pousadas']).".html");
                $cTpl->mSet("link","/pacotes");
                
                
                if(count($fotos) > 0) {
                    $cTpl->mSet("foto",$cGd->mGeraGd($caminhoFotos,$fotos[0]['nm_foto'],360,1));
                    $cTpl->mShow("foto");
                }
                
                $cTpl->mSet("nome",$pousadas[$i]['nm_pousadas']);
                $cTpl->mSet("descricao",$cAutomates->mBreveDescricao($pousadas[$i]['ds_pousadas'],150));
                $cTpl->mShow("dados");
            }
            
            $cTpl->mShow("fim");
        
        }
        include "principalFim.php";
    break;
    

    /**************************
        TROPILHA
    **************************/
    case "tropilha":
        include "principalInicio.php";
            
        $cTpl = new cTemplate("tpl.texto-fotos.htm");

        $cAutomates = new cAutomates("informe");
        $informe = $cAutomates->mRetornaDados();
        
        $cTpl->mSet("titulo_interna", "TROPILHA");
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

        $cTpl->mSet("titulo_pagina","Fotos nos Aparados da Serra");
        $cTpl->mShow("inicio");
        
        $cAutomates = new cAutomates("galeria_fotos");
        $fotos = $cAutomates->mRetornaDados();
        
        for ($i=0; $i < count($fotos); $i++) { 
            $cTpl->mSet("tt_foto","Fotos nos Aparados da Serra");
            $cTpl->mSet("foto",$cGd->mGeraGd($caminhoFotos,$fotos[$i]['nm_foto'],360,1));
            $cTpl->mSet("link",$cGd->mGeraGd($caminhoFotos,$fotos[$i]['nm_foto'],500,3));
            $cTpl->mShow("while");  
        }
        $cTpl->mShow("fim");
        
        
        include "principalFim.php";
    break;
    
    /**************************
        PERGUNTAS
    **************************/
    case "perguntas-e-respostas":
        include "principalInicio.php";
            
        $cTpl = new cTemplate("tpl.texto-fotos.htm");
        
        $cTpl->mSet("titulo_interna","Perguntas e Respostas");
        $cAutomates = new cAutomates("perguntas");
        $perguntas = $cAutomates->mRetornaDados("");
        
        $cTpl->mSet("descricao",nl2br($perguntas[0]['ds_perguntas']));
        
        $cTpl->mShow("");
        
        
        include "principalFim.php";
    break;
    
    /**************************
        Guias
    **************************/
    case "guias":
        include "principalInicio.php";
        
        if ($cUrl->mGetParam(2)) {
            
            $cTpl = new cTemplate("tpl.guiasInt.htm");

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
                    $cTpl->mSet("foto",$cGd->mGeraGd($caminhoFotos,$fotos[0]['nm_foto'],360,1));
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
        POLITiCA DE RESERVAS
    **************************/
    case "politica-de-reservas":
        include "principalInicio.php";
            
        $cTpl = new cTemplate("tpl.texto-fotos.htm");

        $cAutomates = new cAutomates("cavalgadas");
        $cavalgadas = $cAutomates->mRetornaDados();
        $cTpl->mSet("titulo_interna","Política de Reservas");
        $cTpl->mSet("descricao",nl2br($cavalgadas[0]['ds_cavalgadas']));

        $cTpl->mShow("inicio");
        
        $cAutomates = new cAutomates("cavalgadas_fotos");
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
                $cTpl->mSet("foto",$cGd->mGeraGd($caminhoFotos,$foto[0]['nm_foto'],360,3));

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