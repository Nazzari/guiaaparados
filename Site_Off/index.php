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

function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) 
        && preg_match('/@.+\./', $email);
}

function principalInicio($descricao, $pagina_inicial = false){

    $cAutomatesConfigSite = new cAutomates("zpainel_config");
    $configSite = $cAutomatesConfigSite->mRetornaDados();

    $cTplIndex = new cTemplate("tpl.index.htm");

    if($pagina_inicial)        
        $cTplIndex->mSet("titulo_site_config",$configSite[0]["titulo_site_config"]);
    else
        $cTplIndex->mSet("titulo_site_config", $descricao);

    $cTplIndex->mSet("description_site_config",$configSite[0]["description_site_config"]);
    $cTplIndex->mSet("keywords_site_config",$configSite[0]["keywords_site_config"]);
    $cTplIndex->mSet("analytics_site_config",$configSite[0]["analytics_site_config"]);
    $cTplIndex->mSet("webmasters_site_config",$configSite[0]["webmasters_site_config"]);
    
    $cTplIndex->mShow("inicio");

     

      $cAutomates = new cAutomates("destaques");
        $destaques = $cAutomates->mRetornaDados();

        for ($i=0; $i <count($destaques) ; $i++) { 
            $cTplIndex->mSet("img_destaque", "/fotos/foto.php?src=" . $destaques[$i]["arq_destaque"] ."&w=758&h=280&m=u");
            $cTplIndex->mSet("link", $destaques[$i]["link"]);

            $cTplIndex->mShow("home_slider");
        }
        $cTplIndex->mShow("end_home_slider");
}

function principalFim(){
    $cTplIndex = new cTemplate("tpl.index.htm");
    $cTplIndex->mSet("anoAtual",date("Y"));
    $cTplIndex->mShow("fim");
}

