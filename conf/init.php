<?php
session_start();

//nom du site
define('SITE_NAME', 'MondoPhoto Upload Service');

//version du site (utile en particulier pour automatiser la mise à jour de la BDD
define('VERSION','0.0');

//config pour savoir si on est en local ou pas
if($_SERVER['SERVER_ADDR'] == '127.0.0.1')  {
	define('APPLICATION_ENV', 'dev');
}
else {
	define('APPLICATION_ENV','prod');
}

define('SITE','site');

//préfixe des tables de la base de donnée
define('TABLE_PREFIX','up_');

//definition des variables de conf
if(APPLICATION_ENV == 'dev') {//on est en local
//mysql
	define('MYSQL_SERVER','localhost');
	define('MYSQL_USER','root');
	define('MYSQL_PWD','mysql');
	define('MYSQL_DB','voyage');
	
	if(SITE == 'site') {
		define('URL_REWRITING','false');
	}
	else {
		define('URL_REWRITING','false');
	}
	
}
else {//on est en prod
	//mysql
	//this file have to define MYSQL_SERVER, MYSQL_USER, MYSQL_PWD, MYSQL_DB
	require_once 'conf/dbpass.php';
	
	define('NEW_REWRITE_MODE','on'); //"on" == activé, autre == désactivé
	
	if(SITE == 'site') {
		define('URL_REWRITING','false');
		
	}
	else {
		define('URL_REWRITING','false');
	}

}

require_once 'functions.php';

//script de mise à jour de la BDD en fonction de la version
if(!file_exists(VERSION.'.version')){
	require_once 'bdinstal.php';
	if ($fp = fopen(VERSION.'.version',"w")){
		fclose($fp);
	}
	else {
		die("erreur de changement de version vers ".VERSION);
	}
}
