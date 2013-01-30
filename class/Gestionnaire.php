<?php
class Gestionnaire {
	
	private static $_instance = Array();
	private $loadedClass = array();
	private $class; //feinte pour accèder aux variables de conf d'une Entité
	private $className;
	
	/**
	 * 
	 * Enter description here ...
	 * @param Class $class
	 * @return Gestionnaire
	 */
	public static function getGestionnaire($class = null){
		$class = 'Bdmap_'.firstchartoupper($class);
		if(!isset(self::$_instance[$class])){
			if(!class_exists($class)){
				return false;
			}
			self::$_instance[$class] = new Gestionnaire($class);
		}
		return self::$_instance[$class];
	}
	
	public function __construct($class) {
		$this->class = new $class();
		$this->className = get_class($this->class);
	}
	
	/**
	 * @param integer $id
	 * @return $this->$class
	 */
	public function getOne($id) {
		if(array_key_exists($id, $this->loadedClass)) {
			return $this->loadedClass[$id];
		}
		else {
			$one = new $this->className($id);
			$this->loadedClass[$id] = $one;
			return $one;
		}
	}
	
	public function getAll($orderby = 'id', $desc = false) {
		if(!is_null($orderby) && !empty($orderby)) {
			$desc = $desc?' DESC':' ASC';
			$orderby = ' ORDER BY ' . $orderby.$desc;
		}
		else {
			$orderby = '';
		}
		$res = SQL::getInstance()->exec('SELECT '.$this->class->DB_equiv['id'].' FROM '.TABLE_PREFIX.$this->class->DB_table.$orderby);
		if($res) { //cas ou aucun retour requete (retour FALSE)
			$all = array();
			foreach ($res as $row) {
				$all[] = $this->getOne($row[$this->class->DB_equiv['id']]);
			}
		}
		else {
			$all = false;
		}
		return $all;
	}
	
	public function countAll() {
		$res = SQL::getInstance()->exec('SELECT COUNT(*) as nombre FROM '.TABLE_PREFIX.$this->class->DB_table);
		if($res) { //cas ou aucun retour requete (retour FALSE)
			$all = 0;
			foreach ($res as $row) {
				$all = $row['nombre'];
			}
		}
		else {
			$all = 0;
		}
		return $all;
	}
	
	/**
	 * Retourne les enregistrements corespondant aux conditions
	 * @param array $mixedConditions [var, value]
	 * @param unknown_type $orderby
	 * @param unknown_type $desc
	 * Array of the $this->class
	 */
	public function getOf(array $mixedConditions, $orderby = 'id', $desc = false) {
		if(!is_null($orderby) && !empty($orderby)) {
			$desc = $desc?'DESC':'ASC';
			$orderby = ' ORDER BY ' . $orderby;
		}
		else {
			$orderby = '';
		}
		$cond = array();
		foreach ($mixedConditions as $var => $value){
			$cond[] = $this->class->DB_equiv[$var].' = \''.$value.'\'';
		}
		$res = SQL::getInstance()->exec('SELECT '.$this->class->DB_equiv['id'].' FROM '.TABLE_PREFIX.$this->class->DB_table.' WHERE '.implode(' AND ',$cond).$orderby);
		if($res) { //cas ou aucun retour requete (retour FALSE)
			$all = array();
			foreach ($res as $row) {
				$all[] = $this->getOne($row[$this->class->DB_equiv['id']]);
			}
		}
		else {
			$all = false;
		}
		return $all;
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param array $mixedConditions [var, value]
	 */
	public function countOf(array $mixedConditions) {
		$cond = array();
		foreach ($mixedConditions as $var => $value){
			$cond[] = $this->class->DB_equiv[$var].' = \''.$value.'\'';
		}
		$res = SQL::getInstance()->exec('SELECT COUNT(*) as nombre FROM '.TABLE_PREFIX.$this->class->DB_table.' WHERE '.implode(' AND ',$cond));
		if($res) { //cas ou aucun retour requete (retour FALSE)
			$all = 0;
			foreach ($res as $row) {
				$all = $row['nombre'];
			}
		}
		else {
			$all = 0;
		}
		return $all;
	}
	
}