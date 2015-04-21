<?php

class Site_Upload extends Site {
	public function __construct($admin = false) {
		$temps = microtime();
		$temps = explode(' ', $temps);
		$this->microtimeStart = $temps[1] + $temps[0];
		
		$this->log = new Logger('./logs');
				
		$this->addElement('title', SITE_NAME);
		
		//on cr�e les �l�ments g�n�raux du footer
		$this->addFoot('<span id="foot_version">MondoPhoto Upload Service v'.VERSION.'</span>');
		$this->addFoot('<span class="separator"> - </span>');
		$this->addFoot('<span id="foot_copyright">&copy; 2012 loclamor</span>');
		
		//on v�rifie que on a bien une page de demand�e
		if(isset($_GET['page']) and !empty($_GET['page'])){
			$this->page = $_GET['page'];
		}
		else {
			$this->page = 'accueil';
		}
		
		$url = new Url();
		$url->addParam('page', 'accueil');
		
		$this->addMenu('<li id="onglet_1"><a class="brand" href="'.$url->getUrl().'"><img src="img/upload-ico.png" alt=""/>&nbsp;MondoPhoto Upload Service</a></li>','onglets', "ul", array("class"=>"nav"));
		

		$this->user_connected = false;
		if(!isset($_SESSION['upload']['isConnect'])){
			$_SESSION['upload']['isConnect'] = null;
		}
		//on v�rifie la connexion
		if(!isset($_SESSION['upload']['isConnect']) and $_SESSION['upload']['isConnect'] != 'true'){
			if(isset($_POST['pwd']) and !empty($_POST['pwd']) and isset($_POST['pseudo']) and !empty($_POST['pseudo'])) {
				$this->page = 'traitementConnexion';
			}
			else {
				
				$this->page = 'connexion';
			}
		}
		else {
			//ici on est connect�
			
			$this->user = new Bdmap_Utilisateur($_SESSION['upload']['id']);
			//TODO v�rifier si l'user existe bien
			
			// on d�fini une variable de connexion :
			$this->user_connected = true;
			
			$this->log->setBaseString('Upload : '.$this->user->getPseudo().' :');
			
			// on construit le menu
			$url = new Url();
			
			$this->addMenu('<li id="onglet_divider_1" class="divider-vertical" ></li>', 'onglets');
			$this->addMenu('<li id="onglet_profil" ><a href="#" >Connect&eacute; en tant que '.$this->user->getPseudo().'</a></li>', 'onglets');
			
			$url->addParam('page', 'deconnexion');
			$this->addMenu('<li id="onglet_deconnection" ><a class="" href="'.$url->getUrl().'"><i class="icon-off"></i> Se deconnecter</a></li>', 'onglets');
			
			//quand on est connect� il faut aussi construire l'interface d'onglets
			//donc 1 page de contenu = 1 onglet

			$this->addContent('<ul class="nav nav-tabs" id="main-tab-nav"></ul>','main-tab','div',array('class'=>'tabbable tabs-left'));
				$urlTab = new Url(true);
				$urlTab->addParam('page', 'albums');
				$class = ($this->page=='albums'||$this->page=='accueil'||$this->page=='album'||empty($this->page)?'active':'');
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
			case 'createProcess' :
				$this->doCreateAlbum();
				break;
			case 'album' :
				$this->getAlbumContent($_GET['idAlbum']);
				break;
            case 'fusion':
				$this->doMerge($_POST['source'], $_POST['dest']);
				break;
            case 'compte':
                $this->getCompte();
                break;
            case 'updateCompte':
                $this->doUpdateCompte($_POST);
                break;
			case 'albums' :
			case 'accueil' :
			default:
				//par d�faut, on affiche l'accueil
				$this->getAlbums();
		}
	
		$nbAdmQuery = SQL::getInstance()->getNbAdmQuery();
		$nbQuery = SQL::getInstance()->getNbQuery();
		
		$this->addFoot('<span class="separator"> - </span>');
		$this->addFoot('<span id="foot_queries">'.$nbAdmQuery.'/'.$nbQuery.' requ&ecirc;tes</span>');

		
		/*
		 * On g�re ici les notifictions
		 */
		if(SQL::getInstance()->getNbErrors() > 0){
			$this->addContent('<script>$(document).ready(function(){ notify("'.SQL::getInstance()->getNbErrors().' erreurs SQL.<br/>Consultez le repertoire de logs SQL.", 3000); });</script>');
		}
		if(isset($_GET['notify'])){
			$this->addContent('<script>$(document).ready(function(){ notify("'.$_GET['notify'].'", 3000); });</script>');
		}
		
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
		$urlAction->addParam('notify', 'Vous avez ete deconecte');
		redirect($urlAction->getUrl());
		echo 'Vous avez �t� d�connect�.<br/><a href="'.$urlAction->getUrl().'">retour accueil</a>';
	}
	
	
	public function getAlbums(){
		
		$urlFils = new Url(true);
		$urlFils->addParam('page', 'albums');
		$this->addElement('filariane', '<a href="'.$urlFils->getUrl().'">mes albums</a>');
		
		$this->addPage(new Page_Upload_Albums(),'main-tab-content');
		
		
		
		$this->log->log('infos', 'infos_general', 'page albums affichee', Logger::GRAN_MONTH);
		
	}
	
