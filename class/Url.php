<?php
class Url {
	
	public $params = array();
	public $file = '';
	
	/**
	 * @param clean bool url "propre"
	 */
	public function __construct($clean = false, $file = '') {
		if(!$clean) {
			$tmp = explode('&',$_SERVER['QUERY_STRING']);
			foreach ($tmp as $var){
				$tmp2 = explode('=',$var);
				if(count($tmp2)>1){
					$this->params[$tmp2[0]] = $tmp2[1];
				}
				else {
					$this->params[$tmp2[0]] = '';
				}
			}
		}
		$this->file = $file;
	}
	
	/**
	 * @return string l' url
	 */
	public function getUrl() {
		$url = array();
		foreach ($this->params as $key => $value){
			if(!empty($key) && !is_null($key)) {
				$url[] = $key.'='.$value;
			} 
		}
		$baseUrl = implode('&', $url);
		return $this->file.'?'.$baseUrl;
	}
	
	/**
	 * ajoute un paramètre à l'url
	 */
	public function addParam($key, $value) {
		$this->params[$key] = $value;
	}
	
	/**
	 * ajoute des paramètres à l'url
	 */
	public function addParams(array $params){
		foreach ($params as $key => $value) {
			$this->addParam($key, $value);
		}
	}
	
	/**
	 * enlève un paramètre à l'url
	 */
	public function removeParam($key) {
		unset($this->params[$key]);
	}
}