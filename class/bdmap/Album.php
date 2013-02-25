<?php

class Bdmap_Album extends Entite {
	
	public $id;
	public $uniqid;
	public $id_utilisateur;
	public $nom = "Album sans titre";
	public $date_creation; //AAAA-MM-JJ HH:mm:ss -> Y-m-d H:i:s
	public $date_mise_a_jour = null; //AAAA-MM-JJ
	public $privacy = "BY_LINK_ONLY";
	public $is_temp = 1;
	
	public $DB_table = 'album';
	public $DB_equiv = array(
		'id' => 'id',
		'uniqid' => 'uniqid',
		'nom' => 'nom',
		'id_utilisateur' => 'id_utilisateur',
		'date_creation' => 'date_creation',
		'date_mise_a_jour' => 'date_mise_a_jour',
		'privacy' => 'privacy',
		'is_temp' => 'is_temp'
	);
	
	public function getId() {
		return $this->id;
	}
	
	public function getUniqid() {
		return $this->uniqid;
	}
	
	public function getIdUtilisateur() {
		return $this->id_utilisateur;
	}
	
	public function getNom() {
		return $this->nom;
	}
	
	public function getDateCreation() {
		return $this->date_creation;
	}
	
	public function getDateMiseAJour() {
		return $this->date_mise_a_jour;
	}
	
	public function getPrivacy() {
		return $this->privacy;
	}
	
	public function isTemp() {
		return $this->is_temp == 1;
	}

	public function setId($id) {
		$this->id = $id;
	}
	
	public function setUniqid($id) {
		$this->uniqid = $id;
	}
	
	public function setIdUtilisateur($id_utilisateur) {
		$this->id_utilisateur = $id_utilisateur;
	}
	
	public function setNom($nom) {
		$this->nom = $nom;
	}
	
	public function setDateCreation($date_creation) {
		$this->date_creation = $date_creation;
	}
	
	public function setDateMiseAJour($date_mise_a_jour) {
		$this->date_mise_a_jour = $date_mise_a_jour;
	}
	
	public function setPrivacy($privacy) {
		$this->privacy = $privacy;
	}
	
	public function setTemp($bool) {
		$this->is_temp = ($bool?1:0);
	}
	
}