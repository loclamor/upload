<?php
require_once 'conf/init.php';

if(isset($_GET['page'])){
	$page = $_GET['page'];
	
	switch($page){
		case 'traitementUploadPhoto':
			require_once 'ajaxFiles/uploadScript.php';
			break;
                case 'traitementRotatePhoto':
			require_once 'ajaxFiles/rotateImage.php';
			break;
		
                case 'traitementReorderPhoto':
			require_once 'ajaxFiles/reorder.php';
			break;
		
		default :
			echo '{success: false}';
	}
	//visualiser le loader une petite seconde...
	//for($i=0;$i<10000000;$i++){}
	
}
else {
	//erreur
	
}