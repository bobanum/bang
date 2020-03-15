<?php
namespace Bang;

use PDO;
use Exception;

class Table {
	public $_columns = null;
	public $messages = [];
	public $bang = null;
	function __construct()
	{
		
	}
	function __get($name)
	{
		if(substr($name, -3) === "Var") {
			$name = substr($name, 0, -3);
			return '$'.$this->$name;
		}
		$get_name = "get_$name";
		if (method_exists($this, $get_name)) {
			return $this->$get_name();
		}
		throw new Exception("Bad property name '$name'.");
	}
	function get_columns() {
		if (!$this->_columns) {
			$stmt = $this->bang->execute("PRAGMA table_info({$this->name})");
			$this->_columns = $stmt->fetchAll(PDO::FETCH_CLASS, "Bang\Column");
			foreach ($this->_columns as $column) {
				$column->table = $this;
			} 
		}
		return $this->_columns;
	}
	function get_model() {
		$result = $this->name;
		if (substr($result, -1) === "s") {
			$result = substr($result, 0, -1);
		}
		$result = explode("_", $result);
		$result = array_map('ucfirst', $result);
		$result = implode("", $result);
		return $result;
	}
	function get_controller() {
		return "{$this->model}Controller";
	}
	function get_forcedName() {
		if (substr($this->name, -1) === "s") {
			return "";
		} else {
			return "protected \$table = '$this->name';\r\n\t";
		}
	}
	function get_fillable() {
		$result = $this->columns;
		$result = array_map(function ($col) { return $col->name; }, $result);
		$result = array_filter($result, function ($col) { return $col !== "id"; });
		$result = implode("', '", $result);
		$result = "protected \$fillable = ['$result'];\r\n\t";
		return $result;
	}
	function get_normalized() {
		$result = explode("_", $this->name);
		$result = array_map('ucfirst', $result);
		$result = implode("", $result);
		return lcfirst($result);
	}
	function get_sing() {
		$result = $this->normalized;
		if (substr($result, -1) === "s") {
			return substr($result, 0, -1);
		} else {
			return $result;
		}
	}
	function get_plur() {
		$result = $this->normalized;
		// echo $result;exit;
		if (substr($result, -1) === "s") {
			return $result;
		} else {
			return $result . "s";
		}
	}
	function get_views() {
		$result = $this->sing;
		return $result;
	}
	function get_label() {
		$result = $this->columns[2]->name;
		return $result;
	}
	function go() {
		$name = $this->name;
		$this->messages[] = "Processing Table '$name'.";
		foreach ($this->columns as $column) {
			$this->messages[] = "| ".str_pad($column->name, 30, " ").
			"| ".str_pad($column->type, 30, " ")."|";
		}
		$this->processModel();
		$this->processController();
		$this->processViews();
	}
	function report_header($width=65) {
		$result = "+".str_repeat("-", $width-2)."+\r\n";
		$result .= "| ".str_pad($table->name, $width-4, " ")." |\r\n";
		$result .= "+".str_repeat("-", 30)."+".str_repeat("-", 30)."+\r\n";
		return $result;
	}
	public function processModel() {
		$path = "App/{$this->model}.php";
		$this->bang->applyTemplate($this, "model", $path);
		$this->messages[] = "Model '{$this->model}' created at '{$path}'.";
	}
	public function processController() {
		$path = "App/Http/Controllers/{$this->controller}.php";
		$this->bang->applyTemplate($this, "controller", $path);
		$this->messages[] = "Controller '$this->controller' created at '$path'.";
	}
	public function processViews()
	{
		$path = "resources/views/{$this->sing}/index.blade.php";
		$this->bang->applyTemplate($this, "view_index", $path);
		$this->messages[] = "View '{$this->sing}.index' created at '$path'.";
	}

}
