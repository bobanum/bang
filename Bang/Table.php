<?php
namespace Bang;

use PDO;
use Exception;

class Table {
	use GetSet;
	public $_columns = null;
	public $_foreignKeys = null;
	public $messages = [];
	public $bang = null;
	function __construct()
	{
		
	}
	function get_columns() {
		if (!$this->_columns) {
			$stmt = $this->bang->execute("PRAGMA table_info({$this->name})");
			$columns = $stmt->fetchAll(PDO::FETCH_CLASS, "Bang\Column");
			foreach ($columns as $column) {
				$this->_columns[$column->name] = $column;
				$column->table = $this;
			} 
		}
		return $this->_columns;
	}
	function get_foreignKeys() {
		if (!$this->_foreignKeys) {
			$stmt = $this->bang->execute("PRAGMA foreign_key_list({$this->name})");
			$foreignKeys = $stmt->fetchAll(PDO::FETCH_OBJ);
			$this->_foreignKeys = [];
			foreach ($foreignKeys as $foreignKey) {
				$this->_foreignKeys[$foreignKey->from] = $foreignKey;
				$foreignKey->tableObj = $this;
			} 
		}
		return $this->_foreignKeys;
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
			return "\tprotected \$table = '$this->name';\r\n";
		}
	}
	function get_defaultValues() {
		$result = [];
		$result[] = "\tprotected \$attributes = [";
		foreach($this->columns as $column) {
			if ($column->dflt_value !== null) {
				$result[] = "\t\t'{$column->name}' => {$column->dflt_value},";
			}
		}
		$result[] = "\t];";	
		return implode("\r\n", $result);
	}
	function get_fillable() {
		$result = $this->columns;
		$result = array_map(function ($col) { return $col->name; }, $result);
		$result = array_filter($result, function ($col) { return $col !== "id"; });
		$result = implode("', '", $result);
		$result = "\tprotected \$fillable = ['$result'];\r\n";
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
		$result = array_values($this->columns)[2]->name; //TODO Find best column (1st text...)
		return $result;
	}
	function get_hasMany() {
		$result = [];
		$tables = $this->bang->hasMany($this->name);
		foreach ($tables as $table) {
			$result[] = "\t/** */";
			$result[] = "\tpublic function {$table->sing}() {";
			$result[] = "\t\treturn \$this->belongsTo('App\\$table->model');";
			$result[] = "\t}";	
		}
		return implode("\r\n", $result);
	}
	function get_belongsTo() {
		$result = [];
		foreach($this->foreignKeys as $foreignKey) {
			$foreignTable = $this->bang->tables[$foreignKey->table];
			$result[] = "\t/** */";
			$result[] = "\tpublic function {$foreignTable->sing}() {";
			$result[] = "\t\treturn \$this->belongsTo('App\\$foreignTable->model');";
			$result[] = "\t}";
		}
		return implode("\r\n", $result);
	}
	function get_belongsToMany() {
		$result = "";
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
		$this->messages[] = "• Creating file 🗎'{$path}'\r\n  with Model 🖼'{$this->model}' from template.";
	}
	public function processController() {
		$path = "App/Http/Controllers/{$this->controller}.php";
		$this->bang->applyTemplate($this, "controller", $path);
		$this->messages[] = "• Creating file 🗎'{$path}'\r\n  with Controller 🖰'$this->controller' from template.";
	}
	public function processViews()
	{
		$path = "resources/views/{$this->sing}/index.blade.php";
		$this->bang->applyTemplate($this, "view_index", $path);
		$this->messages[] = "• Creating file 🗎'{$path}'\r\n  with View 👁'{$this->sing}.index' from template.";
	}

}
