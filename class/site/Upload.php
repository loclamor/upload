<?php

class Site_Upload extends Site {
	public function __construct($admin = false) {
		$temps = microtime();
		$temps = explode(' ', $temps);
		$this->microtimeStart = $temps[1] + $temps[0];
		
		$this->log = new Logger('./logs');
				
		$this->addElement('title', SITE_NAME);
		
		//on crée les éléments généraux du footer
		$this->addFoot('<span id="foot_version">MondoPhoto Upload Service v'.VERSION.'</span>');
		$this->addFoot('<span class="separator"> - </span>');
		$this->addFoot('<span id="foot_copyright">&copy; 2012 loclamor</span>');
		
		//on vérifie que on a bien une page de demandée
		if(isset($_GET['page']) and !empty($_GET['page'])){
			$this->page = $_GET['page'];
		}
		else {
			$this->page = 'accueil';
		}
		
		$url = new Url();
		$url->addParam('page', 'accueil');
		
		$this->addMenu('<li id="onglet_1"><a class="brand" href="'.$url->getUrl().'">MondoPhoto Upload Service</a></li>','onglets', "ul", array("class"=>"nav"));
		

		$this->user_connected = false;
		if(!isset($_SESSION['upload']['isConnect'])){
			$_SESSION['upload']['isConnect'] = null;
		}
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
			
			$this->addMenu('<li id="onglet_divider_1" class="divider-vertical" ></li>', 'onglets');
			$this->addMenu('<li id="onglet_profil" ><a href="#" >Connect&eacute; en tant que '.$this->user->getPseudo().'</a></li>', 'onglets');
			
			$url->addParam('page', 'deconnexion');
			$this->addMenu('<li id="onglet_deconnection" ><a class="" href="'.$url->getUrl().'"><i class="icon-off"></i> Se deconnecter</a></li>', 'onglets');
			
			//quand on est connecté il faut aussi construire l'interface d'onglets
			//donc 1 page de contenu = 1 onglet

			$this->addContent('<ul class="nav nav-tabs" id="main-tab-nav"></ul>','main-tab','div',array('class'=>'tabbable tabs-left'));
				$urlTab = new Url(true);
				$urlTab->addParam('page', 'albums');
				$class = ($this->page=='albums'||$this->page=='accueil'||empty($this->page)?'active':'');
				$this->addContent('<li class="'.$class.'" id="main-tab-nav-li_album"><a href="'.$urlTab->getUrl().'" >Mes albums</a></li>','main-tab-nav');
				$urlTab->addParam('page', 'create');
				$class = ($this->page=='create'?'active':'');
				$this->addContent('<li class="'.$class.'" id="main-tab-nav-li_new"><a href="'.$urlTab->getUrl().'" >Cr&eacute;er un album</a></li>','main-tab-nav');
				$urlTab->addParam('page', 'compte');
				$class = ($this->page=='compte'?'active':'');
				$this->addContent('<li class="'.$class.'" id="main-tab-nav-li_compte"><a href="'.$urlTab->getUrl().'" >Mon compte</a></li>','main-tab-nav');
			$this->addContent('<div class="tab-content" id="main-tab-content" ></div>','main-tab');
				
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
	
		$nbAdmQuery = SQL::getInstance()->getNbAdmQuery();
		$nbQuery = SQL::getInstance()->getNbQuery();
		
		$this->addFoot('<span class="separator"> - </span>');
		$this->addFoot('<span id="foot_queries">'.$nbAdmQuery.'/'.$nbQuery.' requ&ecirc;tes</span>');
	
		$temps = microtime();
		$temps = explode(' ', $temps);
		$this->microtimeEnd = $temps[1] + $temps[0];
		
		
		$this->addFoot('<span class="separator"> - </span>');
		$this->addFoot('<span id="foot_time">page g&eacute;n&eacute;r&eacute;e en '.round(($this->microtimeEnd - $this->microtimeStart),3).' secondes</span>');
		
	}
	
	public function getConnexion() {
		
		$urlConect = new Url(true);
		$urlConect->addParam('page', 'connexion');
		$this->addElement('filariane', '<a href="'.$urlConect->getUrl().'">connexion</a>');
		
		$this->addPage(new Page_Upload_GetConnexion());
		
		$this->log->log('infos', 'infos_general', 'page connection affichee', Logger::GRAN_MONTH);
		
	}
	
	public function doConnexion($pseudo, $pwd) {
		$this->addPage(new Page_Upload_DoConnexion(array('pseudo'=>$pseudo, 'pwd'=>$pwd)));
		
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
		
		$this->addPage(new Page_Upload_Albums(),'main-tab-content');
		
		
		
		$this->log->log('infos', 'infos_general', 'page albums affichee', Logger::GRAN_MONTH);
		
	}
	
	public function createAlbum(){
		
		$this->addPage(new Page_Upload_CreateAlbum(),'main-tab-content');
		
		$this->log->log('infos', 'infos_general', 'page createAlbum affichee', Logger::GRAN_MONTH);
	}
	
}