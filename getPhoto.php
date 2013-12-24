<?php
session_cache_limiter ('public');
require_once 'conf/init.php';



header('Content-type:image/jpg');


//getPhoto.php?uniqidPhoto=$2
if(isset($_GET['uniqidPhoto']) and !empty($_GET['uniqidPhoto'])){
	$uniqidPhoto = $_GET['uniqidPhoto'];
	$photo = Gestionnaire::getGestionnaire('photo')->getOneOf(array('uniqid' => $uniqidPhoto));
	if($photo instanceof Bdmap_Photo) {
		//TODO : actions sur la privacit�, � voir si utile

//			header("Accept-Ranges: bytes"); //avoid cache to work correctly
//			header("Content-Length: ".filesize($photo->getUrl()));
			
//			$seconds_to_cache = 60 * 60 * 24 * 30; //30 jours de cache
//			$ts = gmdate("D, d M Y H:i:s", time() + $seconds_to_cache) . " GMT";
//			header("Expires: $ts");
//			header("Pragma: cache");
//			header("Cache-Control: max-age=$seconds_to_cache");
			header("Content-Disposition: inline; filename=\"{$photo->getLegende()}\";");
			
			if(isset($_GET['minType']) and isset($_GET['minSize']) and !empty($_GET['minType']) and !empty($_GET['minSize'])){
				readfile($photo->getMinUrl($_GET['minType'],$_GET['minSize']));
			}
			else {
				readfile($photo->getUrl());
			}
                        /*
                         * data:image/jpg;base64,
                         */
	}
	else {
		//photo d'ereur
		
		header("Content-Disposition: inline; filename=\"Photo indisponible\";");
		readfile("img/appareil-photo.jpg");
	}
}
else {
	//photo d'ereur
	
	header("Content-Disposition: inline; filename=\"Photo indisponible\";");
	readfile("img/appareil-photo.jpg");
}