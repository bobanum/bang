<?php
namespace Bang;
use PDO;
error_reporting(E_ALL);
class Bang {
	use GetSet;
	public $exclude = ['sqlite_sequence'];
	protected $db;
	public $messages = [];
	public $results = "..";
	protected $_pdo = null;
	protected $_tables = null;
	function __construct($db)
	{
		if (realpath($db)) {
			$this->db = $db;
			
		} else {
			$this->db = realpath(dirname($_SERVER['SCRIPT_FILENAME'])."/".$db);

		}
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
			$this->setConfigs();
			$this->processViews();
			$table->go();
			$this->messages = array_merge($this->messages, $table->messages);
		}
		echo implode("\r\n", $this->messages);
	}
	public function processViews()
	{
		$views = glob($this->template_path("view_layout_*"));
		foreach ($views as $view) {
			$viewName = substr($view, strlen(__DIR__)+19, -4);
			$viewPath = str_replace("_", "/", $viewName);
			$path = "resources/views/{$viewPath}.blade.php";
			$path = $this->output_path($path);
			$this->applyTemplate($view, $this, $path);
			$this->messages[] = "• Creating file 🗎'{$path}'\r\n  with View 👁'layout {$view}.	' from template.";

		}
	}
	public function setConfigs() {
		$config = file_get_contents($this->output_path("config/database.php"));
		$config = str_replace("'default' => env('DB_CONNECTION', 'mysql')", "'default' => 'sqlite'", $config);
		$config = str_replace("'database' => env('DB_DATABASE', database_path('database.sqlite'))", "'database' => '{$this->db}'", $config);
		file_put_contents($this->output_path("config/database.php"), $config);
	}
	public function output_path($file="") {
		//TODO Stop assuming db in database folder
		$result = dirname($this->db);
		$result = dirname($result);
		$result = realpath($result);
		if ($file) {
			$result .= "/".$file;
		}
		return $result;
	}
	public function phar_path($file="") {
		$result = basename($this);
		//TODO Check for non phar path
		$result = substr($result, 7);
		$result = dirname($result);
		$result = dirname($result);
		$result = realpath($result);
		if ($file) {
			$result .= "/".$file;
		}
		return $result;
	}
	public function template_path($file = "") {
		$result = __DIR__."/../templates";
		if ($file) {
			$result .= "/$file.php";
		}
		return $result;
	}
	public function applyTemplate($template, $obj, $path=null) {
		if (!realpath($template)) {
			$template = $this->template_path($template);
		}
		if (is_array($obj)) {
			extract($obj);
		}
		$tmpl = require $template;
		if ($path) {
			if (!file_exists(dirname($path))) {
				mkdir(dirname($path), 0777, true);
			}
			file_put_contents($path, $tmpl);
		} else {
			return $tmpl;
		}
	}
}