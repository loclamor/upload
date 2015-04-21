<?php

class Site_Administration extends Site {
	public function __construct($admin = false) {
		$temps = microtime();
		$temps = explode(' ', $temps);
		$this->microtimeStart = $temps[1] + $temps[0];
				
		$this->addElement('title', 'Administration '.SITE_NAME);
		
		//on crée les éléments généraux du footer
		$this->addElement('foot', '&copy; 2012 loclamor');
		
		//on vérifie que on a bien une page de demandée
		if(isset($_GET['page']) and !empty($_GET['page'])){
			$this->page = $_GET['page'];
		}
		else {
			$this->page = 'accueil';
		}
		
		//on vérifie la connexion
		if(!isset($_SESSION['administration']['isConnect']) and $_SESSION['administration']['isConnect'] != 'true'){
			if(isset($_POST['pwd']) and !empty($_POST['pwd']) and isset($_POST['pseudo']) and !empty($_POST['pseudo'])) {
				$this->page = 'traitementConnexion';
			}
			else {
				
				$this->page = 'connexion';
			}
		}
		else {
			$this->user = new Utilisateur($_SESSION['administration']['id']);
			
			// on construit le menu
			$url = new Url();
			
			$url->addParam('page', 'addForum');
			$this->addElement('menu', '<li><a href="'.$url->getUrl().'">Ajouter un Forum</a></li>');
			
			$url->addParam('page', 'addCateg');
			$this->addElement('menu', '<li><a href="'.$url->getUrl().'">Ajouter une Catégorie à un Forum</a></li>');
		}
		
		$urlAccueil = new Url(true);
		$this->addElement('filariane', '<a href="'.$urlAccueil->getUrl().'">Accueil</a>');
		
		$this->addElement('head', '<h1>Administration</h1>');
		
		switch ($this->page) {
			case 'connexion':
				$this->getConnexion();
				break;
			case 'traitementConnexion':
				$this->doConnexion($_POST['pseudo'], $_POST['pwd']);
				break;
				
			case 'addForum' :
				$this->addForum();
				break;
			case 'traitementAddForum' :
				$this->doAddForum($_POST['nom'], $_POST['num_ordre']);
				break;
				
			case 'addCateg' :
				$this->addCateg();
				break;
			case 'traitementAddCateg' :
				$this->doAddCateg($_POST['id_forum'], $_POST['titre'], $_POST['ss_titre']);
				break;
				
			case 'accueil' :
			default:
				//par défaut, on affiche l'accueil
				$this->getAccueil();
		}
		
		$nbAdmQuery = SQL::getInstance()->getNbAdmQuery();
		$nbQuery = SQL::getInstance()->getNbQuery();
		
		$this->addElement('foot', $nbAdmQuery.'/'.$nbQuery.' requêtes');
		
		$temps = microtime();
		$temps = explode(' ', $temps);
		$this->microtimeEnd = $temps[1] + $temps[0];
		
		$this->addElement('foot', 'page générée en '.round(($this->microtimeEnd - $this->microtimeStart),3).' secondes');
		
	}
	
		public function getConnexion() {
		
		$urlConect = new Url(true);
		$urlConect->addParam('page', 'connexion');
		$this->addElement('filariane', '<a href="'.$urlConect->getUrl().'">connexion</a>');
		
		$this->addPage(new Page_Administration_GetConnexion());
	}
	
	public function doConnexion($pseudo, $pwd) {
		$this->addPage(new Page_Administration_DoConnexion($pseudo, $pwd));
	}
	
	public function addForum() {
		$urlPage = new Url(true);
		$urlPage->addParam('page', 'addForum');
		$this->addElement('filariane', '<a href="'.$urlPage->getUrl().'">ajout d\'un Forum</a>');
		
		$this->addPage(new Page_Administration_AddForum());
	}
	
	public function doAddForum($nom, $numOrdre) {
		$this->addPage(new Page_Administration_DoAddForum($nom, $numOrdre));
	}
	
	public function addCateg() {
		
		$urlPage = new Url(true);
		$urlPage->addParam('page', 'addCateg');
		$this->addElement('filariane', '<a href="'.$urlPage->getUrl().'">ajout d\'une Catégorie</a>');
		
		$this->addPage(new Page_Administration_AddCateg());
		
	}
	
	public function doAddCateg($idForum, $titre, $sousTitre) {
		$this->addPage(new Page_Administration_DoAddCateg($idForum, $titre, $sousTitre));
	}
	
	public function getAccueil(){
		
		$this->addElement('content', 'Bienvenue sur l\'administration. <br/>Choisissez une action');

	}
	
	
}