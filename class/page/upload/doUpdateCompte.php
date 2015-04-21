<?php

class Page_Upload_DoUpdateCompte extends Page {
	
	public function controller($mixed) {
		//$pseudo = $mixed['pseudo'];
		$password = $mixed['password'];
		$repassword = $mixed['repassword'];
        
        $nom = $mixed['nom'];
        $prenom = $mixed['prenom'];
        $email = $mixed['email'];
		
		$urlAction = new Url(true);
		$urlAction->addParam('page', 'compte');
		
		$log = new Logger('./logs');
		
		$user = Gestionnaire::getGestionnaire('utilisateur')->getOne($_SESSION['upload']['id']);
		
        if( $user instanceof Bdmap_Utilisateur ) {
            if( !empty($password) && !empty($repassword) ) {
                if( $password == $repassword ) {
                    $user->setMotDePasse( $password );
                }
                else {
                    $urlAction->addParam('notify', 'Erreur lors de la ressaisie du nouveau mot de passe');
                }
            }
            
            $user->setNom( $nom );
            $user->setPrenom( $prenom );
            $user->setMail( $email );
            
            $user->enregistrer();
            
        }
		
		redirect($urlAction->getUrl());
		echo 'Donnees mises a jour.<br/><a href="'.$urlAction->getUrl().'">retour au compte</a>';
	}
}