switch ($cUrl->mGetParam(1)) {

    /**************************
      ECASG
     **************************/
    case "ecasg":
        //include "principalInicio.php";
        principalInicio("ECASG");

        $cTpl = new cTemplate("tpl.ecasg.htm");
        $cTpl->mShow("inicio");

        $cAutomates = new cAutomates("ecasg");
        $ecasg = $cAutomates->mRetornaDados("");

        for ($i = 0; $i < count($ecasg); $i++) {

            $cTpl->mSet("cd_guia", $ecasg[$i]['cd_guia']);
            $cTpl->mSet("nm_guia", $ecasg[$i]['nm_guia']);
            $cTpl->mSet("tt_guia", $cAutomates->mBreveDescricao($ecasg[$i]['tt_guia'], 200));
            $cTpl->mSet("ds_guia", $ecasg[$i]['ds_guia']);
            $cTpl->mSet("ft_guia", "/fotos/foto.php?src=" . $ecasg[$i]['ft_guia'] . "&w=205&h=153&m=u");
            $cTpl->mSet("ft_guia_grande", "/fotos/foto.php?src=" . $ecasg[$i]['ft_guia'] . "&w=800&h=600&m=u");

            $cTpl->mShow("while");
        }
        $cTpl->mShow("fim");
        
        include "principalFim.php";
    break;

    /**************************
      FORM BUSCA
     **************************/
    case "formBusca":
        //include "principalInicio.php";
        principalInicio("Busca - Guia Aparados da Serra");

        $cTpl = new cTemplate("tpl.resultados.htm");
        $numResultados = 0;

        /* Agencias / Guias */
        $cAutomAgencias = new cAutomates("agencias");
        $agencias = $cAutomAgencias->mRetornaDados("", "nm_agencia like '%" . $_GET['key'] . "%' OR end_agencia like '%" . $_GET['key'] . "%' OR ds_agencia like '%" .
                $_GET['key'] . "%' OR nm_cidade like '%" . $_GET['key'] . "%'");

        $numResultados += count($agencias);

        /* As cidades */
        $cAutomCidades = new cAutomates("cidades");
        $cidades = $cAutomCidades->mRetornaDados("", "nm_cidades like '%" . $_GET['key'] . "%' OR ds_cidades like '%" . $_GET['key'] . "%'");

        $numResultados += count($cidades);

        /* Atrativos */
        $cAutomAtrativos = new cAutomates("atrativos");
        $atrativos = $cAutomAtrativos->mRetornaDados("", "ds_atrativo like '%" . $_GET['key'] . "%'");

        $numResultados += count($atrativos);

        /* Cavalgadas nos aparados */
        $cAutomCavalgadas = new cAutomates("cavalgadas");
        $cavalgadas = $cAutomCavalgadas->mRetornaDados("", "ds_cavalgada like '%" . $_GET['key'] . "%'");

        $numResultados += count($cavalgadas);

        /* Compras e servicos */
        $cAutomCompras = new cAutomates("compras");
        $compras = $cAutomCompras->mRetornaDados("", "nm_compra like '%" . $_GET['key'] . "%' OR end_compra like '%" . $_GET['key'] . "%' OR ds_compra like '%" .
                $_GET['key'] . "%' OR nm_cidade like '%" . $_GET['key'] . "%'");

        $numResultados += count($compras);

        /* Dicas e Telefones */
        $cAutomDicas = new cAutomates("dicas");
        $dicas = $cAutomDicas->mRetornaDados("", "ds_dicas like '%" . $_GET['key'] . "%'");

        $numResultados += count($dicas);

        /* Excursão para grupos */
        $cAutomExcursao = new cAutomates("excursao");
        $excursao = $cAutomExcursao->mRetornaDados("", "ds_excursao like '%" . $_GET['key'] . "%'");

        $numResultados += count($excursao);

        /* Expedição aparados 4x4 */
        $cAutomExpedicao = new cAutomates("expedicao");
        $expedicao = $cAutomExpedicao->mRetornaDados("", "ds_expedicao like '%" . $_GET['key'] . "%'");

        $numResultados += count($expedicao);

        /* Galeria de fotos */
        $cAutomGaleria = new cAutomates("galeria");
        $galeria = $cAutomGaleria->mRetornaDados("", "ds_galeria like '%" . $_GET['key'] . "%'");

        $numResultados += count($galeria);

        /* Informe-se */
        $cAutomInforme = new cAutomates("informe");
        $informe = $cAutomInforme->mRetornaDados("", "tt_informe like '%" . $_GET['key'] . "%' OR ds_informe like '%" . $_GET['key'] . "%'");

        $numResultados += count($informe);

        /* Nivel de Atividades */
        $cAutomNivel = new cAutomates("atividades");
        $atividades = $cAutomNivel->mRetornaDados("", "ds_atividades like '%" . $_GET['key'] . "%'");

        $numResultados += count($atividades);

        /* Noticias */
        $cAutomNoticias = new cAutomates("noticias");
        $noticia = $cAutomNoticias->mRetornaDados("", "tt_noticia like '%" . $_GET['key'] . "%' OR ds_noticia like '%" . $_GET['key'] . "%'");

        $numResultados += count($noticia);

        /* O que é aparados */
        $cAutomAparados = new cAutomates("aparados");
        $aparados = $cAutomAparados->mRetornaDados("", "ds_aparados like '%" . $_GET['key'] . "%'");

        $numResultados += count($aparados);

        /* Onde Comer */
        $cAutomComer = new cAutomates("onde_comer");
        $comer = $cAutomComer->mRetornaDados("", "nm_comer like '%" . $_GET['key'] . "%' OR end_comer like '%" . $_GET['key'] . "%' OR ds_comer like '%" .
                $_GET['key'] . "%' OR nm_cidade like '%" . $_GET['key'] . "%'");

        $numResultados += count($comer);

        /* Onde Ficar */
        $cAutomFicar = new cAutomates("onde_ficar");
        $ficar = $cAutomFicar->mRetornaDados("", "nm_ficar like '%" . $_GET['key'] . "%' OR end_ficar like '%" . $_GET['key'] . "%' OR ds_ficar like '%" .
                $_GET['key'] . "%' OR nm_cidade like '%" . $_GET['key'] . "%'");

        $numResultados += count($ficar);

        /* Pacotes personalizados */
        $cAutomPersonalizado = new cAutomates("personalizados");
        $personalizados = $cAutomPersonalizado->mRetornaDados("", "ds_personalizado like '%" . $_GET['key'] . "%'");

        $numResultados += count($personalizados);

        /* Parques Nacionais */
        $cAutomParques = new cAutomates("parques");
        $parques = $cAutomParques->mRetornaDados("", "nm_parques like '%" . $_GET['key'] . "%' OR ds_parques like '%" . $_GET['key'] . "%'");

        $numResultados += count($parques);

        /* Quem Somos */
        $cAutomQuemSomos = new cAutomates("quem_somos");
        $quemSomos = $cAutomQuemSomos->mRetornaDados("", "ds_quem_somos like '%" . $_GET['key'] . "%'");

        $numResultados += count($quemSomos);

        /* Sobre aparados da serra */
        $cAutomSobreAparados = new cAutomates("sobre_aparados");
        $sobreAparados = $cAutomSobreAparados->mRetornaDados("", "ds_sobre_aparados like '%" . $_GET['key'] . "%'");

        $numResultados += count($sobreAparados);

        /* Translado/transfer */
        $cAutomTranslado = new cAutomates("translado");
        $translado = $cAutomTranslado->mRetornaDados("", "ds_translado like '%" . $_GET['key'] . "%'");

        $numResultados += count($translado);

        /* Os trekkings nos aparados */
        $cAutomTrekkings = new cAutomates("trekkings");
        $trekkings = $cAutomTrekkings->mRetornaDados("", "ds_trekkings like '%" . $_GET['key'] . "%'");

        $numResultados += count($trekkings);

        $cTpl->mSet("numResultados", $numResultados);
        $cTpl->mSet("key", $_GET['key']);
        $cTpl->mShow("inicio");

        /* Agencias / Guias */
        if (count($agencias) > 0) {
            $cTpl->mSet("nmModulo", "Agências / Guias");
            $cTpl->mSet("item", count($agencias));

            $cTpl->mShow("tipo_1");
            for ($i = 0; $i < count($agencias); $i++) {
                $cTpl->mSet("linkModulo", "agencias-guias/" . $agencias[$i]['cd_cidade'] . "/" . $cUrl->mTrataString($agencias[$i]['nm_cidade']));
                $cTpl->mSet("titulo", $agencias[$i]['nm_agencia']);

                $cTpl->mShow("while");
            }
            $cTpl->mShow("fim");
        }

        /* As cidades */
        if (count($cidades) > 0) {
            $cTpl->mSet("nmModulo", "As Cidades");
            $cTpl->mSet("item", count($cidades));

            $cTpl->mShow("tipo_1");
            for ($i = 0; $i < count($cidades); $i++) {
                $cTpl->mSet("linkModulo", "as-cidades/" . $cidades[$i]['cd_cidades'] . "/" . $cUrl->mTrataString($cidades[$i]['nm_cidades']));
                $cTpl->mSet("titulo", $cidades[$i]['nm_cidades']);

                $cTpl->mShow("while");
            }
            $cTpl->mShow("fim");
        }

        /* Atrativos */
        if (count($atrativos) > 0) {
            $cTpl->mSet("item", count($atrativos));
            $cTpl->mSet("nmModulo", "Atrativos");
            $cTpl->mSet("linkModulo", "atrativos/");
            $cTpl->mSet("descricao", $cAutomAtrativos->mBreveDescricao($atrativos[0]['ds_atrativo'], 200));
            $cTpl->mShow("tipo_2");
        }

        /* Cavalgadas */
        if (count($cavalgadas) > 0) {
            $cTpl->mSet("item", count($cavalgadas));
            $cTpl->mSet("nmModulo", "Cavalgadas nos aparados");
            $cTpl->mSet("linkModulo", "as-cavalgadas-nos-aparados/");
            $cTpl->mSet("descricao", $cAutomCavalgadas->mBreveDescricao($cavalgadas[0]['ds_cavalgada'], 200));
            $cTpl->mShow("tipo_2");
        }

        /* Compras e Serviços */
        if (count($compras) > 0) {
            $cTpl->mSet("nmModulo", "Compras e Serviços");
            $cTpl->mSet("item", count($compras));

            $cTpl->mShow("tipo_1");
            for ($i = 0; $i < count($compras); $i++) {
                $cTpl->mSet("linkModulo", "compras-e-servicos/" . $compras[$i]['cd_cidade'] . "/" . $cUrl->mTrataString($compras[$i]['nm_cidade']));
                $cTpl->mSet("titulo", $compras[$i]['nm_compra']);

                $cTpl->mShow("while");
            }
            $cTpl->mShow("fim");
        }

        /* Dicas e Telefones */
        if (count($dicas) > 0) {
            $cTpl->mSet("item", count($dicas));
            $cTpl->mSet("nmModulo", "Telefones Úteis");
            $cTpl->mSet("linkModulo", "telefones-uteis/");
            $cTpl->mSet("descricao", $cAutomDicas->mBreveDescricao($dicas[0]['ds_dicas'], 200));
            $cTpl->mShow("tipo_2");
        }

        /* Excursão para grupos */
        if (count($excursao) > 0) {
            $cTpl->mSet("item", count($excursao));
            $cTpl->mSet("nmModulo", "Excursão para grupos / colégios / universidade");
            $cTpl->mSet("linkModulo", "excursao-para-grupos-colegios-universidades/");
            $cTpl->mSet("descricao", $cAutomExcursao->mBreveDescricao($excursao[0]['ds_excursao'], 200));
            $cTpl->mShow("tipo_2");
        }

        /* Expedição aparados 4x4 */
        if (count($expedicao) > 0) {
            $cTpl->mSet("item", count($expedicao));
            $cTpl->mSet("nmModulo", "Expedição aparados 4x4");
            $cTpl->mSet("linkModulo", "expedicao-aparados-4x4/");
            $cTpl->mSet("descricao", $cAutomExpedicao->mBreveDescricao($expedicao[0]['ds_expedicao'], 200));
            $cTpl->mShow("tipo_2");
        }

        /* Galeria de Fotos */
        if (count($galeria) > 0) {
            $cTpl->mSet("item", count($galeria));
            $cTpl->mSet("nmModulo", "Galeria de Fotos");
            $cTpl->mSet("linkModulo", "galeria-de-fotos/");
            $cTpl->mSet("descricao", $cAutomGaleria->mBreveDescricao($galeria[0]['ds_galeria'], 200));
            $cTpl->mShow("tipo_2");
        }

        /* Informe-se */
        if (count($informe) > 0) {
            $cTpl->mSet("nmModulo", "Informe-se");
            $cTpl->mSet("item", count($informe));

            $cTpl->mShow("tipo_1");
            for ($i = 0; $i < count($informe); $i++) {
                $cTpl->mSet("linkModulo", "informe-se/" . $informe[$i]['cd_informe'] . "/" . $cUrl->mTrataString($informe[$i]['tt_informe']));
                $cTpl->mSet("titulo", $informe[$i]['tt_informe']);

                $cTpl->mShow("while");
            }
            $cTpl->mShow("fim");
        }

        /* Nivel de Atividades */
        if (count($atividades) > 0) {
            $cTpl->mSet("item", count($atividades));
            $cTpl->mSet("nmModulo", "Nível de atividades");
            $cTpl->mSet("linkModulo", "nivel-de-atividades/");
            $cTpl->mSet("descricao", $cAutomNivel->mBreveDescricao($atividades[0]['ds_atividades'], 200));
            $cTpl->mShow("tipo_2");
        }

        /* Noticias */
        if (count($noticia) > 0) {
            $cTpl->mSet("nmModulo", "Notícias");
            $cTpl->mSet("item", count($noticia));

            $cTpl->mShow("tipo_1");
            for ($i = 0; $i < count($noticia); $i++) {
                $cTpl->mSet("linkModulo", "noticias/" . $noticia[$i]['cd_noticia'] . "/" . $cUrl->mTrataString($noticia[$i]['tt_noticia']));
                $cTpl->mSet("titulo", $noticia[$i]['tt_noticia']);

                $cTpl->mShow("while");
            }
            $cTpl->mShow("fim");
        }

        /* O que é aparados */
        if (count($aparados) > 0) {
            $cTpl->mSet("item", count($aparados));
            $cTpl->mSet("nmModulo", "O que é Aparados da Serra");
            $cTpl->mSet("linkModulo", "o-que-e-aparados-da-serra/");
            $cTpl->mSet("descricao", $cAutomAparados->mBreveDescricao($aparados[0]['ds_aparados'], 200));
            $cTpl->mShow("tipo_2");
        }

        /* Onde Comer */
        if (count($comer) > 0) {
            $cTpl->mSet("nmModulo", "Onde Comer");
            $cTpl->mSet("item", count($comer));

            $cTpl->mShow("tipo_1");
            for ($i = 0; $i < count($comer); $i++) {
                $cTpl->mSet("linkModulo", "onde-comer/" . $comer[$i]['cd_cidade'] . "/" . $cUrl->mTrataString($comer[$i]['nm_cidade']));
                $cTpl->mSet("titulo", $comer[$i]['nm_comer']);

                $cTpl->mShow("while");
            }
            $cTpl->mShow("fim");
        }

        /* Onde Ficar */
        if (count($ficar) > 0) {
            $cTpl->mSet("nmModulo", "Onde Ficar");
            $cTpl->mSet("item", count($ficar));

            $cTpl->mShow("tipo_1");
            for ($i = 0; $i < count($ficar); $i++) {
                $cTpl->mSet("linkModulo", "onde-ficar/" . $ficar[$i]['cd_cidade'] . "/" . $cUrl->mTrataString($ficar[$i]['nm_cidade']));
                $cTpl->mSet("titulo", $ficar[$i]['nm_ficar']);

                $cTpl->mShow("while");
            }
            $cTpl->mShow("fim");
        }

        /* Pacotes Personalizados */
        if (count($personalizados) > 0) {
            $cTpl->mSet("item", count($personalizados));
            $cTpl->mSet("nmModulo", "Pacotes e Passeios Personalizados");
            $cTpl->mSet("linkModulo", "pacotes-e-passeios-personalizados/");
            $cTpl->mSet("descricao", $cAutomPersonalizado->mBreveDescricao($personalizados[0]['ds_personalizado'], 200));
            $cTpl->mShow("tipo_2");
        }

        /* Parques Nacionais */
        if (count($parques) > 0) {
            $cTpl->mSet("nmModulo", "Parques Nacionais");
            $cTpl->mSet("item", count($parques));

            $cTpl->mShow("tipo_1");
            for ($i = 0; $i < count($parques); $i++) {
                $cTpl->mSet("linkModulo", "parques-nacionais/" . $parques[$i]['cd_parques'] . "/" . $cUrl->mTrataString($parques[$i]['nm_parques']));
                $cTpl->mSet("titulo", $parques[$i]['nm_parques']);

                $cTpl->mShow("while");
            }
            $cTpl->mShow("fim");
        }

        /* Quem Somos */
        if (count($quemSomos) > 0) {
            $cTpl->mSet("item", count($quemSomos));
            $cTpl->mSet("nmModulo", "Quem Somos");
            $cTpl->mSet("linkModulo", "quem-somos/");
            $cTpl->mSet("descricao", $cAutomQuemSomos->mBreveDescricao($quemSomos[0]['ds_quem_somos'], 200));
            $cTpl->mShow("tipo_2");
        }

        /* Sobre Aparados da Serra */
        if (count($sobreAparados) > 0) {
            $cTpl->mSet("item", count($sobreAparados));
            $cTpl->mSet("nmModulo", "Sobre aparados da serra");
            $cTpl->mSet("linkModulo", "sobre-os-aparados-da-serra/");
            $cTpl->mSet("descricao", $cAutomSobreAparados->mBreveDescricao($sobreAparados[0]['ds_sobre_aparados'], 200));
            $cTpl->mShow("tipo_2");
        }

        /* Translado/Transfer */
        if (count($translado) > 0) {
            $cTpl->mSet("item", count($translado));
            $cTpl->mSet("nmModulo", "Translados / Transfer");
            $cTpl->mSet("linkModulo", "translados-transfer/");
            $cTpl->mSet("descricao", $cAutomTranslado->mBreveDescricao($translado[0]['ds_translado'], 200));
            $cTpl->mShow("tipo_2");
        }

        /* OS Trekkings nos aparados */
        if (count($trekkings) > 0) {
            $cTpl->mSet("item", count($trekkings));
            $cTpl->mSet("nmModulo", "Os trekkings nos aparados");
            $cTpl->mSet("linkModulo", "os-trekkings-nos-aparados/");
            $cTpl->mSet("descricao", $cAutomTrekkings->mBreveDescricao($trekkings[0]['ds_trekkings'], 200));
            $cTpl->mShow("tipo_2");
        }

        include "principalFim.php";
    break;

    /**************************
      NOTICIAS
     **************************/
    case "noticias":
        //include "principalInicio.php";
        principalInicio("Notícias - Guia Aparados da Serra");

        if ($cUrl->mGetParam(2)) {

            $cTpl = new cTemplate("tpl.noticia.htm");

            $cAutomates = new cAutomates("noticias");
            $noticia = $cAutomates->mRetornaDados($cUrl->mGetParam(2));

            $cTpl->mSet("titulo", $noticia[0]['tt_noticia']);
            $cTpl->mSet("descricao", nl2br($noticia[0]['ds_noticia']));

            $cTpl->mShow("inicio");

            $cAutomates = new cAutomates("noticias_fotos");
            $fotos = $cAutomates->mRetornaDados("", "cd_noticia ='" . $noticia[0]['cd_noticia'] . "'");

            for ($i = 0; $i < count($fotos); $i++) {

                $cTpl->mSet("tt_foto", $fotos[$i]["tt_foto"]);
                $cTpl->mSet("foto", $cGd->mGeraGd($caminhoFotos, $fotos[$i]["nm_foto"], 205, 1));
                $cTpl->mSet("link", $cGd->mGeraGd($caminhoFotos, $fotos[$i]["nm_foto"], 500, 3));
                $cTpl->mShow("while");
            }

            $cTpl->mShow("fim");
        } else {

            $cTpl = new cTemplate("tpl.noticiaLista.htm");
            $cTpl->mShow("inicio");

            $cAutomates = new cAutomates("noticias");
            $noticia = $cAutomates->mRetornaDados("");

            for ($i = 0; $i < count($noticia); $i++) {

                $cTpl->mShow("while");

                $cTpl->mSet("titulo", $noticia[$i]['tt_noticia']);
                $cTpl->mSet("descricao", $cAutomates->mBreveDescricao($noticia[$i]['ds_noticia'], 200));
                $cTpl->mSet("link", "noticias/" . $noticia[$i]['cd_noticia'] . "/" . $cUrl->mTrataString($noticia[$i]['tt_noticia']));

                $cAutomates = new cAutomates("noticias_fotos");
                $fotos = $cAutomates->mRetornaDados("", "cd_noticia ='" . $noticia[$i]['cd_noticia'] . "'");

                if (count($fotos) > 0) {

                    $cTpl->mSet("tt_foto", $fotos[0]["tt_foto"]);
                    $cTpl->mSet("foto", $cGd->mGeraGd($caminhoFotos, $fotos[0]["nm_foto"], 205, 1));

                    $cTpl->mShow("foto");
                }

                $cTpl->mShow("dados");

                $cTpl->mShow("fim");
            }
        }

        include "principalFim.php";
    break;

    /**************************
      INFORME-SE
     **************************/
    case "informe-se":
        //include "principalInicio.php";
        principalInicio("Informe-se - Guia Aparados da Serra");

        if ($cUrl->mGetParam(2)) {

            $cTpl = new cTemplate("tpl.informe.htm");

            $cAutomates = new cAutomates("informe");
            $informe = $cAutomates->mRetornaDados($cUrl->mGetParam(2));

            $cTpl->mSet("titulo", $informe[0]['tt_informe']);
            $cTpl->mSet("descricao", nl2br($informe[0]['ds_informe']));

            $cTpl->mShow("inicio");

            $cAutomates = new cAutomates("informe_fotos");
            $fotos = $cAutomates->mRetornaDados("", "cd_informe ='" . $informe[0]['cd_informe'] . "'");

            for ($i = 0; $i < count($fotos); $i++) {

                $cTpl->mSet("tt_foto", $fotos[$i]["tt_foto"]);
                $cTpl->mSet("foto", $cGd->mGeraGd($caminhoFotos, $fotos[$i]["nm_foto"], 205, 1));
                $cTpl->mSet("link", $cGd->mGeraGd($caminhoFotos, $fotos[$i]["nm_foto"], 500, 3));
                $cTpl->mShow("while");
            }

            $cTpl->mShow("fim");
        } else {

            $cTpl = new cTemplate("tpl.informeLista.htm");
            $cTpl->mShow("inicio");

            $cAutomates = new cAutomates("informe");
            $informe = $cAutomates->mRetornaDados("");

            for ($i = 0; $i < count($informe); $i++) {

                $cTpl->mShow("while");

                $cTpl->mSet("titulo", $informe[$i]['tt_informe']);
                $cTpl->mSet("descricao", $cAutomates->mBreveDescricao($informe[$i]['ds_informe'], 200));
                $cTpl->mSet("link", "informe-se/" . $informe[$i]['cd_informe'] . "/" . $cUrl->mTrataString($informe[$i]['tt_informe']));

                $cAutomates = new cAutomates("informe_fotos");
                $fotos = $cAutomates->mRetornaDados("", "cd_informe ='" . $informe[$i]['cd_informe'] . "'");

                if (count($fotos) > 0) {

                    $cTpl->mSet("tt_foto", $fotos[0]["tt_foto"]);
                    $cTpl->mSet("foto", $cGd->mGeraGd($caminhoFotos, $fotos[0]["nm_foto"], 205, 1));

                    $cTpl->mShow("foto");
                }

                $cTpl->mShow("dados");

                $cTpl->mShow("fim");
            }
        }

        include "principalFim.php";
    break;

    /**************************
      TRANSLADOS TRANSFER
     **************************/
    case "translados-transfer":
        //include "principalInicio.php";
        principalInicio("Translados/Transfer - Guia Aparados da Serra");

        $cTpl = new cTemplate("tpl.translado.htm");

        $cAutomates = new cAutomates("translado");
        $translado = $cAutomates->mRetornaDados();

        $cTpl->mSet("ds_translado", nl2br($translado[0]['ds_translado']));
        $cTpl->mShow("inicio");

        $cAutomates = new cAutomates("translado_fotos");
        $fotos = $cAutomates->mRetornaDados();

        for ($i = 0; $i < count($fotos); $i++) {

            $cTpl->mSet("tt_foto", $fotos[$i]["tt_foto"]);
            $cTpl->mSet("foto", $cGd->mGeraGd($caminhoFotos, $fotos[$i]["nm_foto"], 205, 1));
            $cTpl->mSet("link", $cGd->mGeraGd($caminhoFotos, $fotos[$i]["nm_foto"], 500, 3));
            $cTpl->mShow("while");
        }

        $cTpl->mShow("fim");

        include "principalFim.php";
        break;

    /**************************
      PACOTES PERSONALIZADOS
     **************************/
    case "pacotes-e-passeios-personalizados":
        //include "principalInicio.php";
        principalInicio("Pacotes e Passeios Personalizados - Guia Aparados da Serra");

        $cTpl = new cTemplate("tpl.pacotesPersonalizados.htm");

        $cAutomates = new cAutomates("personalizados");
        $personalizado = $cAutomates->mRetornaDados();

        $cTpl->mSet("ds_personalizado", nl2br($personalizado[0]['ds_personalizado']));
        $cTpl->mShow("inicio");

        $cAutomates = new cAutomates("personalizados_fotos");
        $fotos = $cAutomates->mRetornaDados();

        for ($i = 0; $i < count($fotos); $i++) {

            $cTpl->mSet("tt_foto", $fotos[$i]["tt_foto"]);
            $cTpl->mSet("foto", $cGd->mGeraGd($caminhoFotos, $fotos[$i]["nm_foto"], 205, 1));
            $cTpl->mSet("link", $cGd->mGeraGd($caminhoFotos, $fotos[$i]["nm_foto"], 500, 3));
            $cTpl->mShow("while");
        }

        $cTpl->mShow("fim");

        include "principalFim.php";
    break;

    /**************************
      GALERIA DE FOTOS
     **************************/
    case "galeria-de-fotos":
        //include "principalInicio.php";
        principalInicio("Galeria de Fotos - Guia Aparados da Serra");

        $cTpl = new cTemplate("tpl.galeria.htm");

        $cAutomates = new cAutomates("galeria");
        $galeria = $cAutomates->mRetornaDados();

        $cTpl->mSet("ds_galeria", nl2br($galeria[0]['ds_galeria']));
        $cTpl->mShow("inicio");

        $cAutomates = new cAutomates("galeria_fotos");
        $fotos = $cAutomates->mRetornaDados();

        for ($i = 0; $i < count($fotos); $i++) {

            $cTpl->mSet("tt_foto", $fotos[$i]["tt_foto"]);
            $cTpl->mSet("foto", $cGd->mGeraGd($caminhoFotos, $fotos[$i]["nm_foto"], 183, 1));
            $cTpl->mSet("link", $cGd->mGeraGd($caminhoFotos, $fotos[$i]["nm_foto"], 500, 3));
            $cTpl->mShow("while");
        }

        $cTpl->mShow("fim");

        include "principalFim.php";
    break;

    /**************************
      COMPRE O GUIA IMPRESSO
     **************************/
    case "compre-o-guia-impresso":
        //include "principalInicio.php";
        principalInicio("Compre o Guia Impresso - Guia Aparados da Serra");

        $cTpl = new cTemplate("tpl.compreGuia.htm");

        $cAutomates = new cAutomates("compre");
        $compre = $cAutomates->mRetornaDados();

        $cTpl->mSet("foto", $cGd->mGeraGd($caminhoFotos, $compre[0]["ft_compre"], 130, 1));
        $cTpl->mSet("link", $cGd->mGeraGd($caminhoFotos, $compre[0]["ft_compre"], 500, 3));
        $cTpl->mSet("ds_compre", nl2br($compre[0]['ds_compre']));

        $cTpl->mShow("");

        include "principalFim.php";
    break;

    /**************************
      MAPA
     **************************/
    case "visualizar-mapa":
        //include "principalInicio.php";
        principalInicio("Visualizar Mapa - Guia Aparados da Serra");

        $cTpl = new cTemplate("tpl.mapa.htm");

        $cAutomates = new cAutomates("mapa");
        $mapa = $cAutomates->mRetornaDados();

        $cTpl->mSet("ds_mapa", nl2br($mapa[0]['ds_mapa']));
        $cTpl->mShow("");

        include "principalFim.php";
    break;

    case "visualizar-mapa-jeep":

        //include "principalInicio.php";
        principalInicio("Visualizar Mapa - Guia Aparados da Serra");

        $cTpl = new cTemplate("tpl.mapa-jeep.htm");
        $cTpl->mShow("inicio");

        include "principalFim.php";

    break;

    /**************************
      Mapa 2
     **************************/
    case "mapa2":
        //include "principalInicio.php";
        principalInicio("mapa2 - Guia Aparados da Serra");

        $cTpl = new cTemplate("tpl.mapa2.htm");
        $cTpl->mShow("");

        include "principalFim.php";
    break;

    /**************************
      RESULTADOS BUSCA
     **************************/
    case "resultados":
        //include "principalInicio.php";
        principalInicio("Resultados - Guia Aparados da Serra");

        $cTpl = new cTemplate("tpl.resultados.htm");
        $cTpl->mShow("");

        include "principalFim.php";
    break;

    /**************************
      PARCEIROS
     **************************/
    case "parceiros":
        //include "principalInicio.php";
        principalInicio("Parceiros - Guia Aparados da Serra");

        $cTpl = new cTemplate("tpl.parceiros.htm");
        $cTpl->mShow("inicio");

        $cAutomates = new cAutomates("parceiros");
        $parceiro = $cAutomates->mRetornaDados();

        for ($j = 0; $j < count($parceiro); $j++) {

            $cTpl->mSet("foto", $cGd->mGeraGd($caminhoFotos, $parceiro[$j]["ft_parceiro"], 130, 1));
            $cTpl->mSet("link", "#1");
            if ($parceiro[$j]["st_parceiro"] != "") {
                $cTpl->mSet("link", $parceiro[$j]["st_parceiro"]);
            }

            $cTpl->mShow("while");
        }

        $cTpl->mShow("fim");

        include "principalFim.php";
    break;

    /**************************
      ANUNCIE
     **************************/
    case "anuncie":
        if ($cUrl->mGetParam(2) == "enviar") {
            if ($_POST['nome'] != "") {
                //GRAVA NO BANCO
                $cAutomates = new cAutomates("anuncie");
                $dados["dt_anuncie"] = "'" . date("Y-m-d H:i:s") . "'";
                $dados["nm_anuncie"] = "'" . $_POST["nome"] . "'";
                $dados["tel_anuncie"] = "'" . $_POST["telefone"] . "'";
                $dados["em_anuncie"] = "'" . $_POST["email"] . "'";
                $dados["cid_anuncie"] = "'" . $_POST["cidade"] . "'";
                $dados["uf_anuncie"] = "'" . $_POST["uf"] . "'";
                $dados["mensagem_anuncie"] = "'" . $_POST["mensagem"] . "'";
                $cAutomates->mAtualizaDados($dados);

                //MONTA EMAIL
                $cTpl = new cTemplate("tpl.email.htm");
                $cTpl->mSet("site", "Guia Aparados da Serra");
                $cTpl->mSet("pagina", "Anuncie");
                $cTpl->mSet("hora", date("H:i"));
                $cTpl->mSet("dia", date("d/m/Y"));

                $conteudo.= "<p><strong>Nome</strong><br />" . $_POST["nome"] . "</p>";
                $conteudo.= "<p><strong>Telefone/Fax</strong><br />" . $_POST["telefone"] . "</p>";
                $conteudo.= "<p><strong>Email</strong><br />" . $_POST["email"] . "</p>";
                $conteudo.= "<p><strong>Cidade</strong><br />" . $_POST["cidade"] . "</p>";
                $conteudo.= "<p><strong>UF</strong><br />" . $_POST["uf"] . "</p>";
                $conteudo.= "<p><strong>Observações</strong><br />" . $_POST["mensagem"] . "</p>";

                $cTpl->mSet("conteudo", $conteudo);
                $body = $cTpl->mShow("", 1);

                include "zpainel/class/inc.phpmailer.php";
                $PHPMailer = new PHPMailer();
                $PHPMailer->From = $_POST["email"];
                $PHPMailer->FromName = $_POST["nome"];
                $cAutomates = new cAutomates("anuncie_dados");
                $anuncie = $cAutomates->mRetornaDados();
                $PHPMailer->AddAddress($anuncie[0]["email_anuncie_dados"]);

                $PHPMailer->Subject = "Guia Aparados da Serra (Anuncie)";
                $PHPMailer->Body = $body;
                $PHPMailer->AltBody = $body;
                $PHPMailer->WordWrap = 50;

                if (!$PHPMailer->Send()) {
                    print("Erro ao enviar email, favor tentar novamente!<br />");
                    print("Erro: " . $PHPMailer->ErrorInfo . "");
                } else {
                    header("location: /anuncie/ok/");
                }
            } else {
                header("location: /anuncie/");
            }
        } else {
            //include "principalInicio.php";
            principalInicio("Anuncie - Guia Aparados da Serra");

            $cTpl = new cTemplate("tpl.anuncie.htm");
            $cTpl->mShow("");

            include "principalFim.php";

            if ($cUrl->mGetParam(2) == "ok")
                $cToo->mAlert("Enviado com sucesso! Aguarde uma resposta de nossa equipe. Obrigado!");
        }
    break;

    /**************************
      FALE CONOSCO
     **************************/
    case "fale-conosco":
        if ($cUrl->mGetParam(2) == "enviar") {
            if ($_POST['nome'] != "") {
                //GRAVA NO BANCO
                $cAutomates = new cAutomates("contato");
                $dados["dt_faleconosco"] = "'" . date("Y-m-d H:i:s") . "'";
                $dados["nm_faleconosco"] = "'" . $_POST["nome"] . "'";
                $dados["tel_faleconosco"] = "'" . $_POST["telefone"] . "'";
                $dados["em_faleconosco"] = "'" . $_POST["email"] . "'";
                $dados["cid_faleconosco"] = "'" . $_POST["cidade"] . "'";
                $dados["uf_faleconosco"] = "'" . $_POST["uf"] . "'";
                $dados["assunto_faleconosco"] = "'" . $_POST["assunto"] . "'";
                $dados["mensagem_faleconosco"] = "'" . $_POST["mensagem"] . "'";
                $cAutomates->mAtualizaDados($dados);

                //MONTA EMAIL
                $cTpl = new cTemplate("tpl.email.htm");
                $cTpl->mSet("site", "Guia Aparados da Serra");
                $cTpl->mSet("pagina", "Fale Conosco");
                $cTpl->mSet("hora", date("H:i"));
                $cTpl->mSet("dia", date("d/m/Y"));

                $conteudo.= "<p><strong>Nome</strong><br />" . $_POST["nome"] . "</p>";
                $conteudo.= "<p><strong>Telefone/Fax</strong><br />" . $_POST["telefone"] . "</p>";
                $conteudo.= "<p><strong>Email</strong><br />" . $_POST["email"] . "</p>";
                $conteudo.= "<p><strong>Cidade</strong><br />" . $_POST["cidade"] . "</p>";
                $conteudo.= "<p><strong>UF</strong><br />" . $_POST["uf"] . "</p>";
                $conteudo.= "<p><strong>Assunto</strong><br />" . $_POST["assunto"] . "</p>";
                $conteudo.= "<p><strong>Observações</strong><br />" . $_POST["mensagem"] . "</p>";

                $cTpl->mSet("conteudo", $conteudo);
                $body = $cTpl->mShow("", 1);

                $cAutomates = new cAutomates("contato_dados");
                $faleconosco = $cAutomates->mRetornaDados();
                
                $headers = "MIME-Version: 1.1\n";
                $headers .= "Content-type: text/html; charset=iso-8859-1\n";
                $headers .= "From: sistema@guiaaparadosdaserra.com.br\n";

                $assunto = "Guia Aparados da Serra (Fale Conosco)";

                if (!mail($faleconosco[0]["email_faleconosco_dados"],$assunto,$body,$headers, "-r"."sistema@guiaaparadosdaserra.com.br")){

                    $headers .= "Return-Path: sistema@guiaaparadosdaserra.com.br\n";

                    if (! mail($faleconosco[0]["email_faleconosco_dados"],$assunto,$body,$headers) ){
                        print_r("Erro ao enviar emails.");
                    }
                    else {
                        header("location: /fale-conosco/ok/");
                    }
                }
                else {
                        header("location: /fale-conosco/ok/");
                    }

                // include "zpainel/class/inc.phpmailer.php";
                // $PHPMailer = new PHPMailer();

                // $PHPMailer->From = "sistema@guiaaparadosdaserra.com.br";
                // $PHPMailer->FromName = "sistema@guiaaparadosdaserra.com.br";
                // $PHPMailer->Password = "si123654789";
                // $PHPMailer->Port = "587";

                // $cAutomates = new cAutomates("contato_dados");
                // $faleconosco = $cAutomates->mRetornaDados();
                // $PHPMailer->AddAddress($faleconosco[0]["email_faleconosco_dados"]);

                // $PHPMailer->Subject = "Guia Aparados da Serra (Fale Conosco)";
                // $PHPMailer->Body = $body;
                // $PHPMailer->AltBody = $body;
                // $PHPMailer->WordWrap = 50;

                // if (!$PHPMailer->Send()) {
                //     print("Erro ao enviar email, favor tentar novamente!<br />");
                //     print("Erro: " . $PHPMailer->ErrorInfo . "");
                // } else {
                //     header("location: /fale-conosco/ok/");
                // }
            } else {
                header("location: /fale-conosco/");
            }
        } else {
            //include "principalInicio.php";
            principalInicio("Fale Conosco - Guia Aparados da Serra");

            $cTpl = new cTemplate("tpl.faleConosco.htm");

            $cAutomates = new cAutomates("contato_dados");
            $contato = $cAutomates->mRetornaDados();

            $cTpl->mSet("rodape", nl2br($contato[0]['ds_faleconosco_dados']));

            $cTpl->mShow("");

            include "principalFim.php";

            if ($cUrl->mGetParam(2) == "ok")
                $cToo->mAlert("Enviado com sucesso! Aguarde uma resposta de nossa equipe. Obrigado!");
        }
    break;
        
     /**************************
       FALE CONOSCO ESCOLHA
     **************************/
    case "escolha-fale-conosco":
        //include "principalInicio.php";
        principalInicio("Fale Conosco - Guia Aparados da Serra");

        $cTpl = new cTemplate("tpl.faleConosco_escolha.htm");

        $cTpl->mShow("inicio");

        include "principalFim.php";
    break;

    /**************************
      COMPRAS E SERVICOS
     **************************/
    case "compras-e-servicos":

        if ($cUrl->mGetParam(2) == "enviar") {

            $cTpl = new cTemplate("tpl.pousada-enviar.htm");

            $cAutomates = new cAutomates("compras");

            $compra = $cAutomates->mRetornaDados($cUrl->mGetParam(3));
            principalInicio("Compras e Serviços > ". $compra[0]['nm_cidade'] ." - Guia Aparados da Serra");
            $cTpl->mSet("nm_cidade", $compra[0]['nm_cidade']);

            $cTpl->mSet("foto", $cGd->mGeraGd($caminhoFotos, $compra[0]["ft_compra"], 205, 1));
            $cTpl->mSet("link", $cGd->mGeraGd($caminhoFotos, $compra[0]["ft_compra"], 500, 3));
            $cTpl->mSet("cd_compra", $compra[0]['cd_compra']);
            $cTpl->mSet("nm_pousada", $compra[0]['nm_compra']);
            $cTpl->mSet("end_pousada", $compra[0]['end_compra'] . " - " . $compra[0]['nm_compra']);
            $cTpl->mSet("tel_pousada", $compra[0]['tel_compra']);

            if ($compra[0]['em_compra'] == "") {
                $email = "";
            } else {
                $email = "<strong>Email: </strong>" . $compra[0]['em_compra'];
            }

            $cTpl->mSet("em_pousada", $email);

            if ($compra[0]['st_compra'] == "") {
                $link = "Não possui";
            } else {
                $link = "<a href=\"" . $compra[0]['st_compra'] . "\" target=\"_blank\" class=\"none\">" . $compra[0]['st_compra'] . "</a>";
            }

            $cTpl->mSet("st_pousada", $link);
            $cTpl->mSet("ds_pousada", nl2br($compra[0]['ds_compra']));

            $cTpl->mShow("inicio");

            $cAutomFotos = new cAutomates("compras_fotos");
            $fotos = $cAutomFotos->mRetornaDados("", "cd_compra='" . $compra[0]['cd_compra'] . "'");

            for ($j = 0; $j < count($fotos); $j++) {

                $cTpl->mSet("tt_fotos", $fotos[$j]["tt_foto"]);
                $cTpl->mSet("fotos", $cGd->mGeraGd($caminhoFotos, $fotos[$j]["nm_foto"], 130, 1));
                $cTpl->mSet("links", $cGd->mGeraGd($caminhoFotos, $fotos[$j]["nm_foto"], 500, 3));

                $cTpl->mShow("while");
            }

            $cTpl->mShow("fim");

            if ($compra[0]['em_compra'] != "" && isset($compra[0]['em_compra'])) {
                
                $email = $compra[0]['em_compra'];

                //MONTA EMAIL
                $cTpl = new cTemplate("tpl.email.htm");
                $cTpl->mSet("site", "Guia Aparados da Serra");
                $cTpl->mSet("pagina", "Notificação");
                $cTpl->mSet("hora", date("H:i"));
                $cTpl->mSet("dia", date("d/m/Y"));

                $conteudo.= "<p>Mais um turista acaba de solicitar seu contado através do portal www.guiaaparadosdaserra.com.br. Boas vendas. <br/> 
                                Mensagem automática. Portais da Agência Guia Aparados. <br/>
                                \"Parceria existe quando a troca\". <p/>";

                $cTpl->mSet("conteudo", $conteudo);
                $body = $cTpl->mShow("", 1);

                include "zpainel/class/inc.phpmailer.php";
                $PHPMailer = new PHPMailer();

                $PHPMailer->From = "sistema@guiaaparadosdaserra.com.br";
                $PHPMailer->FromName = "sistema@guiaaparadosdaserra.com.br";
                $PHPMailer->Password = "si123654789";
                $PHPMailer->Port = "587";

                $PHPMailer->AddAddress($email);
                
                $PHPMailer->Subject = "Sistema Automático Notificação";
                $PHPMailer->Body = $body;
                $PHPMailer->AltBody = $body;
                $PHPMailer->WordWrap = 50;

                $PHPMailer->Send();
            }
            include "principalFim.php";
        }
        else if ($cUrl->mGetParam(2)) {

            $cTpl = new cTemplate("tpl.compras.htm");

            $cAutomates = new cAutomates("compras");

            if($cUrl->mGetParam(2) == 3){
                $compra = $cAutomates->mRetornaDados("", "compras.cd_cidade ='" . $cUrl->mGetParam(2) . "'");
                principalInicio("Compras e Serviços > ". $compra[0]['nm_cidade'] ." - Guia Aparados da Serra");
                $cTpl->mSet("nm_cidade", $compra[0]['nm_cidade']);
            }
            else{
                $compra = $cAutomates->mRetornaDados("", "compras.cd_cidade <> '3'", "compras.cd_cidade ASC");
                principalInicio("Compras e Serviços > Outros - Guia Aparados da Serra");
                $cTpl->mSet("nm_cidade", "Outros");
            }
            
            $cTpl->mShow("inicio");

            for ($i = 0; $i < count($compra); $i++) {

                $cTpl->mSet("foto", $cGd->mGeraGd($caminhoFotos, $compra[$i]["ft_compra"], 205, 1));
                $cTpl->mSet("link", $cGd->mGeraGd($caminhoFotos, $compra[$i]["ft_compra"], 500, 3));
                $cTpl->mSet("cd_compra", $compra[$i]['cd_compra']);
                $cTpl->mSet("link_compras", "/compras-e-servicos/enviar/" . $compra[$i]['cd_compra']);
                $cTpl->mSet("nm_compra", $compra[$i]['nm_compra']);
                $cTpl->mSet("end_compra", $compra[$i]['end_compra'] . " - " . $compra[$i]['nm_compra']);
                $cTpl->mSet("tel_compra", $compra[$i]['tel_compra']);

                if ($compra[$i]['em_compra'] == "") {
                    $email = "Não possui";
                } else {
                    $email = $compra[$i]['em_compra'];
                }

                $cTpl->mSet("em_compra", $email);

                if ($compra[$i]['st_compra'] == "") {
                    $link = "Não possui";
                } else {
                    $link = "<a href=\"" . $compra[$i]['st_compra'] . "\" target=\"_blank\" class=\"none\">" . $compra[$i]['st_compra'] . "</a>";
                }

                $cTpl->mSet("st_compra", $link);
                $cTpl->mSet("ds_compra", nl2br($compra[$i]['ds_compra']));

                $cTpl->mShow("while");

                $cAutomFotos = new cAutomates("compras_fotos");
                $fotos = $cAutomFotos->mRetornaDados("", "cd_compra='" . $compra[$i]['cd_compra'] . "'");

                for ($j = 0; $j < count($fotos); $j++) {

                    $cTpl->mSet("tt_fotos", $fotos[$j]["tt_foto"]);
                    $cTpl->mSet("fotos", $cGd->mGeraGd($caminhoFotos, $fotos[$j]["nm_foto"], 130, 1));
                    $cTpl->mSet("links", $cGd->mGeraGd($caminhoFotos, $fotos[$j]["nm_foto"], 500, 3));

                    $cTpl->mShow("while_while");
                }

                $cTpl->mShow("fim_while_while");

                $cTpl->mShow("fim");
            }

            include "principalFim.php";
        }
        else {
            header("Location: /");
        }
    break;

    /**************************
      AGENCIAS E GUIAS
     **************************/
    case "agencias-guias":

        if ($cUrl->mGetParam(2) == "enviar") {

            $cTpl = new cTemplate("tpl.pousada-enviar.htm");

            $cAutomates = new cAutomates("agencias");

            $agencia = $cAutomates->mRetornaDados($cUrl->mGetParam(3));
            principalInicio("Agências / Guias > ". $agencia[0]['nm_cidade'] ." - Guia Aparados da Serra");
            $cTpl->mSet("nm_cidade", $agencia[0]['nm_cidade']);

            $cTpl->mSet("foto", $cGd->mGeraGd($caminhoFotos, $agencia[0]["ft_agencia"], 205, 1));
            $cTpl->mSet("link", $cGd->mGeraGd($caminhoFotos, $agencia[0]["ft_agencia"], 500, 3));
            $cTpl->mSet("cd_agencia", $agencia[0]['cd_agencia']);
            $cTpl->mSet("nm_pousada", $agencia[0]['nm_agencia']);
            $cTpl->mSet("end_pousada", $agencia[0]['end_agencia'] . " - " . $agencia[0]['nm_cidade']);
            $cTpl->mSet("tel_pousada", $agencia[0]['tel_agencia']);

            if ($agencia[0]['em_agencia'] == "") {
                $email = "";
            } else {
                $email = "<strong>Email: </strong>" .  $agencia[0]['em_agencia'];
            }

            $cTpl->mSet("em_pousada", $email);

            if ($agencia[0]['st_agencia'] == "") {
                $link = "Não possui";
            } else {
                $link = "<a href=\"" . $agencia[0]['st_agencia'] . "\" target=\"_blank\" class=\"none\">" . $agencia[0]['st_agencia'] . "</a>";
            }

            $cTpl->mSet("st_pousada", $link);
            $cTpl->mSet("ds_pousada", nl2br($agencia[0]['ds_agencia']));

            $cTpl->mShow("inicio");

            $cAutomFotos = new cAutomates("agencias_fotos");
            $fotos = $cAutomFotos->mRetornaDados("", "cd_agencia='" . $agencia[0]['cd_agencia'] . "'");

            for ($j = 0; $j < count($fotos); $j++) {

                $cTpl->mSet("tt_fotos", $fotos[$j]["tt_foto"]);
                $cTpl->mSet("fotos", $cGd->mGeraGd($caminhoFotos, $fotos[$j]["nm_foto"], 130, 1));
                $cTpl->mSet("links", $cGd->mGeraGd($caminhoFotos, $fotos[$j]["nm_foto"], 500, 3));

                $cTpl->mShow("while");
            }

            $cTpl->mShow("fim");


            if ($agencia[0]['em_agencia'] != "" && isset($agencia[0]['em_agencia'])) {
                
                $email = $agencia[0]['em_agencia'];

                //MONTA EMAIL
                $cTpl = new cTemplate("tpl.email.htm");
                $cTpl->mSet("site", "Guia Aparados da Serra");
                $cTpl->mSet("pagina", "Notificação");
                $cTpl->mSet("hora", date("H:i"));
                $cTpl->mSet("dia", date("d/m/Y"));

                $conteudo.= "<p>Mais um turista acaba de solicitar seu contado através do portal www.guiaaparadosdaserra.com.br. Boas vendas. <br/> 
                                Mensagem automática. Portais da Agência Guia Aparados. <br/>
                                \"Parceria existe quando a troca\". <p/>";

                $cTpl->mSet("conteudo", $conteudo);
                $body = $cTpl->mShow("", 1);

                include "zpainel/class/inc.phpmailer.php";
                $PHPMailer = new PHPMailer();

                $PHPMailer->From = "sistema@guiaaparadosdaserra.com.br";
                $PHPMailer->FromName = "sistema@guiaaparadosdaserra.com.br";
                $PHPMailer->Password = "si123654789";
                $PHPMailer->Port = "587";

                $PHPMailer->AddAddress($email);

                $PHPMailer->Subject = "Sistema Automático Notificação";
                $PHPMailer->Body = $body;
                $PHPMailer->AltBody = $body;
                $PHPMailer->WordWrap = 50;

                $PHPMailer->Send();
            }

            include "principalFim.php";
        }
        else if ($cUrl->mGetParam(2)) {

            $cTpl = new cTemplate("tpl.agencias.htm");

            $cAutomates = new cAutomates("agencias");

            if($cUrl->mGetParam(2) == 3){
                $agencia = $cAutomates->mRetornaDados("", "agencias.cd_cidade ='" . $cUrl->mGetParam(2) . "'");
                principalInicio("Agências / Guias > ". $agencia[0]['nm_cidade'] ." - Guia Aparados da Serra");
                $cTpl->mSet("nm_cidade", $agencia[0]['nm_cidade']);
            }
            else{
                $agencia = $cAutomates->mRetornaDados("", "agencias.cd_cidade <> '3'", "agencias.cd_cidade ASC");
                principalInicio("Agências / Guias > Outros - Guia Aparados da Serra");
                $cTpl->mSet("nm_cidade", "Outros");
            }

            $cTpl->mShow("inicio");

            for ($i = 0; $i < count($agencia); $i++) {

                $cTpl->mSet("foto", $cGd->mGeraGd($caminhoFotos, $agencia[$i]["ft_agencia"], 205, 1));
                $cTpl->mSet("link", $cGd->mGeraGd($caminhoFotos, $agencia[$i]["ft_agencia"], 500, 3));
                $cTpl->mSet("cd_agencia", $agencia[$i]['cd_agencia']);
                $cTpl->mSet("link_guia", "/agencias-guias/enviar/" . $agencia[$i]['cd_agencia']);
                $cTpl->mSet("nm_agencia", $agencia[$i]['nm_agencia']);
                $cTpl->mSet("end_agencia", $agencia[$i]['end_agencia'] . " - " . $agencia[$i]['nm_cidade']);
                $cTpl->mSet("tel_agencia", $agencia[$i]['tel_agencia']);

                if ($agencia[$i]['em_agencia'] == "") {
                    $email = "Não possui";
                } else {
                    $email = $agencia[$i]['em_agencia'];
                }

                $cTpl->mSet("em_agencia", $email);

                if ($agencia[$i]['st_agencia'] == "") {
                    $link = "Não possui";
                } else {
                    $link = "<a href=\"" . $agencia[$i]['st_agencia'] . "\" target=\"_blank\" class=\"none\">" . $agencia[$i]['st_agencia'] . "</a>";
                }

                $cTpl->mSet("st_agencia", $link);
                $cTpl->mSet("ds_agencia", nl2br($agencia[$i]['ds_agencia']));

                $cTpl->mShow("while");

                $cAutomFotos = new cAutomates("agencias_fotos");
                $fotos = $cAutomFotos->mRetornaDados("", "cd_agencia='" . $agencia[$i]['cd_agencia'] . "'");

                for ($j = 0; $j < count($fotos); $j++) {

                    $cTpl->mSet("tt_fotos", $fotos[$j]["tt_foto"]);
                    $cTpl->mSet("fotos", $cGd->mGeraGd($caminhoFotos, $fotos[$j]["nm_foto"], 130, 1));
                    $cTpl->mSet("links", $cGd->mGeraGd($caminhoFotos, $fotos[$j]["nm_foto"], 500, 3));

                    $cTpl->mShow("while_while");
                }

                $cTpl->mShow("fim_while_while");

                $cTpl->mShow("fim");
            }

            include "principalFim.php";
        } else {
            header("Location: /");
        }
    break;

    /**************************
      ONDE COMER
     **************************/
    case "onde-comer":

        if ($cUrl->mGetParam(2) == "enviar") {

            $cTpl = new cTemplate("tpl.pousada-enviar.htm");

            $cAutomates = new cAutomates("onde_comer");

            $ondeComer = $cAutomates->mRetornaDados($cUrl->mGetParam(3));

            principalInicio("Onde Comer > ". $ondeComer[0]['nm_cidade'] ." - Guia Aparados da Serra");

            $cTpl->mSet("nm_cidade", $ondeComer[0]['nm_cidade']);

            $cTpl->mSet("foto", $cGd->mGeraGd($caminhoFotos, $ondeComer[0]["ft_comer"], 205, 1));
            $cTpl->mSet("link", $cGd->mGeraGd($caminhoFotos, $ondeComer[0]["ft_comer"], 500, 3));
            $cTpl->mSet("cd_comer", $ondeComer[0]['cd_comer']);
            $cTpl->mSet("nm_pousada", $ondeComer[0]['nm_comer']);
            $cTpl->mSet("link_comer", "/onde-comer/enviar/" . $ondeComer[0]['cd_comer']);
            $cTpl->mSet("end_pousada", $ondeComer[0]['end_comer'] . " - " . $ondeComer[0]['nm_cidade']);
            $cTpl->mSet("tel_pousada", $ondeComer[0]['tel_comer']);

            if ($ondeComer[0]['em_comer'] == "") {
                $email = "";
            } else {
                $email = "<strong>Email: </strong>" . $ondeComer[0]['em_comer'];

            }

            $cTpl->mSet("em_pousada", $email);

            if ($ondeComer[0]['st_comer'] == "") {
                $link = "Não possui";
            } else {
                $link = "<a href=\"" . $ondeComer[0]['st_comer'] . "\" target=\"_blank\" class=\"none\">" . $ondeComer[0]['st_comer'] . "</a>";
            }

            $cTpl->mSet("st_pousada", $link);
            $cTpl->mSet("ds_pousada", nl2br($ondeComer[0]['ds_comer']));

            $cTpl->mShow("inicio");

            $cAutomFotos = new cAutomates("onde_comer_fotos");
            $fotos = $cAutomFotos->mRetornaDados("", "cd_comer='" . $ondeComer[0]['cd_comer'] . "'");

            for ($j = 0; $j < count($fotos); $j++) {

                $cTpl->mSet("tt_fotos", $fotos[$j]["tt_foto"]);
                $cTpl->mSet("fotos", $cGd->mGeraGd($caminhoFotos, $fotos[$j]["nm_foto"], 130, 1));
                $cTpl->mSet("links", $cGd->mGeraGd($caminhoFotos, $fotos[$j]["nm_foto"], 500, 3));

                $cTpl->mShow("while");
            }
            $cTpl->mShow("fim");

            if ($ondeComer[0]['em_comer'] != "" && isset($ondeComer[0]['em_comer'])) {

                $email = $ondeComer[0]['em_comer'];

                //MONTA EMAIL
                $cTpl = new cTemplate("tpl.email.htm");
                $cTpl->mSet("site", "Guia Aparados da Serra");
                $cTpl->mSet("pagina", "Notificação");
                $cTpl->mSet("hora", date("H:i"));
                $cTpl->mSet("dia", date("d/m/Y"));

                $conteudo.= "<p>Mais um turista acaba de solicitar seu contado através do portal www.guiaaparadosdaserra.com.br. Boas vendas. <br/> 
                                Mensagem automática. Portais da Agência Guia Aparados. <br/>
                                \"Parceria existe quando a troca\". <p/>";

                $cTpl->mSet("conteudo", $conteudo);
                $body = $cTpl->mShow("", 1);

                include "zpainel/class/inc.phpmailer.php";
                $PHPMailer = new PHPMailer();

                $PHPMailer->From = "sistema@guiaaparadosdaserra.com.br";
                $PHPMailer->FromName = "sistema@guiaaparadosdaserra.com.br";
                $PHPMailer->Password = "si123654789";
                $PHPMailer->Port = "587";

                $PHPMailer->AddAddress($email);

                $PHPMailer->Subject = "Sistema Automático Notificação";
                $PHPMailer->Body = $body;
                $PHPMailer->AltBody = $body;
                $PHPMailer->WordWrap = 50;

                $PHPMailer->Send();
            }
            include "principalFim.php";
        }
        else if ($cUrl->mGetParam(2)) {

            $cTpl = new cTemplate("tpl.ondeComer.htm");

            $cAutomates = new cAutomates("onde_comer");

            if($cUrl->mGetParam(2) == 3){
                $ondeComer = $cAutomates->mRetornaDados("", "onde_comer.cd_cidade ='" . $cUrl->mGetParam(2) . "'");

                principalInicio("Onde Comer > ". $ondeComer[0]['nm_cidade'] ." - Guia Aparados da Serra");

                $cTpl->mSet("nm_cidade", $ondeComer[0]['nm_cidade']);
            }
            else{
                $ondeComer = $cAutomates->mRetornaDados("", "onde_comer.cd_cidade <> '3'", "onde_comer.cd_cidade ASC");
                principalInicio("Onde Comer > Outros - Guia Aparados da Serra");
                $cTpl->mSet("nm_cidade", "Outros");
            }

            $cTpl->mShow("inicio");

            for ($i = 0; $i < count($ondeComer); $i++) {

                $cTpl->mSet("foto", $cGd->mGeraGd($caminhoFotos, $ondeComer[$i]["ft_comer"], 205, 1));
                $cTpl->mSet("link", $cGd->mGeraGd($caminhoFotos, $ondeComer[$i]["ft_comer"], 500, 3));
                $cTpl->mSet("cd_comer", $ondeComer[$i]['cd_comer']);
                $cTpl->mSet("nm_comer", $ondeComer[$i]['nm_comer']);
                $cTpl->mSet("link_comer", "/onde-comer/enviar/" . $ondeComer[$i]['cd_comer']);
                $cTpl->mSet("end_comer", $ondeComer[$i]['end_comer'] . " - " . $ondeComer[$i]['nm_cidade']);
                $cTpl->mSet("tel_comer", $ondeComer[$i]['tel_comer']);

                if ($ondeComer[$i]['em_comer'] == "") {
                    $email = "Não possui";
                } else {
                    $email = $ondeComer[$i]['em_comer'];
                }

                $cTpl->mSet("em_comer", $email);

                if ($ondeComer[$i]['st_comer'] == "") {
                    $link = "Não possui";
                } else {
                    $link = "<a href=\"" . $ondeComer[$i]['st_comer'] . "\" target=\"_blank\" class=\"none\">" . $ondeComer[$i]['st_comer'] . "</a>";
                }

                $cTpl->mSet("st_comer", $link);
                $cTpl->mSet("ds_comer", nl2br($ondeComer[$i]['ds_comer']));

                $cTpl->mShow("while");

                $cAutomFotos = new cAutomates("onde_comer_fotos");
                $fotos = $cAutomFotos->mRetornaDados("", "cd_comer='" . $ondeComer[$i]['cd_comer'] . "'");

                for ($j = 0; $j < count($fotos); $j++) {

                    $cTpl->mSet("tt_fotos", $fotos[$j]["tt_foto"]);
                    $cTpl->mSet("fotos", $cGd->mGeraGd($caminhoFotos, $fotos[$j]["nm_foto"], 130, 1));
                    $cTpl->mSet("links", $cGd->mGeraGd($caminhoFotos, $fotos[$j]["nm_foto"], 500, 3));

                    $cTpl->mShow("while_while");
                }

                $cTpl->mShow("fim_while_while");

                $cTpl->mShow("fim");
            }

            include "principalFim.php";
        } else {
            header("Location: /");
        }
    break;

    /**************************
      ONDE FICAR
     **************************/
    case "onde-ficar":

        if($cUrl->mGetParam(2) == "enviar"){

            $cTpl = new cTemplate("tpl.pousada-enviar.htm");

            $cAutomates = new cAutomates("onde_ficar");

            $onde = $cAutomates->mRetornaDados($cUrl->mGetParam(3));

            principalInicio("Onde Ficar > ". $onde[0]['nm_cidade'] ." - Guia Aparados da Serra");

            $cTpl->mSet("nm_cidade", $onde[0]['nm_cidade']);
        
            $cTpl->mSet("foto", $cGd->mGeraGd($caminhoFotos, $onde[0]["ft_ficar"], 205, 1));
            $cTpl->mSet("link", $cGd->mGeraGd($caminhoFotos, $onde[0]["ft_ficar"], 500, 3));

            $cTpl->mSet("nm_pousada", $onde[0]['nm_ficar']);
            $cTpl->mSet("end_pousada", $onde[0]['end_ficar'] . " - " . $onde[0]['nm_cidade']);
            $cTpl->mSet("tel_pousada", $onde[0]['tel_ficar']);

            if ($onde[0]['em_ficar'] == "") {
                $email = "";
            } else {
                $email = "<strong>Email: </strong>" . $onde[0]['em_ficar'];
            }

            $cTpl->mSet("em_pousada", $email);

            if ($onde[0]['st_ficar'] == "") {
                $link = "Não possui";
            } else {

                $link = "<a href=\"" . $onde[0]['st_ficar'] . "\" target=\"_blank\" class=\"none\">" . $onde[0]['st_ficar'] . "</a>";
            }

            $cTpl->mSet("st_pousada", $link);
            $cTpl->mSet("ds_pousada", nl2br($onde[0]['ds_ficar']));

            $cTpl->mShow("inicio");

            $cAutomFotos = new cAutomates("onde_ficar_fotos");
            $fotos = $cAutomFotos->mRetornaDados("", "cd_ficar='" . $onde[0]['cd_ficar'] . "'");

            for ($j = 0; $j < count($fotos); $j++) {

                $cTpl->mSet("tt_fotos", $fotos[$j]["tt_foto"]);
                $cTpl->mSet("fotos", $cGd->mGeraGd($caminhoFotos, $fotos[$j]["nm_foto"], 130, 1));
                $cTpl->mSet("links", $cGd->mGeraGd($caminhoFotos, $fotos[$j]["nm_foto"], 500, 3));

                $cTpl->mShow("while");
            }
            $cTpl->mShow("fim");

            if ($onde[0]['em_ficar'] != "" && isset($onde[0]['em_ficar'])) {
                
                $email = $onde[0]['em_ficar'];

                //MONTA EMAIL
                $cTpl = new cTemplate("tpl.email.htm");
                $cTpl->mSet("site", "Guia Aparados da Serra");
                $cTpl->mSet("pagina", "Notificação");
                $cTpl->mSet("hora", date("H:i"));
                $cTpl->mSet("dia", date("d/m/Y"));

                $conteudo.= "<p>Mais um turista acaba de solicitar seu contado através do portal www.guiaaparadosdaserra.com.br. Boas vendas. <br/> 
                                Mensagem automática. Portais da Agência Guia Aparados. <br/>
                                \"Parceria existe quando há troca\". <p/>";

                $cTpl->mSet("conteudo", $conteudo);
                $body = $cTpl->mShow("", 1);

                include "zpainel/class/inc.phpmailer.php";
                $PHPMailer = new PHPMailer(true);

                $PHPMailer->From = "sistema@guiaaparadosdaserra.com.br";
                $PHPMailer->FromName = "sistema@guiaaparadosdaserra.com.br";
                $PHPMailer->Password = "si123654789";
                $PHPMailer->Port = "587";

                $PHPMailer->AddAddress($email);

                $PHPMailer->Subject = "Sistema Automático Notificação";
                $PHPMailer->Body = $body;
                $PHPMailer->AltBody = $body;
                $PHPMailer->WordWrap = 50;
                
                $PHPMailer->Send();
            }
            
            include "principalFim.php";
        }
        else if ($cUrl->mGetParam(2)) {

            //include "principalInicio.php";

            $cTpl = new cTemplate("tpl.ondeFicar.htm");

            $cAutomates = new cAutomates("onde_ficar");

            if($cUrl->mGetParam(2) == 3){

                $ondeFicar = $cAutomates->mRetornaDados("", "onde_ficar.cd_cidade ='" . $cUrl->mGetParam(2) . "'");

                principalInicio("Onde Ficar > ". $ondeFicar[0]['nm_cidade'] ." - Guia Aparados da Serra");

                $cTpl->mSet("nm_cidade", $ondeFicar[0]['nm_cidade']);
            }
            else{
                $ondeFicar = $cAutomates->mRetornaDados("", "onde_ficar.cd_cidade <> '3'", "onde_ficar.cd_cidade ASC");
                principalInicio("Onde Ficar > Outros - Guia Aparados da Serra");
                $cTpl->mSet("nm_cidade", "Outros");
            }
            
            $cTpl->mShow("inicio");

            for ($i = 0; $i < count($ondeFicar); $i++) {

                $cTpl->mSet("foto", $cGd->mGeraGd($caminhoFotos, $ondeFicar[$i]["ft_ficar"], 205, 1));
                $cTpl->mSet("link", $cGd->mGeraGd($caminhoFotos, $ondeFicar[$i]["ft_ficar"], 500, 3));
                $cTpl->mSet("cd_ficar", $ondeFicar[$i]['cd_ficar']);
                $cTpl->mSet("link_ficar", "/onde-ficar/enviar/" . $ondeFicar[$i]['cd_ficar']);
                $cTpl->mSet("nm_ficar", $ondeFicar[$i]['nm_ficar']);
                $cTpl->mSet("end_ficar", $ondeFicar[$i]['end_ficar'] . " - " . $ondeFicar[$i]['nm_cidade']);
                $cTpl->mSet("tel_ficar", $ondeFicar[$i]['tel_ficar']);

                if ($ondeFicar[$i]['em_ficar'] == "") {
                    $email = "Não possui";
                } else {
                    $email = $ondeFicar[$i]['em_ficar'];
                }

                $cTpl->mSet("em_ficar", $email);

                if ($ondeFicar[$i]['st_ficar'] == "") {
                    $link = "Não possui";
                } else {
                    $link = "<a href=\"" . $ondeFicar[$i]['st_ficar'] . "\" target=\"_blank\" class=\"none\">" . $ondeFicar[$i]['st_ficar'] . "</a>";
                }

                $cTpl->mSet("st_ficar", $link);
                $cTpl->mSet("ds_ficar", nl2br($ondeFicar[$i]['ds_ficar']));

                $cTpl->mShow("while");

                $cAutomFotos = new cAutomates("onde_ficar_fotos");
                $fotos = $cAutomFotos->mRetornaDados("", "cd_ficar='" . $ondeFicar[$i]['cd_ficar'] . "'");

                for ($j = 0; $j < count($fotos); $j++) {

                    $cTpl->mSet("tt_fotos", $fotos[$j]["tt_foto"]);
                    $cTpl->mSet("fotos", $cGd->mGeraGd($caminhoFotos, $fotos[$j]["nm_foto"], 130, 1));
                    $cTpl->mSet("links", $cGd->mGeraGd($caminhoFotos, $fotos[$j]["nm_foto"], 500, 3));

                    $cTpl->mShow("while_while");
                }

                $cTpl->mShow("fim_while_while");

                $cTpl->mShow("fim");
            }

            include "principalFim.php";
        } else {
            header("Location: /");
        }
    break;

    /**************************
      POUSADA ALVORADA
     **************************/

	case "pousada-alvorada":

        //include "principalInicio.php";
        principalInicio("Pousada - Guia Aparados da Serra");

        $cTpl = new cTemplate("tpl.alvorada.htm");

        $cAutomates = new cAutomates("onde_ficar");
        $ondeFicar = $cAutomates->mRetornaDados("", "onde_ficar.cd_ficar = 36");

        $cTpl->mSet("nm_cidade", $ondeFicar[0]['nm_cidade']);

        $cTpl->mShow("inicio");


        $cTpl->mSet("foto", $cGd->mGeraGd($caminhoFotos, $ondeFicar[0]["ft_ficar"], 205, 1));
        $cTpl->mSet("link", $cGd->mGeraGd($caminhoFotos, $ondeFicar[0]["ft_ficar"], 500, 3));
        $cTpl->mSet("cd_ficar", $ondeFicar[0]['cd_ficar']);
        $cTpl->mSet("nm_ficar", $ondeFicar[0]['nm_ficar']);
        $cTpl->mSet("end_ficar", $ondeFicar[0]['end_ficar'] . " - " . $ondeFicar[$i]['nm_cidade']);
        $cTpl->mSet("tel_ficar", $ondeFicar[0]['tel_ficar']);

        if ($ondeFicar[0]['em_ficar'] == "") {
            $email = "Não possui";
        } else {
            $email = $ondeFicar[0]['em_ficar'];
        }

        $cTpl->mSet("em_ficar", $email);

        if ($ondeFicar[0]['st_ficar'] == "") {
            $link = "Não possui";
        } else {
            $link = "<a href=\"" . $ondeFicar[0]['st_ficar'] . "\" target=\"_blank\" class=\"none\">" . $ondeFicar[$i]['st_ficar'] . "</a>";
        }

        $cTpl->mSet("st_ficar", $link);
        $cTpl->mSet("ds_ficar", nl2br($ondeFicar[0]['ds_ficar']));

        $cTpl->mShow("while");

        $cAutomFotos = new cAutomates("onde_ficar_fotos");
        $fotos = $cAutomFotos->mRetornaDados("", "cd_ficar='" . $ondeFicar[0]['cd_ficar'] . "'");

        for ($j = 0; $j < count($fotos); $j++) {

            $cTpl->mSet("tt_fotos", $fotos[$j]["tt_foto"]);
            $cTpl->mSet("fotos", $cGd->mGeraGd($caminhoFotos, $fotos[$j]["nm_foto"], 130, 1));
            $cTpl->mSet("links", $cGd->mGeraGd($caminhoFotos, $fotos[$j]["nm_foto"], 500, 3));

            $cTpl->mShow("while_while");
        }

        $cTpl->mShow("fim_while_while");

        $cTpl->mShow("fim");
        
        include "principalFim.php";
    break;
		

    /**************************
      PARQUES NACIONAIS
     **************************/
    case "parques-nacionais":
        //include "principalInicio.php";
        principalInicio("Parques Nacionais - Guia Aparados da Serra");

        if ($cUrl->mGetParam(2)) {

            $cTpl = new cTemplate("tpl.parques.htm");

            $cAutomates = new cAutomates("parques");
            $parques = $cAutomates->mRetornaDados($cUrl->mGetParam(2));

            $cTpl->mSet("titulo", $parques[0]['nm_parques']);
            $cTpl->mSet("ds_parques", nl2br($parques[0]['ds_parques']));
            $cTpl->mShow("inicio");

            $cAutomates = new cAutomates("parques_fotos");
            $fotos = $cAutomates->mRetornaDados("", "cd_parques ='" . $parques[0]['cd_parques'] . "'");

            for ($i = 0; $i < count($fotos); $i++) {

                $cTpl->mSet("tt_foto", $fotos[$i]["tt_foto"]);
                $cTpl->mSet("foto", $cGd->mGeraGd($caminhoFotos, $fotos[$i]["nm_foto"], 205, 1));
                $cTpl->mSet("link", $cGd->mGeraGd($caminhoFotos, $fotos[$i]["nm_foto"], 500, 3));
                $cTpl->mShow("while");
            }

            $cTpl->mShow("fim");
        } else {

            $cTpl = new cTemplate("tpl.parquesInternas.htm");
            $cTpl->mShow("inicio");

            $cAutomates = new cAutomates("parques");
            $parques = $cAutomates->mRetornaDados("");

            for ($i = 0; $i < count($parques); $i++) {

                $cTpl->mShow("while");

                $cTpl->mSet("titulo", $parques[$i]['nm_parques']);
                $cTpl->mSet("descricao", $cAutomates->mBreveDescricao($parques[$i]['ds_parques'], 200));
                $cTpl->mSet("link", "parques-nacionais/" . $parques[$i]['cd_parques'] . "/" . $cUrl->mTrataString($parques[$i]['nm_parques']));

                $cAutomates = new cAutomates("parques_fotos");
                $fotos = $cAutomates->mRetornaDados("", "cd_parques ='" . $parques[$i]['cd_parques'] . "'");

                if (count($fotos) > 0) {

                    $cTpl->mSet("tt_foto", $fotos[0]["tt_foto"]);
                    $cTpl->mSet("foto", $cGd->mGeraGd($caminhoFotos, $fotos[0]["nm_foto"], 205, 1));

                    $cTpl->mShow("foto");
                }

                $cTpl->mShow("dados");

                $cTpl->mShow("fim");
            }
        }

        include "principalFim.php";
    break;
	
	/**************************
      APARADOS DA SERRA TURISMO
     **************************/
    case "aparados-da-serra-turismo":
        //include "principalInicio.php";
        principalInicio("Aparados da Serra Turismo - Guia Aparados da Serra");

        if ($cUrl->mGetParam(2)) {

            $cTpl = new cTemplate("tpl.turismo.htm");

            $cAutomates = new cAutomates("turismo");
            $parques = $cAutomates->mRetornaDados($cUrl->mGetParam(2));

            $cTpl->mSet("titulo", $parques[0]['nm_turismo']);
            $cTpl->mSet("ds_turismo", nl2br($parques[0]['ds_turismo']));
            $cTpl->mShow("inicio");

            $cAutomates = new cAutomates("turismo_fotos");
            $fotos = $cAutomates->mRetornaDados("", "cd_turismo ='" . $parques[0]['cd_turismo'] . "'");

            for ($i = 0; $i < count($fotos); $i++) {

                $cTpl->mSet("tt_foto", $fotos[$i]["tt_foto"]);
                $cTpl->mSet("foto", $cGd->mGeraGd($caminhoFotos, $fotos[$i]["nm_foto"], 205, 1));
                $cTpl->mSet("link", $cGd->mGeraGd($caminhoFotos, $fotos[$i]["nm_foto"], 500, 3));
                $cTpl->mShow("while");
            }

            $cTpl->mShow("fim");
        } 
		else {

            $cTpl = new cTemplate("tpl.turismoInternas.htm");
            $cTpl->mShow("inicio");

            $cAutomates = new cAutomates("turismo");
            $parques = $cAutomates->mRetornaDados("");

            for ($i = 0; $i < count($parques); $i++) {

                $cTpl->mShow("while");

                $cTpl->mSet("titulo", $parques[$i]['nm_turismo']);
                $cTpl->mSet("descricao", $cAutomates->mBreveDescricao($parques[$i]['ds_turismo'], 200));
                $cTpl->mSet("link", 'aparados-da-serra-turismo/' . $parques[$i]['cd_turismo'] . "/" . $cUrl->mTrataString($parques[$i]['nm_turismo']));

                $cAutomates = new cAutomates("turismo_fotos");
                $fotos = $cAutomates->mRetornaDados("", "cd_turismo ='" . $parques[$i]['cd_turismo'] . "'");

                if (count($fotos) > 0) {

                    $cTpl->mSet("tt_foto", $fotos[0]["tt_foto"]);
                    $cTpl->mSet("foto", $cGd->mGeraGd($caminhoFotos, $fotos[0]["nm_foto"], 205, 1));

                    $cTpl->mShow("foto");
                }

                $cTpl->mShow("dados");

                $cTpl->mShow("fim");
            }
        }

        include "principalFim.php";
    break;

    /**************************
      SOBRE APARADOS
     **************************/
    case "sobre-os-aparados-da-serra":
        //include "principalInicio.php";
        principalInicio("Sobre os Aparados da Serra - Guia Aparados da Serra");

        $cTpl = new cTemplate("tpl.sobreAparados.htm");

        $cAutomates = new cAutomates("sobre_aparados");
        $sobre_aparados = $cAutomates->mRetornaDados();

        $cTpl->mSet("ds_sobre_aparados", nl2br($sobre_aparados[0]['ds_sobre_aparados']));
        $cTpl->mShow("inicio");

        $cAutomates = new cAutomates("sobre_aparados_fotos");
        $fotos = $cAutomates->mRetornaDados();

        for ($i = 0; $i < count($fotos); $i++) {

            $cTpl->mSet("tt_foto", $fotos[$i]["tt_foto"]);
            $cTpl->mSet("foto", $cGd->mGeraGd($caminhoFotos, $fotos[$i]["nm_foto"], 205, 1));
            $cTpl->mSet("link", $cGd->mGeraGd($caminhoFotos, $fotos[$i]["nm_foto"], 500, 3));
            $cTpl->mShow("while");
        }

        $cTpl->mShow("fim");

        include "principalFim.php";
    break;

    /**************************
      AS CIDADES
     **************************/
    case "as-cidades":
        //include "principalInicio.php";
        principalInicio("As Cidades -  Guias Aparados da Serra");

        if ($cUrl->mGetParam(2)) {

            $cTpl = new cTemplate("tpl.cidades.htm");

            $cAutomates = new cAutomates("cidades");
            $cidades = $cAutomates->mRetornaDados($cUrl->mGetParam(2));

            $cTpl->mSet("titulo", $cidades[0]['nm_cidades']);
            $cTpl->mSet("ds_cidades", nl2br($cidades[0]['ds_cidades']));
            $cTpl->mShow("inicio");

            $cAutomates = new cAutomates("cidades_fotos");
            $fotos = $cAutomates->mRetornaDados("", "cd_cidades ='" . $cidades[0]['cd_cidades'] . "'");

            for ($i = 0; $i < count($fotos); $i++) {

                $cTpl->mSet("tt_foto", $fotos[$i]["tt_foto"]);
                $cTpl->mSet("foto", $cGd->mGeraGd($caminhoFotos, $fotos[$i]["nm_foto"], 205, 1));
                $cTpl->mSet("link", $cGd->mGeraGd($caminhoFotos, $fotos[$i]["nm_foto"], 500, 3));
                $cTpl->mShow("while");
            }

            $cTpl->mShow("fim");
        } else {

            $cTpl = new cTemplate("tpl.cidadesInternas.htm");
            $cTpl->mShow("inicio");

            $cAutomates = new cAutomates("cidades");
            $cidades = $cAutomates->mRetornaDados("");

            for ($i = 0; $i < count($cidades); $i++) {

                $cTpl->mShow("while");

                $cTpl->mSet("titulo", $cidades[$i]['nm_cidades']);
                $cTpl->mSet("descricao", $cAutomates->mBreveDescricao($cidades[$i]['ds_cidades'], 200));
                $cTpl->mSet("link", "as-cidades/" . $cidades[$i]['cd_cidades'] . "/" . $cUrl->mTrataString($cidades[$i]['nm_cidades']));

                $cAutomates = new cAutomates("cidades_fotos");
                $fotos = $cAutomates->mRetornaDados("", "cd_cidades ='" . $cidades[$i]['cd_cidades'] . "'");

                if (count($fotos) > 0) {

                    $cTpl->mSet("tt_foto", $fotos[0]["tt_foto"]);
                    $cTpl->mSet("foto", $cGd->mGeraGd($caminhoFotos, $fotos[0]["nm_foto"], 205, 1));

                    $cTpl->mShow("foto");
                }

                $cTpl->mShow("dados");

                $cTpl->mShow("fim");
            }
        }

        include "principalFim.php";
    break;

    /**************************
      PRODUTOS
     **************************/
    case "produtos":
        //include "principalInicio.php";
        principalInicio("Produtos - Guia Aparados da Serra");
        if ($cUrl->mGetParam(2)) {

            $cTpl = new cTemplate("tpl.produtosInternas.htm");

            $cAutomates = new cAutomates("produtos");
            $produto = $cAutomates->mRetornaDados($cUrl->mGetParam(2));

            $cTpl->mSet("titulo", $produto[0]['nm_produto']);
            $cTpl->mSet("preco", $produto[0]['pr_produto']);
            $cTpl->mSet("descricao", nl2br($produto[0]['ds_produto']));

            $cTpl->mSet("foto", $cGd->mGeraGd($caminhoFotos, $produto[0]["ft_produto"], 205, 1));
            $cTpl->mSet("link", $cGd->mGeraGd($caminhoFotos, $produto[0]["ft_produto"], 500, 3));

            $cTpl->mShow("");
        } else {

            $cTpl = new cTemplate("tpl.produtos.htm");
            $cTpl->mShow("inicio");

            $cAutomates = new cAutomates("produtos");
            $produto = $cAutomates->mRetornaDados("");

            for ($i = 0; $i < count($produto); $i++) {

                $cTpl->mSet("titulo", $produto[$i]['nm_produto']);
                $cTpl->mSet("descricao", $cAutomates->mBreveDescricao($produto[$i]['ds_produto'], 200));
                $cTpl->mSet("link", "produtos/" . $produto[$i]['cd_produto'] . "/" . $cUrl->mTrataString($produto[$i]['nm_produto']));
                $cTpl->mSet("foto", $cGd->mGeraGd($caminhoFotos, $produto[$i]["ft_produto"], 205, 1));

                $cTpl->mShow("while");
            }
        }

        include "principalFim.php";
    break;

    /**************************
      TELEFONES UTEIS
     **************************/
    case "telefones-uteis":
        //include "principalInicio.php";
        principalInicio("Telefones Úteis - Guia Aparados da Serra");

        $cTpl = new cTemplate("tpl.dicas.htm");

        $cAutomates = new cAutomates("dicas");
        $dicas = $cAutomates->mRetornaDados();

        $cTpl->mSet("ds_dicas", nl2br($dicas[0]['ds_dicas']));
        $cTpl->mShow("inicio");

        $cAutomates = new cAutomates("dicas_fotos");
        $fotos = $cAutomates->mRetornaDados();

        for ($i = 0; $i < count($fotos); $i++) {

            $cTpl->mSet("tt_foto", $fotos[$i]["tt_foto"]);
            $cTpl->mSet("foto", $cGd->mGeraGd($caminhoFotos, $fotos[$i]["nm_foto"], 205, 1));
            $cTpl->mSet("link", $cGd->mGeraGd($caminhoFotos, $fotos[$i]["nm_foto"], 500, 3));
            $cTpl->mShow("while");
        }

        $cTpl->mShow("fim");

        include "principalFim.php";
    break;

    /**************************
      NIVEL DE ATIVIDADES
     **************************/
    case "nivel-de-atividades":
        //include "principalInicio.php";
        principalInicio("Nível de Atividades - Guia Aparados da Serra");
        $cTpl = new cTemplate("tpl.atividades.htm");

        $cAutomates = new cAutomates("atividades");
        $atividades = $cAutomates->mRetornaDados();

        $cTpl->mSet("ds_atividades", nl2br($atividades[0]['ds_atividades']));
        $cTpl->mShow("inicio");

        $cAutomates = new cAutomates("atividades_fotos");
        $fotos = $cAutomates->mRetornaDados();

        for ($i = 0; $i < count($fotos); $i++) {

            $cTpl->mSet("tt_foto", $fotos[$i]["tt_foto"]);
            $cTpl->mSet("foto", $cGd->mGeraGd($caminhoFotos, $fotos[$i]["nm_foto"], 205, 1));
            $cTpl->mSet("link", $cGd->mGeraGd($caminhoFotos, $fotos[$i]["nm_foto"], 500, 3));
            $cTpl->mShow("while");
        }

        $cTpl->mShow("fim");

        include "principalFim.php";
    break;

    /**************************
      EXPEDIÇÃO APARADOS 4x4
     **************************/
    case "trilha-dos-canyons":

        principalInicio("Trilha dos Canyons e Cachoeiras 4x4 - Guia Aparados da Serra");
        $cTpl = new cTemplate("tpl.trilha-dos-canyons.htm");

        $cAutomates = new cAutomates("trilha_canyons");
        $trilha = $cAutomates->mRetornaDados();

        $cTpl->mShow("inicio");

        $cAutomates = new cAutomates("trilha_canyons_fotos");
        $fotos = $cAutomates->mRetornaDados();

        for ($i = 0; $i < count($fotos); $i++) {

            $cTpl->mSet("tt_foto", $fotos[$i]["tt_foto"]);
            $cTpl->mSet("foto", $cGd->mGeraGd($caminhoFotos, $fotos[$i]["nm_foto"], 205, 1));
            $cTpl->mSet("link", $cGd->mGeraGd($caminhoFotos, $fotos[$i]["nm_foto"], 500, 3));
            $cTpl->mShow("while");
        }

        $cTpl->mSet("ds_excursao", nl2br($trilha[0]['ds_excursao']));

        $cTpl->mShow("fim");

        include "principalFim.php";
    break;

    /**************************
      EXPEDIÇÃO APARADOS 4x4
     **************************/
    case "expedicao-aparados-4x4":
        //include "principalInicio.php";
        principalInicio("Expedição Aparados 4x4 - Guia Aparados da Serra");
        $cTpl = new cTemplate("tpl.expedicao.htm");

        $cAutomates = new cAutomates("expedicao");
        $expedicao = $cAutomates->mRetornaDados();

        $cTpl->mSet("ds_expedicao", nl2br($expedicao[0]['ds_expedicao']));
        $cTpl->mShow("inicio");

        $cAutomates = new cAutomates("expedicao_fotos");
        $fotos = $cAutomates->mRetornaDados();

        for ($i = 0; $i < count($fotos); $i++) {

            $cTpl->mSet("tt_foto", $fotos[$i]["tt_foto"]);
            $cTpl->mSet("foto", $cGd->mGeraGd($caminhoFotos, $fotos[$i]["nm_foto"], 205, 1));
            $cTpl->mSet("link", $cGd->mGeraGd($caminhoFotos, $fotos[$i]["nm_foto"], 500, 3));
            $cTpl->mShow("while");
        }

        $cTpl->mShow("fim");

        include "principalFim.php";
    break;

    /**************************
      APARADOS DA SERRA
     **************************/
    case "o-que-e-aparados-da-serra":
        //include "principalInicio.php";
        principalInicio("O que é Aparados da Serra? - Guia Aparados da Serra");

        $cTpl = new cTemplate("tpl.aparados.htm");

        $cAutomates = new cAutomates("aparados");
        $aparados = $cAutomates->mRetornaDados();

        $cTpl->mSet("ds_aparados", nl2br($aparados[0]['ds_aparados']));
        $cTpl->mShow("inicio");

        $cAutomates = new cAutomates("aparados_fotos");
        $fotos = $cAutomates->mRetornaDados();

        for ($i = 0; $i < count($fotos); $i++) {

            $cTpl->mSet("tt_foto", $fotos[$i]["tt_foto"]);
            $cTpl->mSet("foto", $cGd->mGeraGd($caminhoFotos, $fotos[$i]["nm_foto"], 205, 1));
            $cTpl->mSet("link", $cGd->mGeraGd($caminhoFotos, $fotos[$i]["nm_foto"], 500, 3));
            $cTpl->mShow("while");
        }

        $cTpl->mShow("fim");

        include "principalFim.php";
    break;

    /**************************
      EXCURSÃO PARA GRUPOS
     **************************/
    case "excursao-para-grupos-colegios-universidades":
        //include "principalInicio.php";
        principalInicio("Excursão para grupos / Colégios / Universidade - Guia Aparados da Serra");
        $cTpl = new cTemplate("tpl.excursao.htm");

        $cAutomates = new cAutomates("excursao");
        $excursao = $cAutomates->mRetornaDados();

        $cTpl->mSet("ds_excursao", nl2br($excursao[0]['ds_excursao']));
        $cTpl->mShow("inicio");

        $cAutomates = new cAutomates("excursao_fotos");
        $fotos = $cAutomates->mRetornaDados();

        for ($i = 0; $i < count($fotos); $i++) {

            $cTpl->mSet("tt_foto", $fotos[$i]["tt_foto"]);
            $cTpl->mSet("foto", $cGd->mGeraGd($caminhoFotos, $fotos[$i]["nm_foto"], 205, 1));
            $cTpl->mSet("link", $cGd->mGeraGd($caminhoFotos, $fotos[$i]["nm_foto"], 500, 3));
            $cTpl->mShow("while");
        }

        $cTpl->mShow("fim");

        include "principalFim.php";
    break;

    /**************************
      CAVALGADAS
     **************************/
    case "as-cavalgadas-nos-aparados":
        //include "principalInicio.php";
        principalInicio("As Cavalgadas nos Aparados - Guia Aparados da Serra");    

        $cTpl = new cTemplate("tpl.cavalgadas.htm");

        $cAutomates = new cAutomates("cavalgadas");
        $cavalgadas = $cAutomates->mRetornaDados();

        $cTpl->mSet("ds_cavalgada", nl2br($cavalgadas[0]['ds_cavalgada']));
        $cTpl->mShow("inicio");

        $cAutomates = new cAutomates("cavalgadas_fotos");
        $fotos = $cAutomates->mRetornaDados();

        for ($i = 0; $i < count($fotos); $i++) {

            $cTpl->mSet("tt_foto", $fotos[$i]["tt_foto"]);
            $cTpl->mSet("foto", $cGd->mGeraGd($caminhoFotos, $fotos[$i]["nm_foto"], 205, 1));
            $cTpl->mSet("link", $cGd->mGeraGd($caminhoFotos, $fotos[$i]["nm_foto"], 500, 3));
            $cTpl->mShow("while");
        }

        $cTpl->mShow("fim");

        include "principalFim.php";
    break;

    /**************************
      TREKKINGS
     **************************/
    case "os-trekkings-nos-aparados":
        //include "principalInicio.php";
        principalInicio("Os Trekking nos Aparados - Guia Aparados da Serra");

        $cTpl = new cTemplate("tpl.trekkings.htm");

        $cAutomates = new cAutomates("trekkings");
        $trekkings = $cAutomates->mRetornaDados();

        $cTpl->mSet("ds_trekkings", nl2br($trekkings[0]['ds_trekkings']));
        $cTpl->mShow("inicio");

        $cAutomates = new cAutomates("trekkings_fotos");
        $fotos = $cAutomates->mRetornaDados();

        for ($i = 0; $i < count($fotos); $i++) {

            $cTpl->mSet("tt_foto", $fotos[$i]["tt_foto"]);
            $cTpl->mSet("foto", $cGd->mGeraGd($caminhoFotos, $fotos[$i]["nm_foto"], 205, 1));
            $cTpl->mSet("link", $cGd->mGeraGd($caminhoFotos, $fotos[$i]["nm_foto"], 500, 3));
            $cTpl->mShow("while");
        }

        $cTpl->mShow("fim");

        include "principalFim.php";
    break;
	
	/**************************
      MOUNTAIN BIKE
     **************************/
    case "mountain-bike-nos-aparados":
        //include "principalInicio.php";
        principalInicio("Mountain Bike nos Aparados - Guia Aparados da Serra");

        $cTpl = new cTemplate("tpl.mountainbike.htm");

        $cAutomates = new cAutomates("mountainbike");
        $trekkings = $cAutomates->mRetornaDados();

        $cTpl->mSet("ds_mountainbike", nl2br($trekkings[0]['ds_mountainbike']));
        $cTpl->mShow("inicio");

        $cAutomates = new cAutomates("mountainbike_fotos");
        $fotos = $cAutomates->mRetornaDados();

        for ($i = 0; $i < count($fotos); $i++) {

            $cTpl->mSet("tt_foto", $fotos[$i]["tt_foto"]);
            $cTpl->mSet("foto", $cGd->mGeraGd($caminhoFotos, $fotos[$i]["nm_foto"], 205, 1));
            $cTpl->mSet("link", $cGd->mGeraGd($caminhoFotos, $fotos[$i]["nm_foto"], 500, 3));
            $cTpl->mShow("while");
        }

        $cTpl->mShow("fim");

        include "principalFim.php";
    break;

    /**************************
      ATRATIVOS
     **************************/
    case "atrativos":
        //include "principalInicio.php";

        principalInicio("Atrativos - Guia Aparados da Serra");

        $cTpl = new cTemplate("tpl.atrativos.htm");

        $cAutomates = new cAutomates("atrativos");
        $atrativos = $cAutomates->mRetornaDados();

        $cTpl->mSet("ds_atrativo", nl2br($atrativos[0]['ds_atrativo']));
        $cTpl->mShow("inicio");

        $cAutomates = new cAutomates("atrativos_fotos");
        $fotos = $cAutomates->mRetornaDados();

        for ($i = 0; $i < count($fotos); $i++) {

            $cTpl->mSet("tt_foto", $fotos[$i]["tt_foto"]);
            $cTpl->mSet("foto", $cGd->mGeraGd($caminhoFotos, $fotos[$i]["nm_foto"], 183, 1));
            $cTpl->mSet("link", $cGd->mGeraGd($caminhoFotos, $fotos[$i]["nm_foto"], 500, 3));
            $cTpl->mShow("while");
        }

        $cTpl->mShow("fim");

        include "principalFim.php";
    break;

    /**************************
      QUEM SOMOS
     **************************/
    case "quem-somos":
        //include "principalInicio.php";

        principalInicio("Quem Somos - Guia Aparados da Serra");

        $cTpl = new cTemplate("tpl.quemSomos.htm");

        $cAutomates = new cAutomates("quem_somos");
        $quemSomos = $cAutomates->mRetornaDados();

        $cTpl->mSet("ds_quem_somos", nl2br($quemSomos[0]['ds_quem_somos']));
        $cTpl->mShow("inicio");

        $cAutomates = new cAutomates("quem_somos_fotos");
        $fotos = $cAutomates->mRetornaDados();

        for ($i = 0; $i < count($fotos); $i++) {

            $cTpl->mSet("tt_foto", $fotos[$i]["tt_foto"]);
            $cTpl->mSet("foto", $cGd->mGeraGd($caminhoFotos, $fotos[$i]["nm_foto"], 205, 1));
            $cTpl->mSet("link", $cGd->mGeraGd($caminhoFotos, $fotos[$i]["nm_foto"], 500, 3));
            $cTpl->mShow("while");
        }

        $cTpl->mShow("fim");

        include "principalFim.php";
    break;

    /**************************
      DESTAQUES XML
     **************************/
    case "destaques-xml":
        
        $cAutomates = new cAutomates("destaques");
        $destaques = $cAutomates->mRetornaDados();

        for ($i = 0; $i < count($destaques); $i++) {
            $dimensoes = getimagesize($caminhoFotos . $destaques[$i]["arq_destaque"]);
            print("
				<image>
				   <filename>" . $destaques[$i]["arq_destaque"] . "</filename>
				   <caption></caption>
				   <width>" . $dimensoes[0] . "</width>
				   <height>" . $dimensoes[1] . "</height>
				</image>
			");
        }

        print("
		</gallery>
		");
    break;

    /**************************
      PROMOCAO
     **************************/
    case "promocao":

        $cTpl = new cTemplate("tpl.promocao.htm");
        $cTpl->mShow("");
    break;

    /**************************
      PÁGINA INICIAL
     **************************/
    default:
        //include "principalInicio.php";

        principalInicio("", true);

        $cTpl = new cTemplate("tpl.principal.htm");
        $cTpl->mShow("inicio");

        $cAutomates = new cAutomates("pacotes");
        $comData = $cAutomates->mRetornaDados("", "tp_pacote='Pacotes com Datas'");

        for ($i = 0; $i < count($comData); $i++) {

            $cTpl->mSet("link", $comData[$i]["lk_pacote"]);
            $cTpl->mSet("nm_pacote", $comData[$i]["nm_pacote"]);
            $cTpl->mShow("comData");
        }

        $cTpl->mShow("comData_fim");

        $cAutomates = new cAutomates("pacotes");
        $familia = $cAutomates->mRetornaDados("", "tp_pacote='Pacotes Familia'");

        for ($i = 0; $i < count($familia); $i++) {

            $cTpl->mSet("link", $familia[$i]["lk_pacote"]);
            $cTpl->mSet("nm_pacote", $familia[$i]["nm_pacote"]);
            $cTpl->mShow("familia");
        }
        $cTpl->mShow("familia_fim");

        $cAutomates = new cAutomates("pacotes");
        $umDia = $cAutomates->mRetornaDados("", "tp_pacote='Passeios de um dia'");

        for ($i = 0; $i < count($umDia); $i++) {

            $cTpl->mSet("link", $umDia[$i]["lk_pacote"]);
            $cTpl->mSet("nm_pacote", $umDia[$i]["nm_pacote"]);
            $cTpl->mShow("umDia");
        }
        $cTpl->mShow("umDia_fim");

        $cAutomates = new cAutomates("informe");
        $informe = $cAutomates->mRetornaDados("");

        for ($i = 0; $i < count($informe); $i++) {

            $cTpl->mSet("link", "informe-se/" . $informe[$i]["cd_informe"] . "/" . $cUrl->mTrataString($informe[$i]["tt_informe"]));
            $cTpl->mSet("titulo", $informe[$i]["tt_informe"]);
            $cTpl->mShow("informe-se");
        }
        $cTpl->mShow("fim_informe-se");

        $cAutomates = new cAutomates("noticias");
        $noticia = $cAutomates->mRetornaDados("");

        for ($i = 0; $i < count($noticia); $i++) {

            $cTpl->mSet("link", "noticias/" . $noticia[$i]["cd_noticia"] . "/" . $cUrl->mTrataString($noticia[$i]["tt_noticia"]));
            $cTpl->mSet("titulo", $noticia[$i]["tt_noticia"]);
            $cTpl->mSet("numNoticia", ($i + 1));
            $cTpl->mShow("noticias");
        }
        $cTpl->mShow("fim_noticias");
        
        include "principalFim.php";
        
    break;
}
?>