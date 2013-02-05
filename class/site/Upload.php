<?php

class Site_Upload extends Site {
	public function __construct($admin = false) {
		$temps = microtime();
		$temps = explode(' ', $temps);
		$this->microtimeStart = $temps[1] + $temps[0];
		
		$this->log = new Logger('./logs');
				
		$this->addElement('title', SITE_NAME);
		
		//on crée les éléments généraux du footer
		$this->addFoot('MondoPhoto Upload Service v'.VERSION.'', 'foot_version','span');
		$this->addFoot(' - &copy; 2012 loclamor', 'foot_copyright','span');
	//	$this->addElement('foot', 'MondoPhoto Upload Service v'.VERSION);
	//	$this->addElement('foot', '&copy; 2012 loclamor');
		
		//on vérifie que on a bien une page de demandée
		if(isset($_GET['page']) and !empty($_GET['page'])){
			$this->page = $_GET['page'];
		}
		else {
			$this->page = 'accueil';
		}
		
		$url = new Url();
		$url->addParam('page', 'accueil');
		
		//<ul id="onglets" class="nav">
		$this->addMenu('', "onglets", "ul", array("class"=>"nav"));
		$this->addMenu('<a class="brand" href="'.$url->getUrl().'">MondoPhoto Upload Service</a>', "onglet_1", "li", array(), 'onglets');
		

		$this->user_connected = false;
		//on vérifie la connexion
		if(!isset($_SESSION['upload']['isConnect']) and $_SESSION['upload']['isConnect'] != 'true'){
			if(isset($_POST['pwd']) and !empty($_POST['pwd']) and isset($_POST['pseudo']) and !empty($_POST['pseudo'])) {
				$this->page = 'traitementConnexion';
			}
			else {
				
				$this->page = 'connexion';
			}
		}
		else {
			//ici on est connecté
			
			$this->user = new Bdmap_Utilisateur($_SESSION['upload']['id']);
			//TODO vérifier si l'user existe bien
			
			// on défini une variable de connexion :
			$this->user_connected = true;
			
			$this->log->setBaseString('Upload : '.$this->user->getPseudo().' :');
			
			// on construit le menu
			$url = new Url();
			
			$this->addElement('menu', '<li class="divider-vertical"></li>');
			$this->addElement('menu', '<li><a href="#" >Connecté en tant que '.$this->user->getPseudo().'</a></li>');
			
			$url->addParam('page', 'deconnexion');
			$this->addElement('menu', '<li><a class="" href="'.$url->getUrl().'"><i class="icon-off"></i> Se deconnecter</a></li>');
			
			//quand on est connecté il faut aussi construire l'interface d'onglets
			//on ne met que l'ouverture des éléments, les fermetures seront mise après le contenu de la page
			//donc 1 page de contenu = 1 onglet
			
			
			$this->addElement('content','<div class="tabbable tabs-left">');
				$this->addElement('content','<ul class="nav nav-tabs">');
					$urlTab = new Url(true);
					$urlTab->addParam('page', 'albums');
					$this->addElement('content','<li class="'.($this->page=='albums'||$this->page=='accueil'||empty($this->page)?'active':'').'"><a href="'.$urlTab->getUrl().'" >Mes albums</a></li>');
					$urlTab->addParam('page', 'create');
					$this->addElement('content','<li class="'.($this->page=='create'?'active':'').'"><a href="'.$urlTab->getUrl().'" >Créer un album</a></li>');
					
				$this->addElement('content','</ul>');
				$this->addElement('content', '<div class="tab-content">');
				
		}
		
		$urlAccueil = new Url(true);
		$this->addElement('filariane', '<a href="'.$urlAccueil->getUrl().'">Accueil</a>');
		
		switch ($this->page) {
			case 'connexion':
				$this->getConnexion();
				break;
			case 'traitementConnexion':
				$this->doConnexion($_POST['pseudo'], $_POST['pwd']);
				break;
			case 'deconnexion':
				$this->endConnexion();
				break;
			case 'create' :
				$this->createAlbum();
				break;
			case 'albums' :
			case 'accueil' :
			default:
				//par défaut, on affiche l'accueil
				$this->getAlbums();
		}
		
		//si on est connecté, on affiche la fermeture des balises des onglets
		if($this->user_connected) {
			$this->addElement('content','</div>');
			$this->addElement('content','</div>');
		}
		
		$nbAdmQuery = SQL::getInstance()->getNbAdmQuery();
		$nbQuery = SQL::getInstance()->getNbQuery();
		
		$this->addFoot(' - '.$nbAdmQuery.'/'.$nbQuery.' requ&ecirc;tes', 'foot_queries','span');
		
		$temps = microtime();
		$temps = explode(' ', $temps);
		$this->microtimeEnd = $temps[1] + $temps[0];
		
		$this->addFoot(' - page g&eacute;n&eacute;r&eacute;e en '.round(($this->microtimeEnd - $this->microtimeStart),3).' secondes', 'foot_time','span');
		
	}
	
	public function getConnexion() {
		
		$urlConect = new Url(true);
		$urlConect->addParam('page', 'connexion');
		$this->addElement('filariane', '<a href="'.$urlConect->getUrl().'">connexion</a>');
		
		$this->addPage(new Page_Upload_GetConnexion());
		
		$this->log->log('infos', 'infos_general', 'page connection affichee', Logger::GRAN_MONTH);
		
	}
	
	public function doConnexion($pseudo, $pwd) {
		$this->addPage(new Page_Upload_DoConnexion($pseudo, $pwd));
		
		$this->log->log('infos', 'infos_connections', 'demande de connection pour '.$pseudo, Logger::GRAN_MONTH);
		
	}
	
	public function endConnexion() {
		unset($_SESSION['upload']['isConnect']);
		unset($_SESSION['upload']['id']);
		
		$this->log->log('success', 'success_deconnection', 'Deconnection pour '.$this->user->getPseudo(), Logger::GRAN_MONTH);
		
		$urlAction = new Url(true);
		$urlAction->addParam('page', 'accueil');
		redirect($urlAction->getUrl());
		echo 'Vous avez été déconnecté.<br/><a href="'.$urlAction->getUrl().'">retour accueil</a>';
	}
	
	
	public function getAlbums(){
		
		$this->addPage(new Page_Upload_Albums());
		
		
		
		$this->log->log('infos', 'infos_general', 'page albums affichee', Logger::GRAN_MONTH);
		
	}
	
	public function createAlbum(){
		
		$this->addPage(new Page_Upload_CreateAlbum());
		
		$this->log->log('infos', 'infos_general', 'page createAlbum affichee', Logger::GRAN_MONTH);
	}
	
}