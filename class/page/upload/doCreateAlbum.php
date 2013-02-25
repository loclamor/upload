<?php
class Page_Upload_DoCreateAlbum extends Page {
	
	public function controller($mixed){
		
		$album = Gestionnaire::getGestionnaire('album')->getOne($_GET['idAlbum']);
		if($album instanceof Bdmap_Album) {
			
			if(isset($_POST['title']) and !empty($_POST['title'])){
				$album->setNom($_POST['title']);
				$album->setTemp(false);
				$album->enregistrer(array('nom','is_temp'));
			}
			
			if(is_array($_POST['up_legende'])){
				foreach($_POST['up_legende'] as $idPhoto => $legende){
					$photo = Gestionnaire::getGestionnaire('photo')->getOne($idPhoto);
					
					if($photo instanceof Bdmap_Photo){
						if($photo->getIdAlbum() == $album->getId()){
							$photo->setLegende($legende);
							$photo->enregistrer(array('legend'));
						}
					}
				}
			}
		}
		
		
		$urlAction = new Url(true);
		$urlAction->addParam('page', 'accueil');
		$urlAction->addParam('notify', 'Album cree');
		redirect($urlAction->getUrl());
		echo 'Vos photos ont &eacute;t&eacute; envoy&eacute;s.<br/><a href="'.$urlAction->getUrl().'">retour accueil</a>';
	}
}