	public function createAlbum(){
		
		$urlFils = new Url(true);
		$urlFils->addParam('page', 'create');
		$this->addElement('filariane', '<a href="'.$urlFils->getUrl().'">nouvel albums</a>');
		
		$this->addPage(new Page_Upload_CreateAlbum(),'main-tab-content');
		
		$this->log->log('infos', 'infos_general', 'page createAlbum affichee', Logger::GRAN_MONTH);
	}
	
	public function doCreateAlbum() {
		$this->addPage(new Page_Upload_DoCreateAlbum(),'main-tab-content');
		
		$this->log->log('infos', 'infos_general', 'process creation album', Logger::GRAN_MONTH);
	}
        
        public function doMerge($source, $dest) {
		$this->addPage(new Page_Upload_DoMerge(array('source'=>$source, 'dest'=>$dest)));
		
		$this->log->log('infos', 'infos_merge', 'demande de merge de '.$source.' dans '.$dest, Logger::GRAN_MONTH);
		
	}
	
	public function getAlbumContent($idAlbum) {
		
		$album = Gestionnaire::getGestionnaire('album')->getOne($idAlbum);
		
		$urlFils = new Url(true);
		$urlFils->addParam('page', 'albums');
		$this->addElement('filariane', '<a href="'.$urlFils->getUrl().'">mes albums</a>');
		
		$urlFils = new Url(true);
		$urlFils->addParam('page', 'album');
		$urlFils->addParam('idAlbum', $idAlbum);
		$this->addElement('filariane', '<a href="'.$urlFils->getUrl().'">'.$album->getNom().'</a>');
		
		$this->addPage(new Page_Upload_AlbumContent($idAlbum),'main-tab-content');
		
		
		
		$this->log->log('infos', 'infos_general', 'page album affichee pour l\'album '.$album->getId().' : '.$album->getNom(), Logger::GRAN_MONTH);
	}
    
	public function getCompte() {
		
		$urlFils = new Url(true);
		$urlFils->addParam('page', 'compte');
		$this->addElement('filariane', '<a href="'.$urlFils->getUrl().'">mon compte</a>');
		
		
		$this->addPage(new Page_Upload_Compte(),'main-tab-content');
		
		
		
		$this->log->log('infos', 'infos_general', 'page compte affichee', Logger::GRAN_MONTH);
	}
    
	public function doUpdateCompte($post) {
		
		$this->addPage(new Page_Upload_DoUpdateCompte($post),'main-tab-content');
		
		$this->log->log('infos', 'infos_general', 'page compte update', Logger::GRAN_MONTH);
	}
	
}