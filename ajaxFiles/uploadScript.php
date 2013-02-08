<?php

/**
 * Handle file uploads via XMLHttpRequest
 */
class qqUploadedFileXhr {
    /**
     * Save the file to the specified path
     * @return boolean TRUE on success
     */
    function save($path) {    
        $input = fopen("php://input", "r");
        $temp = tmpfile();
        $realSize = stream_copy_to_stream($input, $temp);
        fclose($input);
        
        if ($realSize != $this->getSize()){            
            return false;
        }
        
        $target = fopen($path, "w");        
        fseek($temp, 0, SEEK_SET);
        stream_copy_to_stream($temp, $target);
        fclose($target);
        
        return true;
    }
    function getName() {
        return $_GET['qqfile'];
    }
    function getSize() {
        if (isset($_SERVER["CONTENT_LENGTH"])){
            return (int)$_SERVER["CONTENT_LENGTH"];            
        } else {
            throw new Exception('Getting content length is not supported.');
        }      
    }   
}

/**
 * Handle file uploads via regular form post (uses the $_FILES array)
 */
class qqUploadedFileForm {  
    /**
     * Save the file to the specified path
     * @return boolean TRUE on success
     */
    function save($path) {
        if(!move_uploaded_file($_FILES['qqfile']['tmp_name'], $path)){
            return false;
        }
        return true;
    }
    function getName() {
        return $_FILES['qqfile']['name'];
    }
    function getSize() {
        return $_FILES['qqfile']['size'];
    }
}

class qqFileUploader {
    private $allowedExtensions = array();
    private $sizeLimit = 10485760;
    private $file;

    function __construct(array $allowedExtensions = array(), $sizeLimit = 10485760){        
        $allowedExtensions = array_map("strtolower", $allowedExtensions);
            
        $this->allowedExtensions = $allowedExtensions;        
        $this->sizeLimit = $sizeLimit;
        
        $this->checkServerSettings();       

        if (isset($_GET['qqfile'])) {
            $this->file = new qqUploadedFileXhr();
        } elseif (isset($_FILES['qqfile'])) {
            $this->file = new qqUploadedFileForm();
        } else {
            $this->file = false; 
        }
    }
    
    private function checkServerSettings(){        
        $postSize = $this->toBytes(ini_get('post_max_size'));
        $uploadSize = $this->toBytes(ini_get('upload_max_filesize'));        
        
        if ($postSize < $this->sizeLimit || $uploadSize < $this->sizeLimit){
            $size = max(1, $this->sizeLimit / 1024 / 1024) . 'M';             
            die("{'error':'augmenter les variables d\'initialisation post_max_size et upload_max_filesize � $size'}");    
        }        
    }
    
    private function toBytes($str){
        $val = trim($str);
        $last = strtolower($str[strlen($str)-1]);
        switch($last) {
            case 'g': $val *= 1024;
            case 'm': $val *= 1024;
            case 'k': $val *= 1024;        
        }
        return $val;
    }
    
    /**
     * Returns array('success'=>true) or array('error'=>'error message')
     */
    function handleUpload($uploadDirectory, $replaceOldFile = FALSE){
        if (!is_writable($uploadDirectory)){
            return array('error' => "Erreur serveur : le r�pertoire de t�l�chargement n\'est pas accessible en �criture : ".$uploadDirectory);
        }
        
        if (!$this->file){
            return array('error' => 'Pas de fichier envoy�');
        }
        
        $size = $this->file->getSize();
        
        if ($size == 0) {
            return array('error' => 'Le fichier est vide');
        }
        
        if ($size > $this->sizeLimit) {
            return array('error' => 'Le fichier est trop gros');
        }
        
        $pathinfo = pathinfo($this->file->getName());
        $filename = $nomPhoto = noSpecialChar($pathinfo['filename']);
        
        //$filename = md5(uniqid());
        $ext = $pathinfo['extension'];

        if($this->allowedExtensions && !in_array(strtolower($ext), $this->allowedExtensions)){
            $these = implode(', ', $this->allowedExtensions);
            return array('error' => 'Le fichier a une extention non autoris�e. Les extentions autoris�es sont '. $these . '.');
        }
        
        if(!$replaceOldFile){
            /// don't overwrite previous files that were uploaded
            while (file_exists($uploadDirectory . $filename . '.' . $ext)) {
                $filename .= rand(10, 99);
            }
        }
        
        if ($this->file->save($uploadDirectory . $filename . '.' . $ext)){
        	$uploadFile = $uploadDirectory . $filename . '.' . $ext;
        	$datePV = "";
        	
        	$tmp = getImageDate($uploadFile);
        	if($tmp) {
        		$datePV = $tmp;
        	}
        	
        //	$nouvUrlImage = redimJPEG($uploadFile);
        //	unlink($uploadFile);
            return array('success'=>true,
            			 'uploadedFile' => $uploadFile,
            			 'datePV' => $datePV
            			 );
        } else {
            return array('error'=> 'Impossible d\'enregistrer le fichier.' .
                'L\'envoi a �t� annul�, ou une erreur serveur est survenue');
        }
        
    }    
}
/**
 * Cr�ation du rep
 */

//on r�cup�re les noms du lieu, du pays, du continent
$lieu = GestionLieux::getInstance()->getLieu($_GET['idLieu']);
$pays = GestionPays::getInstance()->getPays($lieu->getIdPays());
$continent = GestionContinents::getInstance()->getContinent($pays->getIdContinent());
if (($lieu instanceof Lieu) and ($pays instanceof Pays) and ($continent instanceof Continent)) {
//	debug($_FILES);
	
	$nomLieu = noSpecialChar($lieu->getNom());
	$nomPays = noSpecialChar($pays->getNom());
	$nomCont = noSpecialChar($continent->getNom());
	
	$dos = strtolower(substr($nomCont,0,2).'/'.$nomPays.'/'.$nomLieu);
	
//	echo $dos.'/'.$nomPhoto;
	
	if(!is_dir('images/'.$dos)){
		if(!is_dir(strtolower('images/'.substr($nomCont,0,2).'/'.$nomPays))){
			if(!is_dir(strtolower('images/'.substr($nomCont,0,2)))){
				if(!is_dir(strtolower('images/'))){
					mkdir(strtolower('images/'),0777);
				}
				mkdir(strtolower('images/'.substr($nomCont,0,2)),0777);
			}
			mkdir(strtolower('images/'.substr($nomCont,0,2).'/'.$nomPays),0777);
		}
		mkdir(strtolower('images/'.$dos),0777);
	}
	// list of valid extensions, ex. array("jpeg", "xml", "bmp")
	$allowedExtensions = array("jpeg", "jpg");
	// max file size in bytes
	if(APPLICATION_ENV == 'prod') {
		$sizeLimit = 8 * 1024 * 1024;
	}
	else {
		$sizeLimit = 2 * 1024 * 1024;
	}
	
	$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
	$result = $uploader->handleUpload('images/'.$dos.'/');
	// to pass data through iframe you will need to encode all html tags
	echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
}
else {
	echo '{success:false}';
}



