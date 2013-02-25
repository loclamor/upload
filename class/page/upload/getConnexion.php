<?php

class Page_Upload_GetConnexion extends Page {
	
	public function controller($mixed) {
		
		$this->addTitle('Connexion');
		
		$this->urlAction = new Url();
		//c'est mieux que l'utilisateur connaisse pas la page de traitement de connexion
		$this->urlAction->addParam('page', 'accueil');
		$this->urlAction->addParam('noDisplay', 'true'); 
		
	}
}