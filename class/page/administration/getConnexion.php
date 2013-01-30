<?php

class Page_Administration_GetConnexion extends Page {
	
	public function __construct() {
		
		$urlAction = new Url();
		//c'est mieux que l'utilisateur connaisse pas la page de traitement de connexion
		$urlAction->addParam('page', 'accueil');
		$urlAction->addParam('noDisplay', 'true'); 
		$this->addElement('content', '<h2>Connexion</h2>');
		$form =
			'<form action='. $urlAction->getUrl().' method="POST" >'
			. '	<label for="pseudo">Pseudo :</label><input type="text" name="pseudo" id="pseudo" size="75" /><br/>'
			. '	<label for="pwd">Mot de passe :</label><input type="password" name="pwd" id="pwd" size="75" /><br/>'
			. '	<input type="submit" value="Valider"/>'
			. '</form>';
		$this->addElement('content', $form);
	}
}