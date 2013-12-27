<?php

class Bdmap_Photo extends Entite {
	
	public $id;
	public $uniqid;
	public $id_album;
	public $legend;
	public $url;
	public $date_upload = null; //AAAA-MM-JJ HH:mm:ss
	public $privacy = "BY_LINK_ONLY";
        public $seen = 0;
	
	public $DB_table = 'photo';
	public $DB_equiv = array(
		'id' => 'id',
		'uniqid' => 'uniqid',
		'id_album' => 'id_album',
		'legend' => 'legend',
		'url' => 'url',
		'date_upload' => 'date_upload',
		'privacy' => 'privacy',
                'seen' => 'seen'
	);
	
	public function getId() {
		return $this->id;
	}
	
	public function getUniqid() {
		return $this->uniqid;
	}
	
	public function getIdAlbum() {
		return $this->id_album;
	}
	
	public function getLegende() {
		return $this->legend;
	}
	
	public function getUrl() {
		return $this->url;
	}
	
	public function getMinUrl($minType = 'H', $minSize = 120, $generate = true){
		$path_parts = pathinfo($this->getUrl());
		$minUrl = $path_parts['dirname'].'/'.$path_parts['filename'].'.min'.$minType.$minSize.'.'.$path_parts['extension'];
		if(!file_exists($minUrl) and $generate) {
			return redimJPEG($this->getUrl(), $minSize, $minType, $minUrl);
		}
		else {
			return $minUrl;
		}
	}
	
	public function getPrivateUrl() {
		return "./photo/".$this->getUniqid().".jpg";
	}
	
	public function getMinPrivateUrl($minType = 'L', $minSize = 800) {
		return "./photo/".$this->getUniqid().".min".$minType.$minSize.".jpg";
	}
	
	public function getDateUpload() {
		return $this->date_upload;
	}
	
	public function getPrivacy() {
		return $this->privacy;
	}
	
	public function getSeen() {
		return $this->seen;
	}

	public function setId($id) {
		$this->id = $id;
	}
	
	public function setUniqid($id) {
		$this->uniqid = $id;
	}
	
	public function setIdAlbum($id_album) {
		$this->id_album = $id_album;
	}
	
	public function setLegende($legende) {
		$this->legend = $legende;
	}
	
	public function setUrl($url) {
		$this->url = $url;
	}
	
	public function setDateUpload($date_upload) {
		$this->date_upload = $date_upload;
	}
	
	public function setPrivacy($privacy) {
		$this->privacy = $privacy;
	}
	
	public function setSeen($seen) {
		$this->seen = $seen;
	}
	
}