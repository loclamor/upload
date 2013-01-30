<?php

abstract class Page {
	private $content = array();
	private $title = array();
	
	private $errors = array();
	private $confirm = array();
	private $infos = array();
	
	public abstract function __construct();
	
	public function get($glue = "\n"){
		return implode($glue, $this->content);
	}
	
	/**
	 * Ajoute du contenu dans un element particulier
	 * @param String $var l'element
	 * @param String $content le contenu
	 * @param boolean $end indique si on ajoute le contenu à la fin du contenu de l'element ou pas
	 */
	public function addElement($var,$content,$end=true) {
		if($end) {
			array_push($this->$var, $content);
		}
		else {
			array_unshift($this->$var,$content);
		}
	}
	
	/**
	 * Ajoute du contenu dans l'element contenu
	 * Equivaut à addElement('content', $content,$end)
	 * @param String $content le contenu
	 * @param boolean $end indique si on ajoute le contenu à la fin de l'element contenu ou pas
	 */
	public function add($content,$end=true) {
		$this->addElement('content', $content,$end);
	}
	
	
	public function addTitle($title,$end=true) {
		$this->addElement('title', $title,$end);
	}
	
	public function getTitle($glue = ' - '){
		if($glue !== false) {
			return implode($glue, $this->title);
		}
		else {
			return $this->title;
		}
	}
	
	public function getMessageErrors($glue = '<br/>'){
		if($glue !== false) {
			return implode($glue, $this->errors);
		}
		else {
			return $this->errors;
		}
	}
	public function getMessageConfirm($glue = '<br/>'){
		if($glue !== false) {
			return implode($glue, $this->confirm);
		}
		else {
			return $this->confirm;
		}
	}
	public function getMessageInfos($glue = '<br/>'){
		if($glue !== false) {
			return implode($glue, $this->infos);
		}
		else {
			return $this->infos;
		}
	}
}