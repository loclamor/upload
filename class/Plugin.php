<?php
abstract class Plugin {
	
	private $DOMContent;
	
	public function __construct(){
		
	}
	
	public function setDomContent(DOMDocument & $dom){
		$this->DOMContent = $dom;
		//if($dom instanceof DOMDocument)
		{
			$elt = $dom->getElementById("main-tab-content");
			$myNode = $dom->createTextNode("coucou");
			if($elt instanceof DOMNode)
			{
				$elt->appendChild($myNode);
			}
			else {
				$dom->documentElement->appendChild($myNode);
			}
		}
		//echo htmlspecialchars_decode($dom->saveHTML());
	}
	
	public abstract function exec();
	
}