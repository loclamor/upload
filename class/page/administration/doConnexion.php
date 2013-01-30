<?php

class Page_Administration_DoConnexion extends Page {
	public function __construct($pseudo, $pwd) {
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
				$_SESSION['administration']['isConnect'] = 'true';
				$_SESSION['administration']['id'] = $userId;
			}
			else {
				unset($_SESSION['administration']['isConnect']);
				unset($_SESSION['administration']['id']);
			}
		}
		else {
			unset($_SESSION['administration']['isConnect']);
			unset($_SESSION['administration']['id']);
		}
		$urlAction = new Url(true);
		$urlAction->addParam('page', 'accueil');
		redirect($urlAction->getUrl());
		echo 'Vous avez été connecté.<br/><a href="'.$urlAction->getUrl().'">retour accueil</a>';
	}
}