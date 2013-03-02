<?php
class Page_Upload_AlbumContent extends Page {
	
	public function controller($albumId){
			$album = Gestionnaire::getGestionnaire('album')->getOne($albumId);
			if($album instanceof Bdmap_Album){
				$this->nom = $album->getNom();
				
				$this->uniqidAlbum = $album->getUniqid();
				
				$this->photos = Gestionnaire::getGestionnaire('photo')->getOf(array('id_album' => $album->getId()));
			}
			else {
				$this->nom = "album inconnu";
				$this->uniqidAlbum = "0";
				$this->photos = false;
			}
			$this->addTitle($this->nom);
	}
}