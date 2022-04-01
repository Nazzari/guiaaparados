<?
$cTplIndex = new cTemplate("tpl.index.htm");
$cAutomatesConfigSite = new cAutomates("zpainel_config");
$configSite = $cAutomatesConfigSite->mRetornaDados();
$cTplIndex->mSet("titulo_site_config",$configSite[0]["titulo_site_config"]);
$cTplIndex->mSet("description_site_config",$configSite[0]["description_site_config"]);
$cTplIndex->mSet("keywords_site_config",$configSite[0]["keywords_site_config"]);
$cTplIndex->mSet("analytics_site_config",$configSite[0]["analytics_site_config"]);
$cTplIndex->mSet("webmasters_site_config",$configSite[0]["webmasters_site_config"]);

$cTplIndex->mShow("inicio");

?>