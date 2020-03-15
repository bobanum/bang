<?php
namespace Bang;
use PDO;
error_reporting(E_ALL);
class Bang {
	use GetSet;
	public $exclude = ['sqlite_sequence'];
	protected $db;
	public $messages = [];
	public $results = "./results";
	protected $_pdo = null;
	protected $_tables = null;
	function __construct($db)
	{
		$this->db = realpath(dirname($_SERVER['SCRIPT_FILENAME'])."/".$db);
	}
	function get_tables() {
		if (!$this->_tables) {
			$pdo = $this->connect();
			$stmt = $pdo->prepare("SELECT * FROM sqlite_master WHERE type='table'");	//TODO Indexes
			$stmt->execute();
			$tables = $stmt->fetchAll(PDO::FETCH_CLASS, "Bang\Table");
			$tables = array_filter($tables, function ($table) { return !in_array($table->name, $this->exclude);});
			foreach ($tables as $table) {
				$this->_tables[$table->name] = $table;
				$table->bang = $this;
			} 
		}
		return $this->_tables;
	}
	public function connect() {
		if (!$this->_pdo) {
			$pdo = new PDO("sqlite:{$this->db}");
			$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_CLASS);
			$this->_pdo = $pdo;
		}
		return $this->_pdo;
	}
	public function prepare($sql) {
		$pdo = $this->connect();
		$stmt = $pdo->prepare($sql);
		return $stmt;
	}
	public function hasMany($table) {
		$result = [];
		foreach($this->tables as $table2) {
			foreach($table2->foreignKeys as $foreignKey) {
				if ($foreignKey->table === $table) {
					$result[$table2->name] = $table2;
					break;
				}
			}
		}
		return $result;
	}
	public function execute($sql, $data=[]) {
		$pdo = $this->connect();
		$stmt = $pdo->prepare($sql);
		$stmt->execute($data);
		return $stmt;
	}
	public function go() {
		foreach ($this->tables as $table) {
			$table->go();
			$this->messages = array_merge($this->messages, $table->messages);
		}
		echo implode("\r\n", $this->messages);
	}
	//SCRAPE
	public function applyTemplate($table, $template, $path) {
		$path = dirname($this->db).'/'.$this->results.'/'.$path;
		if (!file_exists(dirname($path))) {
			mkdir(dirname($path), 0777, true);
		}
		$tmpl = require $template;
		file_put_contents($path, $tmpl);
	}
}