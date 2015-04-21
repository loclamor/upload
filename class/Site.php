<?php

abstract class Site {
	
	private $title = array();
	private $filariane = array();
	
	/**
	 * @var DOMDocument
	 */
	private $DOMHead = null;
	/**
	 * @var DOMDocument
	 */
	private $DOMMenu = null;
	/**
	 * @var DOMDocument
	 */
	private $DOMContent = null;
	/**
	 * @var DOMDocument
	 */
	private $DOMFoot = null;
	
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
	 * @param boolean $admin [optional, unusued] détermine si on est dans l'administration ou pas pour afficher ou pas certains elements
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
	 * 
	 */
	public function getHead() {
		if(is_null($this->DOMHead)){   	
        	$this->DOMHead = new DOMDocument;
		}
		return htmlspecialchars_decode($this->DOMHead->saveHTML());
	}
	
	/**
	 * retourne le contenu de l'element menu pour affichage
	 * 
	 */
	public function getMenu() {
		if(is_null($this->DOMMenu)){   	
        	$this->DOMMenu = new DOMDocument;
		}
		return htmlspecialchars_decode($this->DOMMenu->saveHTML());
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
	 * 
	 */
	public function getContent() {
		if(is_null($this->DOMContent)){   	
        	$this->DOMContent = new DOMDocument;
		}
		return htmlspecialchars_decode($this->DOMContent->saveHTML());
	}
	
	/**
	 * retourne le contenu de l'element foot pour affichage
	 * 
	 */
	public function getFoot($impl = ' - ') {
		if(is_null($this->DOMFoot)){   	
        	$this->DOMFoot = new DOMDocument;
		}
		return htmlspecialchars_decode($this->DOMFoot->saveHTML());
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
		
		switch($var) {
			case 'head':
			case 'menu':
			case 'content':
			case 'foot':
				$this->addContent('<pre>Tentative d\'ajout dans '.$var.'</pre>', uniqid('err'));
				break;
			default :
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
	}
	
	/** 
	 * Ajoute du HTML textuel au DOM du Hearder
	 * @param string $contentToAdd le HTML a rajouter
	 * @param string $idToAppend l'ID de l'element auquel sera accroché le HTML 
	 * @param string $baseBalise la balise de base du DOM (utilisé selement à la création du DOM/premier appel de la fonction)
	 * @param array $attributes un tableau d'attributs à ajouter à la balise de base
	 */
	public function addHead($contentToAdd, $idToAppend = "root_head", $baseBalise = "div", $attributes = null) {
		$this->addXml('DOMHead', $contentToAdd, $idToAppend, $baseBalise, $attributes);
	}
	/** 
	 * Ajoute du HTML textuel au DOM du Menu
	 * @param string $contentToAdd le HTML a rajouter
	 * @param string $idToAppend l'ID de l'element auquel sera accroché le HTML 
	 * @param string $baseBalise la balise de base du DOM (utilisé selement à la création du DOM/premier appel de la fonction)
	 * @param array $attributes un tableau d'attributs à ajouter à la balise de base
	 */
	public function addMenu($contentToAdd, $idToAppend = "root_menu", $baseBalise = "div", $attributes = null) {
		$this->addXml('DOMMenu', $contentToAdd, $idToAppend, $baseBalise, $attributes);
	}
	/** 
	 * Ajoute du HTML textuel au DOM du Content
	 * @param string $contentToAdd le HTML a rajouter
	 * @param string $idToAppend l'ID de l'element auquel sera accroché le HTML 
	 * @param string $baseBalise la balise de base du DOM (utilisé selement à la création du DOM/premier appel de la fonction)
	 * @param array $attributes un tableau d'attributs à ajouter à la balise de base
	 */
	public function addContent($contentToAdd, $idToAppend = "root_content", $baseBalise = "div", $attributes = null) {
		$this->addXml('DOMContent', $contentToAdd, $idToAppend, $baseBalise, $attributes);
	}
	/** 
	 * Ajoute du HTML textuel au DOM du Foot
	 * @param string $contentToAdd le HTML a rajouter
	 * @param string $idToAppend l'ID de l'element auquel sera accroché le HTML 
	 * @param string $baseBalise la balise de base du DOM (utilisé selement à la création du DOM/premier appel de la fonction)
	 * @param array $attributes un tableau d'attributs à ajouter à la balise de base
	 */
	public function addFoot($contentToAdd, $idToAppend = "root_foot", $baseBalise = "div", $attributes = null) {
		$this->addXml('DOMFoot', $contentToAdd, $idToAppend, $baseBalise, $attributes);
	}
	
	/**
	 * Ajoute du Xml à une variable DOM de la classe.
	 * Crée le DOM si besoin grace au paramètre $baseBalise avec comme ID $idToAppend et comme autres attributs $attributes
	 * @param string $siteVar nom de la variable DOM
	 * @param string $contentToAdd le HTML a rajouter
	 * @param string $idToAppend l'ID de l'element auquel sera accroché le HTML 
	 * @param string $baseBalise la balise de base du DOM (utilisé selement à la création du DOM/premier appel de la fonction)
	 * @param array $attributes un tableau d'attributs à ajouter à la balise de base
	 */
	public function addXml($siteVar, $contentToAdd, $idToAppend = "root", $baseBalise = "div", $attributes = null) {

		if(is_null($this->$siteVar)){
			$this->$siteVar = new DOMDocument;
	        $this->$siteVar->formatOutput = false;
	        $elt = $this->$siteVar->createElement($baseBalise); 
	        $elt->setAttribute('id', $idToAppend);
	        $elt->setIdAttribute('id', true);
	        if(!is_null($attributes)) {
				foreach ($attributes as $attName => $attValue) {
		        	$elt->setAttribute($attName,$attValue);
		        }
	        }
	        $this->$siteVar->appendChild($elt);
		}
		
		if($myNode = @DOMDocument::loadXml($contentToAdd)){
			//c'est du HTML
			if($myNode instanceof DOMDocument){
				$myNode = $myNode->documentElement;
			}
			//import du node sur DOMDocument
			//attention, retourne une copie du node importé, copie qui elle appartient au DOMDocument
			$myNode = $this->$siteVar->importNode($myNode,true);
			//ensuite (car il faut être sur le DOMDocument final pour que ce soit pris en comtpe), on set l'ID
			//TODO : idéalement, il faudrait parcourir tous les sous elements pour retrouver les IDs...
			try {
				$myNode->setIdAttribute('id', true);
			} catch (Exception $e) {
				//debug("cannot set Id attribute");
			}
		}
		else {
			//c'est du texte simple
			$myNode = $this->$siteVar->createTextNode($contentToAdd);
		}
		//on ajoute notre elt
		if($idToAppend !== null){
			$eltToAppend = $this->$siteVar->getElementById($idToAppend);
            if(!$eltToAppend){
            	$this->$siteVar->documentElement->appendChild($myNode);
            }
            else {
           		try {
           			$eltToAppend->appendChild($myNode);
           		} catch (Exception $e) {
           			debug("cannot append child to ".$idToAppend.' : '.$e->getMessage());
           			$this->$siteVar->documentElement->appendChild($myNode);
           		}
            	
            }
		}
		else {
			debug("no id specified");
			$this->$siteVar->documentElement->appendChild($myNode);
		}
	}
	
/*	
	public function addXml($siteVar, $xmlToAdd, $idAdding, $idToAdd = 'root', $baseBalise = "div", $attributes = array("class"=>"noClass"))
    {
    	//si le dom n'est pas créé, on le cré
        if(is_null($this->$siteVar)){   	
        	$this->$siteVar = new DOMDocument;
	        $this->$siteVar->formatOutput = false;
	        $elt = $this->$siteVar->createElement($baseBalise); 
	        $elt->setAttribute('id', $idAdding);
	        $elt->setIdAttribute('id', true);
	        
	        foreach ($attributes as $attName => $attValue) {
	        	$elt->setAttribute($attName,$attValue);
	        }
	        $textContent = $this->$siteVar->createTextNode($xmlToAdd);
	        $elt->appendChild($textContent);
	        
	        $this->$siteVar->appendChild($elt);
        }
		else {    
	        $childElt = $this->$siteVar->createElement($baseBalise);
	    	$childElt->setAttribute('id', $idAdding);
	        $childElt->setIdAttribute('id', true);
	        foreach ($attributes as $attName => $attValue) {
	        	$childElt->setAttribute($attName,$attValue);
	        }
	        $textContent = $this->$siteVar->createTextNode($xmlToAdd);
	        $childElt->appendChild($textContent);
	        
	        //on importe le nouvel element et on l'ajoute
	        $childElt = $this->$siteVar->importNode($childElt, true);
	
			//si il y a un ID auquel ataché, on le tente, sinon on met à la racine
			//TODO : si l'ID !== null mais pas existant...
	        if ($idToAdd !== null) {
	            $idToAdd = $this->$siteVar->getElementById($idToAdd);
	            if(!$idToAdd){
	            	$this->$siteVar->documentElement->appendChild($childElt);
	            }
	            else {
	           		$idToAdd->appendChild($childElt);
	            }
	        } else {
	            $this->$siteVar->documentElement->appendChild($childElt);
	        }
		}
    }
*/	
	/**
	 * ajoute une Page $page au contenu DOMContent
	 * @param Page $page
	 * @param string $idToAppend l'id sur lequel on vas attacher le contenu de la page
	 * @param string $setBaseBalise si a TRUE, le contenu de la page sera englobé dans une $baseBalise d'ID le nom de la class de $page avec les attributs $baseAttributes
	 * @param string $baseBalise type de la balise englobante si $setBaseBalise===TRUE
	 * @param array $baseAttributes attributs de la balise englobante si $setBaseBalise===TRUE. Ne doit pas contenir l'attibut ID
	 */
	public function addPage(Page $page, $idToAppend = 'root_content', $setBaseBalise = true, $baseBalise = "div", $baseAttributes = null) {
		if(is_null($baseAttributes)){
			$baseAttributes = array();
		}
		$content = $page->get();
		if($setBaseBalise===true) {
			$baseBaliseId = get_class($page);
			$attributes = array();
			foreach ($baseAttributes as $attName => $attValue){
				$attributes[] = $attName."='".$attValue."'";
			}
			$attributes = implode(" ", $attributes);
			$content = "<".$baseBalise." id='".$baseBaliseId."' ".$attributes." >".$content."</".$baseBalise.">";
		}
		$this->addContent($content, $idToAppend);
		
		$this->addElement('title', $page->getTitle());
		$this->addElement('errors', $page->getMessageErrors());
		$this->addElement('confirm', $page->getMessageConfirm());
		$this->addElement('infos', $page->getMessageInfos());
	}
}

