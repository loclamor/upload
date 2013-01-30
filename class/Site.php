<?php

abstract class Site {
	
	private $title = array();
	private $head = array();
	private $menu = array();
	private $filariane = array();
	private $content = array();
	private $foot = array();
	private $errors = array();
	private $confirm = array();
	private $infos = array();
	
	/**
	 * @var Logger
	 */
	private $log;
	
	private $microtimeStart = 0;
	private $microtimeEnd = 0;
	
	/**
	 * @var Bdmap_Utilisateur
	 */
	public $user = null;
	private $user_connected = false;
	
	public $page = null;
	public $couleur;
	
	/**
	 * Constructeur, gère l'appel des pages
	 * @param boolean $admin [optional] détermine si on est dans l'administration ou pas pour afficher ou pas certains elements
	 */
	public abstract function __construct($admin = false);
	
	/**
	 * retourne le contenu de l'element title pour affichage
	 * @param String $impl [optional] la chaine de liaison
	 */
	public function getTitle($impl = ' - ') {
		return implode($impl,$this->title);
	}
	
	/**
	 * retourne le contenu de l'element head pour affichage
	 * @param String $impl [optional] la chaine de liaison
	 */
	public function getHead($impl = ' ') {
		return implode($impl,$this->head);
	}
	
	/**
	 * retourne le contenu de l'element menu pour affichage
	 * @param String $impl [optional] la chaine de liaison
	 */
	public function getMenu($impl = '') {
		return implode($impl, $this->menu);
	}
	
	/**
	 * retourne le contenu de l'element fil d'ariane pour affichage
	 * @param String $impl [optional] la chaine de liaison
	 */
	public function getFilAriane($impl = ' > ') {
		return implode($impl,$this->filariane);
	}
	
	/**
	 * retourne le contenu de l'element content pour affichage
	 * @param String $impl [optional] la chaine de liaison
	 */
	public function getContent($impl = '') {
		return implode($impl,$this->content);
	}
	
	/**
	 * retourne le contenu de l'element foot pour affichage
	 * @param String $impl [optional] la chaine de liaison
	 */
	public function getFoot($impl = ' - ') {
		return implode($impl,$this->foot);
	}
	
	/**
	 * retourne le contenu de l'element errors pour affichage
	 * @param String $impl [optional] la chaine de liaison
	 */
	public function getMessageErrors($impl = '<br/>') {
		return implode($impl,$this->errors);
	}
	
	/**
	 * retourne le contenu de l'element confirm pour affichage
	 * @param String $impl [optional] la chaine de liaison
	 */
	public function getMessageConfirm($impl = '<br/>') {
		return implode($impl,$this->confirm);
	}
	
	/**
	 * retourne le contenu de l'element infos pour affichage
	 * @param String $impl [optional] la chaine de liaison
	 */
	public function getMessageInfos($impl = '<br/>') {
		return implode($impl,$this->infos);
	}
	
	/**
	 * Ajoute du contenu dans un element
	 * @param string $var (title, head, content, foot, menu) l'element
	 * @param string $content le contenu
	 * @param boolean $end [optional] le contenu est il ajouté en fin de l'element ?
	 */
	public function addElement($var,$content,$end=true) {
		if($content != ""){
			if($end) {
				if(!is_array($content)) {
					array_push($this->$var, $content);
				}
				else {
					foreach ($content as $cont) {
						array_push($this->$var, $cont);
					}
				}
			}
			else {
				if(!is_array($content)) {
					array_unshift($this->$var,$content);
				}
				else {
					$content = array_reverse($content);
					foreach ($content as $cont) {
						array_unshift($this->$var, $cont);
					}
				}
			}
		}
	}
	
	public function addPage(Page $page) {
		$this->addElement('content', $page->get());
		$this->addElement('title', $page->getTitle());
		$this->addElement('errors', $page->getMessageErrors());
		$this->addElement('confirm', $page->getMessageConfirm());
		$this->addElement('infos', $page->getMessageInfos());
	}
}

