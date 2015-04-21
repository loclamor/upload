<?php
class Page_Upload_Compte extends Page {
	
	public function controller($mixed){
		
		$this->addTitle('Mon Compte');

		$this->user = Gestionnaire::getGestionnaire('utilisateur')->getOne($_SESSION['upload']['id']);
		
		$this->urlAction = new Url(true);
		$this->urlAction->addParam('page', 'updateCompte');
		
	}
}