<?php
namespace Bang\Table;
use PDO;

trait Analyze {
	public $_columns = [];
	public $belongsTo = [];
	public $hasMany = [];
	public $belongsToMany = [];
	public $hasManyThrough = [];
	public $hasOneThrough = [];
	public $isJunctionTable = false;
	public $tableLeft = null;
	public $tableRight = null;

	static function analyze($tables) {
		// Check for belongsTo and hasMany
		
		foreach($tables as $table) {
			$table->fetchColumns();
		}
		// Check for belongsTo
		foreach($tables as $table) {
			$table->analyzeBelongsTo();
		}
		// Check for hasMany
		foreach($tables as $table) {
			$table->analyzeHasMany();
		}
		// Check for belongsToMany
		foreach($tables as $table) {
			$table->analyzeBelongsToMany();
		}
		// Check for hasManyThrough
		// foreach($tables as $table) {
		// 	$table->analyzeHasManyThrough();
		// }
		return;

	}
	function fetchColumns() {
		// Fetch all the table's columns
		$stmt = $this->db->execute("PRAGMA table_info({$this->name})");
		$columns = $stmt->fetchAll(PDO::FETCH_CLASS, "Bang\Column");
		$this->_columns = [];
		foreach ($columns as $column) {
			$this->_columns[$column->name] = $column;
			$column->table = $this;
		}
		$this->checkJunctionTable();
	}
	function analyzeBelongsTo() {
		// Add informations about foreign keys
		$stmt = $this->db->execute("PRAGMA foreign_key_list({$this->name})");
		$foreignKeys = $stmt->fetchAll(PDO::FETCH_OBJ);
		foreach ($foreignKeys as $foreignKey) {
			$foreignTable = $this->db->getTable($foreignKey->table);
			unset($foreignKey->table);
			$this->addBelongsTo($foreignTable);
			$foreignKey->foreignTable = $foreignTable;
			foreach ($foreignKey as $name=>$info) {
				$this->_columns[$foreignKey->from]->$name = $info;
			}
		} 
	}
	function analyzeHasMany() {
		if($this->isJunctionTable) {
			return;
		}
		foreach($this->belongsTo as $bt) {
			$bt->addHasMany($this);
		}
	}
	function analyzeBelongsToMany() {
		if(!$this->isJunctionTable) {
			return;
		}
		$bts = array_slice($this->belongsTo, 0);
		$this->addBelongsToMany();
		//Look for other foreign keys
		unset($bts[$this->tableLeft->name]);
		unset($bts[$this->tableRight->name]);
		foreach($bts as $bt) {
			$bt->addHasMany($this);
		}
	}
	function analyzeHasManyThrough() {
		$a1 = array_values($this->belongsTo);
		foreach($this->belongsTo as $bt) {
			foreach($this->hasMany as $hm) {
				$bt->hasManyThrough[$hm->name] = [$hm, $this];
				$hm->hasOneThrough[$bt->name] = [$bt, $this];
			}
		}
	}
	function checkJunctionTable() {
		$subs = explode("_",$this->name);
		// Does the name have 2 parts
		if (count($subs) !== 2) {
			return false;
		}
		// Is it in alphebetical order
		if ($subs[0] >= $subs[1]) {
			return false;
		}
		// Does each name corresponds with a table
		$tableLeft = $this->db->getTable($subs[0]);
		$tableRight = $this->db->getTable($subs[1]);
		if (!$tableLeft || !$tableRight) {
			return false;
		}
		// Do we have a foreign key for each table
		if (!isset($this->_columns[$tableLeft->foreignKey]) || !isset($this->_columns[$tableRight->foreignKey])) {
			return false;
		}
		$this->tableLeft = $tableLeft;
		$this->tableRight = $tableRight;
		$this->isJunctionTable = true;
		return true;
	}
	function addBelongsTo($table) {
		if (isset($this->belongsTo[$table->name])) {
			throw new \Exception('Already added');
		}
		$this->belongsTo[$table->name] = $table;
	}
	function addHasMany($table) {
		if ($table->isJunctionTable) {
			return false;
		}
		if (isset($this->hasMany[$table->name])) {
			return;
		}
		$this->hasMany[$table->name] = $table;
	}
	function addBelongsToMany() {
		$this->tableLeft->belongsToMany[$this->name] = $this->tableRight;
		$this->tableRight->belongsToMany[$this->name] = $this->tableLeft;
	}
}
