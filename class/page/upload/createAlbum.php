<?php
class Page_Upload_CreateAlbum extends Page {
	
	public function controller($mixed){
		$date = date("Y-m-d H:i:s",time());
		if(!isset($_GET['idAlbum'])) {
			$this->addTitle('Nouvel Album');
			
			$album = new Bdmap_Album();
			$album->setIdUtilisateur($_SESSION['upload']['id']);
			$album->setUniqid(uniqid());
			
			$album->setDateCreation($date);
			$album->setDateMiseAJour($date);
			$album->enregistrer();
		}
		else {
			$album = Gestionnaire::getGestionnaire('Album')->getOne($_GET['idAlbum']);
                        $album->setDateMiseAJour($date);
                        $album->enregistrer( array( 'date_mise_a_jour' ) );
			//TODO : v�rifier que l'utilisateur est dans son album
		}
		
		$this->urlAction = new Url(true);
		$this->urlAction->addParam('page', 'createProcess');
		$this->urlAction->addParam('idAlbum', $album->getId());
		$this->album = $album;
		
	}
}