<?php
session_start();

//nom du site
define('SITE_NAME', 'MondoPhoto Upload Service');

//version du site (utile en particulier pour automatiser la mise � jour de la BDD
define('VERSION','0.1');

//config pour savoir si on est en local ou pas
if($_SERVER['SERVER_ADDR'] == '127.0.0.1')  {
	define('APPLICATION_ENV', 'dev');
}
else {
	define('APPLICATION_ENV','prod');
}

define('SITE','site');

//pr�fixe des tables de la base de donn�e
define('TABLE_PREFIX','up_');

//definition des variables de conf
if(APPLICATION_ENV == 'dev') {//on est en local
//mysql
	define('MYSQL_SERVER','localhost');
	define('MYSQL_USER','root');
	define('MYSQL_PWD','mysqlroot');
	define('MYSQL_DB','voyage');
	
	if(SITE == 'site') {
		define('URL_REWRITING','false');
	}
	else {
		define('URL_REWRITING','false');
	}
	
	define('HOST_OF_SITE', 'http://127.0.0.1/workspace-php/upload');
}
else {//on est en prod
	//mysql
	//this file have to define MYSQL_SERVER, MYSQL_USER, MYSQL_PWD, MYSQL_DB
	require_once 'conf/dbpass.php';
	
	define('NEW_REWRITE_MODE','on'); //"on" == activ�, autre == d�sactiv�
	
	if(SITE == 'site') {
		define('URL_REWRITING','false');
		
	}
	else {
		define('URL_REWRITING','false');
	}
	
	define('HOST_OF_SITE', 'http://'.$_SERVER['SERVER_NAME']);
}

require_once 'functions.php';

//script de mise � jour de la BDD en fonction de la version
if(!file_exists(VERSION.'.version')){
	require_once 'bdinstal.php';
	if ($fp = fopen(VERSION.'.version',"w")){
		fclose($fp);
	}
	else {
		die("erreur de changement de version vers ".VERSION);
	}
}
