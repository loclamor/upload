<?php

//on fait la connexion à mysql
mysql_connect(MYSQL_SERVER,  MYSQL_USER, MYSQL_PWD);
mysql_select_db(MYSQL_DB);

$requete = "CREATE TABLE IF NOT EXISTS `".TABLE_PREFIX."utilisateur`(
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`uniqid` varchar(15) NOT NULL,
	`pseudo` varchar(255) NOT NULL,
	`nom` varchar(255) NOT NULL,
	`prenom` varchar(255) NOT NULL,
	`mot_de_passe` varchar(255) NOT NULL,
	`mail` varchar(255) NOT NULL,
	`date_inscription` datetime NOT NULL,
	`date_anniversaire` date NOT NULL,
	`signature` varchar(255) DEFAULT NULL,
	PRIMARY KEY (`id`)
)";
mysql_query($requete);

$requete = "CREATE TABLE IF NOT EXISTS `".TABLE_PREFIX."album`(
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`uniqid` varchar(15) NOT NULL,
	`id_utilisateur` int(11) NOT NULL,
	`nom` varchar(255) NOT NULL,
	`date_creation` datetime NOT NULL,
	`date_mise_a_jour` datetime NOT NULL,
	`privacy` varchar(255) DEFAULT NULL,
	`is_temp` TINYINT NOT NULL DEFAULT '1',
	PRIMARY KEY (`id`)
)";
mysql_query($requete);

$requete = "CREATE TABLE IF NOT EXISTS `".TABLE_PREFIX."photo`(
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`uniqid` varchar(15) NOT NULL,
	`id_album` int(11) NOT NULL,
	`legend` varchar(255) NOT NULL,
	`url` varchar(255) NOT NULL,
	`date_upload` datetime NOT NULL,
	`privacy` varchar(255) DEFAULT NULL,
	PRIMARY KEY (`id`)
)";
mysql_query($requete);

//on se déconnecte
mysql_close();