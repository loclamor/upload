<?php

class Page_Upload_DoConnexion extends Page {
	
	public function controller($mixed) {
		$pseudo = $mixed['pseudo'];
		$pwd = $mixed['pwd'];
		
		$urlAction = new Url(true);
		$urlAction->addParam('page', 'accueil');
		
		$log = new Logger('./logs');
		
		$users = Gestionnaire::getGestionnaire('Utilisateur')->getOf(array('pseudo' => $pseudo, 'mot_de_passe' => $pwd));
		
		if($users){
			$_SESSION['upload']['isConnect'] = 'true';
			$_SESSION['upload']['id'] = $users[0]->getId();
			$log->log('success', 'success_connection', 'Success de connection pour '.$pseudo, Logger::GRAN_MONTH);
			$urlAction->addParam('notify', 'Bienvenue '.$pseudo);
		}
		else {
			unset($_SESSION['upload']['isConnect']);
			unset($_SESSION['upload']['id']);
			$log->log('erreurs', 'erreurs_connection', 'Echec de connection pour '.$pseudo.' '.$pwd, Logger::GRAN_MONTH);
			$urlAction->addParam('notify', 'Pseudo ou mot de passe incorrect.');
		}
		
		
		
		
		redirect($urlAction->getUrl());
		echo 'Vous avez été connecté.<br/><a href="'.$urlAction->getUrl().'">retour accueil</a>';
	}
}
