<?php
class Page_Upload_Albums extends Page {
	
	public function controller($mixed){
		
		$this->addTitle('Mes Albums');

		$this->user = Gestionnaire::getGestionnaire('utilisateur')->getOne($_SESSION['upload']['id']);
		
		$this->albums = Gestionnaire::getGestionnaire('album')->getOf(array('id_utilisateur' => $this->user->getId(), 'is_temp' => 0));
		
		$this->bbcodeLastAlbum = false;
		
		if(isset($_GET['albumId'])){
			//TODO : encspsule URL pour les photos à true pour le moment
			$this->bbcodeLastAlbum = getBBCodeFromOneAlbum(Gestionnaire::getGestionnaire('album')->getOne($_GET['albumId']),true,true);
		}
		
	}
}