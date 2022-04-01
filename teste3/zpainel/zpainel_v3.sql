# HeidiSQL Dump 
#
# --------------------------------------------------------
# Host:                 app
# Database:             zpainel_v3
# Server version:       4.1.22-community
# Server OS:            Win32
# Target-Compatibility: Standard ANSI SQL
# HeidiSQL version:     3.2 Revision: 1129
# --------------------------------------------------------

/*!40100 SET CHARACTER SET latin1;*/
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ANSI';*/
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;*/


#
# Table structure for table 'zpainel_config'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ "zpainel_config" (
  "cd_zpainel_config" int(10) unsigned NOT NULL auto_increment,
  "titulo_site_config" varchar(255) NOT NULL default '' COMMENT 't:Title;l:1',
  "description_site_config" text COMMENT 't:Description',
  "keywords_site_config" varchar(255) default NULL COMMENT 't:Keywords',
  "analytics_site_config" text COMMENT 't:Google Analytics',
  "webmasters_site_config" varchar(255) default NULL COMMENT 't:Google Webmasters',
  "nm_zpainel_config" varchar(255) NOT NULL default '' COMMENT 't:Title Zpainel',
  PRIMARY KEY  ("cd_zpainel_config")
) AUTO_INCREMENT=2 /*!40100 DEFAULT CHARSET=latin1 COMMENT='t:Projeto;m:1;u:1'*/;



#
# Dumping data for table 'zpainel_config'
#

LOCK TABLES "zpainel_config" WRITE;
/*!40000 ALTER TABLE "zpainel_config" DISABLE KEYS;*/
REPLACE INTO "zpainel_config" ("cd_zpainel_config", "titulo_site_config", "description_site_config", "keywords_site_config", "analytics_site_config", "webmasters_site_config", "nm_zpainel_config") VALUES
	('1','Título do projeto','','','','','Título do Zpainel');
/*!40000 ALTER TABLE "zpainel_config" ENABLE KEYS;*/
UNLOCK TABLES;


#
# Table structure for table 'zpainel_links'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ "zpainel_links" (
  "cd_link_zpainel" int(10) unsigned NOT NULL auto_increment,
  "nm_link_zpainel" varchar(255) NOT NULL default '',
  "link_link_zpainel" varchar(255) NOT NULL default '',
  PRIMARY KEY  ("cd_link_zpainel")
) AUTO_INCREMENT=2 /*!40100 DEFAULT CHARSET=latin1*/;



#
# Dumping data for table 'zpainel_links'
#

LOCK TABLES "zpainel_links" WRITE;
/*!40000 ALTER TABLE "zpainel_links" DISABLE KEYS;*/
REPLACE INTO "zpainel_links" ("cd_link_zpainel", "nm_link_zpainel", "link_link_zpainel") VALUES
	('1','Usuários','usuarios/?');
/*!40000 ALTER TABLE "zpainel_links" ENABLE KEYS;*/
UNLOCK TABLES;


#
# Table structure for table 'zpainel_modulos'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ "zpainel_modulos" (
  "cd_modulo_zpainel" int(10) unsigned NOT NULL auto_increment,
  "nm_modulo_zpainel" varchar(255) NOT NULL default '' COMMENT 't:Nome;l:1',
  "link_modulo_zpainel" varchar(255) default NULL COMMENT 't:Link;l:1',
  PRIMARY KEY  ("cd_modulo_zpainel")
) /*!40100 DEFAULT CHARSET=latin1 COMMENT='t:Módulos;i:1;m:1;u:1;d:1;o:nm_modulo_zpainel,a'*/;



#
# Dumping data for table 'zpainel_modulos'
#

# (No data found.)



#
# Table structure for table 'zpainel_modulos_itens'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ "zpainel_modulos_itens" (
  "cd_item_modulo_zpainel" int(10) unsigned NOT NULL auto_increment,
  "cd_modulo_zpainel" int(11) NOT NULL default '0' COMMENT 'o:1',
  "nm_item_modulo_zpainel" varchar(255) NOT NULL default '' COMMENT 't:Nome;l:1',
  "link_item_modulo_zpainel" varchar(255) NOT NULL default '' COMMENT 't:Link;l:1',
  PRIMARY KEY  ("cd_item_modulo_zpainel")
) /*!40100 DEFAULT CHARSET=latin1 COMMENT='t:Módulos » Itens;i:1;m:1;u:1;d:1'*/;



#
# Dumping data for table 'zpainel_modulos_itens'
#

# (No data found.)



#
# Table structure for table 'zpainel_usuarios'
#

CREATE TABLE /*!32312 IF NOT EXISTS*/ "zpainel_usuarios" (
  "cd_usuario_zpainel" int(10) unsigned NOT NULL auto_increment,
  "nm_usuario_zpainel" varchar(255) NOT NULL default '' COMMENT 't:Nome;l:1',
  "em_usuario_zpainel" varchar(255) NOT NULL default '' COMMENT 't:E-mail;v:email',
  "login_usuario_zpainel" varchar(255) NOT NULL default '' COMMENT 't:Usuário;l:1;s:25',
  "pass_usuario_zpainel" varchar(255) NOT NULL default '' COMMENT 't:Senha;s:25;c:password',
  "cd_link_zpainel" varchar(255) default NULL COMMENT 't:Links;c:checkbox,zpainel_links,nm_link_zpainel',
  "cd_modulo_zpainel" varchar(255) default NULL COMMENT 't:Módulos;c:checkbox,zpainel_modulos,nm_modulo_zpainel',
  "situacao_usuario_zpainel" enum('Ativo','Inativo') NOT NULL default 'Ativo' COMMENT 't:Situação;l:1;a:center',
  PRIMARY KEY  ("cd_usuario_zpainel")
) /*!40100 DEFAULT CHARSET=latin1 COMMENT='t:Usuários;i:1;m:1;u:1;d:1;o:nm_usuario_zpainel,a'*/;



#
# Dumping data for table 'zpainel_usuarios'
#

# (No data found.)

/*!40101 SET SQL_MODE=@OLD_SQL_MODE;*/
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;*/
