<?php
/**
 * Classe de log générique permettant de gérer l'archivage des fichiers de log
 * Configuration requise: PHP 5 (ne fonctionne pas avec PHP 4)
 * Encodage: UTF-8 (sans BOM)
 * Auteur: www.finalclap.com
 * Date: 19/02/2012
**/
class Logger {

    private $depot; # Dossier où sont enregistrés les fichiers logs (ex: /Applications/MAMP/www/monsite/logs)
    private $ready; # Le logger est prêt quand le dossier de dépôt des logs existe
    
    private $baseString;
    
    # Granularité (pour l'archivage des logs)
    const GRAN_VOID  = 'VOID';  # Aucun archivage
    const GRAN_MONTH = 'MONTH'; # Archivage mensuel
    const GRAN_YEAR  = 'YEAR';  # Archivage annuel
    
    /**
     * Constructeur
     * Vérifie que le dossier dépôt éxiste
     *
     * @param string $path Chemin vers le dossier de dépôt
    **/
    public function __construct($path, $baseString = ""){
        $this->ready = false;
        $this->baseString = $baseString;
        
        # Si le dépôt n'éxiste pas
        if( !is_dir($path) ){
            trigger_error("<code>$path</code> n'existe pas", E_USER_WARNING);
            return false;
        }
        
        $this->depot = realpath($path);
        $this->ready = true;
        
        return true;
    }
    
    public function setBaseString ($baseString) {
    	$this->baseString = $baseString;
    }
    
    /**
     * Retourne le chemin vers un fichier de log déterminé à partir des paramètres $type, $name et $granularity.
	 * (ex: /Applications/MAMP/www/monsite/logs/erreurs/201202/201202_erreur_connexion.log)
     * Elle créé le chemin si il n'éxiste pas.
	 *
	 * @param string $type Dossier dans lequel sera enregistré le fichier de log
     * @param string $name Nom du fichier de log
     * @param string $granularity Granularité : GRAN_VOID, GRAN_MONTH ou GRAN_YEAR
	 * @return string Chemin vers le fichier de log
    **/
    public function path($type, $name, $granularity = self::GRAN_YEAR){
		# On vérifie que le logger est prêt (et donc que le dossier de dépôt existe
        if( !$this->ready ){
            trigger_error("Logger is not ready", E_USER_WARNING);
            return false;
        }
		
		# Contrôle des arguments
        if( !isset($type) || empty($name) ){
            trigger_error("Paramètres incorrects", E_USER_WARNING);
            return false;
        }
        
        # Création dossier du type (ex: /Applications/MAMP/www/monsite/logs/erreurs/)
        if( empty($type) ){
            $type_path = $this->depot.'/';
        } else {
            $type_path = $this->depot.'/'.$type.'/';
            if( !is_dir($type_path) ){
                mkdir($type_path);
            }
        }
        
        # Création du dossier granularity (ex: /Applications/MAMP/www/monsite/logs/erreurs/201202/)
        if( $granularity == self::GRAN_VOID ){
            $logfile = $type_path.$name.'.log';
        }
        elseif( $granularity == self::GRAN_MONTH ){
            $mois_courant    = date('Ym');
            $type_path_mois    = $type_path.$mois_courant;
            if( !is_dir($type_path_mois) ){
                mkdir($type_path_mois);
            }
            $logfile = $type_path_mois.'/'.$mois_courant.'_'.$name.'.log';
        }
        elseif( $granularity == self::GRAN_YEAR ){
            $current_year    = date('Y');
            $type_path_year    = $type_path.$current_year;
            if( !is_dir($type_path_year) ){
                mkdir($type_path_year);
            }
            $logfile = $type_path_year.'/'.$current_year.'_'.$name.'.log';
        }
        else{
            trigger_error("Granularité '$granularity' non prise en charge", E_USER_WARNING);
            return false;
        }
        
        return $logfile;
    }
    
    /**
	 * Enregistre $row dans le fichier log déterminé à partir des paramètres $type, $name et $granularity
     *
     * @param string $type Dossier dans lequel sera enregistré le fichier de log
     * @param string $name Nom du fichier de log
     * @param string $row Texte à ajouter au fichier de log
     * @param string $granularity Granularité : GRAN_VOID, GRAN_MONTH ou GRAN_YEAR
    **/
    public function log($type, $name, $row, $granularity = self::GRAN_YEAR){
		# Contrôle des arguments
        if( !isset($type) || empty($name) || empty($row) ){
            trigger_error("Paramètres incorrects", E_USER_WARNING);
            return false;
        }
        
        $logfile = $this->path($type, $name, $granularity);
		
		if( $logfile === false ){
			trigger_error("Impossible d'enregistrer le log", E_USER_WARNING);
			return false;
		}
        # Ajout de la chaine par d�faut d�finie � la construction
        $row = $this->baseString.' '.$row;
		
		# Ajout de la date et de l'heure au début de la ligne
        $row = date('d/m/Y H:i:s').' '.$row;
		
		# Ajout du retour chariot de fin de ligne si il n'y en a pas
		if( !preg_match('#\n$#',$row) ){
			$row .= "\n";
		}
        
        $this->write($logfile, $row);
    }
    
    /**
     * Écrit (append) $row dans $logfile
     *
     * @param string $logfile Chemin vers le fichier de log
     * @param string $row Chaîne de caractères à ajouter au fichier
    **/
    private function write($logfile, $row){
        if( !$this->ready ){return false;}
        
        if( empty($logfile) ){
            trigger_error("<code>$logfile</code> est vide", E_USER_WARNING);
            return false;
        }
        
        $fichier = fopen($logfile,'a+');
        fputs($fichier, $row);
        fclose($fichier);
    }

}
?>