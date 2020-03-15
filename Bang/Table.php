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
		$this->messages[] = "• Processing Table '$name'.";
		$this->messages[] = $this->report();
		$this->processModel();
		$this->processController();
		$this->processViews();
	}
	function report($width=65) {
		//║╗╝╚╔═╣╠╬╪╤╧
		$hr = "╔".str_repeat("═", $width-30)."";
		$hr .= "╤".str_repeat("═", 22)."";
		$hr .= "╤".str_repeat("═", 4)."";
		$hr .= "╗";
		$result[] = $hr;
		$h = "";
		$h .= "║ ".str_pad("COLUMN NAME", $width-31, " ");
		$h .= "│ ".str_pad("TYPE", 21, " ");
		$h .= "│ PK ║";
		$result[] = $h;
		$hr = "╠".str_repeat("═", $width-30)."";
		$hr .= "╪".str_repeat("═", 22)."";
		$hr .= "╪".str_repeat("═", 4)."";
		$hr .= "╣";
		$result[] = $hr;
		foreach ($this->columns as $column) {
			$result[] = $column->report_line();
		}
		$hr = "╚".str_repeat("═", $width-30)."";
		$hr .= "╧".str_repeat("═", 22)."";
		$hr .= "╧".str_repeat("═", 4)."";
		$hr .= "╝";
		$result[] = $hr;
		$result = implode("\r\n", $result);
		return $result;
	}
	public function processModel() {
		$path = "App/{$this->model}.php";
		$this->bang->applyTemplate($this, "model", $path);
		$this->messages[] = "• Creating file 🗎'{$path}' with Model 🖼'{$this->model}' from template.";
	}
	public function processController() {
		$path = "App/Http/Controllers/{$this->controller}.php";
		$this->bang->applyTemplate($this, "controller", $path);
		$this->messages[] = "• Creating file 🗎'{$path}' with Controller 🖰'$this->controller' from template.";
	}
	public function processViews()
	{
		$path = "resources/views/{$this->sing}/index.blade.php";
		$this->bang->applyTemplate($this, "view_index", $path);
		$this->messages[] = "• Creating file 🗎'{$path}' with View 👁'{$this->sing}.index' from template.";
	}

}
