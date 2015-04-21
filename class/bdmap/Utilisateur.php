<?php

class Bdmap_Utilisateur extends Entite {
	
	public $id;
	public $uniqid;
	public $pseudo;
	public $nom;
	public $prenom;
	public $mot_de_passe;
	public $mail;
	public $date_inscription; //AAAA-MM-JJ HH:mm:ss
	public $date_anniversaire = null; //AAAA-MM-JJ
	public $signature = null;
	
	public $DB_table = 'utilisateur';
	public $DB_equiv = array(
		'id' => 'id',
		'uniqid' => 'uniqid',
		'pseudo' => 'pseudo',
		'nom' => 'nom',
		'prenom' => 'prenom',
		'mot_de_passe' => 'mot_de_passe',
		'mail' => 'mail',
		'date_inscription' => 'date_inscription',
		'date_anniversaire' => 'date_anniversaire',
		'signature' => 'signature'
	);
	
	public function getId() {
		return $this->id;
	}
	
	public function getUniqid() {
		return $this->uniqid;
	}
	
	public function getPseudo() {
		return $this->pseudo;
	}
	
	public function getNom() {
		return $this->nom;
	}
	
	public function getPrenom() {
		return $this->prenom;
	}
	
	public function getMotDePasse() {
		return $this->mot_de_passe;
	}
	
	public function getMail() {
		return $this->mail;
	}
	
	public function getDateInscription() {
		return $this->date_inscription;
	}
	
	public function getDateAnniversaire() {
		return $this->date_anniversaire;
	}
	
	public function getSignature() {
		return $this->signature;
	}

	public function setId($id) {
		$this->id = $id;
	}
	
	public function setUniqid($id) {
		$this->uniqid = $id;
	}
	
	public function setPseudo($pseudo) {
		$this->pseudo = $pseudo;
	}
	
	public function setNom($nom) {
		$this->nom = $nom;
	}
	
	public function setPrenom($prenom) {
		$this->prenom = $prenom;
	}
	
	public function setMotDePasse($mdp) {
		$this->mot_de_passe = $mdp;
	}
    
	public function setMail( $mail ) {
		$this->mail = $mail;
	}
	
	public function setDateInscription($date) {
		$this->date_inscription = $date;
	}
	
	public function setDateAnniversaire($date) {
		$this->date_anniversaire = $date;
	}
	
	public function setSignature($signature) {
		$this->signature = $signature;
	}
}