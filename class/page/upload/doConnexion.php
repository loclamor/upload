<?php

class Page_Upload_DoConnexion extends Page {
	
	public function controller($mixed) {
		$pseudo = $mixed['pseudo'];
		$pwd = $mixed['pwd'];
		//TODO code de connexion à revoir : le parcours de tous les utilisateurs n'est pas viable si le site grossi
		
		$log = new Logger('./logs');
		
		$users = Gestionnaire::getGestionnaire('Utilisateur')->getOf(array('pseudo' => $pseudo, 'mot_de_passe' => $pwd));
		
		if($users){
			$_SESSION['upload']['isConnect'] = 'true';
			$_SESSION['upload']['id'] = $users[0]->getId();
			$log->log('success', 'success_connection', 'Success de connection pour '.$pseudo, Logger::GRAN_MONTH);
		}
		else {
			unset($_SESSION['upload']['isConnect']);
			unset($_SESSION['upload']['id']);
			$log->log('erreurs', 'erreurs_connection', 'Echec de connection pour '.$pseudo.' '.$pwd, Logger::GRAN_MONTH);
		}
		
		/*
		$userExist = false;
		$userId = false;
		if(!is_null($pseudo) and !is_null($pwd)){
			$users = Gestionnaire::getGestionnaire('Utilisateur')->getAll();
			foreach ($users as $user) {
				if($user instanceof Utilisateur) {
					if($user->getPseudo() == $pseudo and $user->getMotDePasse() == $pwd) {
						$userExist = true;
						$userId = $user->getId();
					}
				}
			}
			if($userExist) {
				$_SESSION['upload']['isConnect'] = 'true';
				$_SESSION['upload']['id'] = $userId;
			}
			else {
				
			}
		}
		else {
			unset($_SESSION['upload']['isConnect']);
			unset($_SESSION['upload']['id']);
		}
		
		*/
		
		$urlAction = new Url(true);
		$urlAction->addParam('page', 'accueil');
		redirect($urlAction->getUrl());
		echo 'Vous avez été connecté.<br/><a href="'.$urlAction->getUrl().'">retour accueil</a>';
	}
}