<?php
namespace Bang;

use PDO;
use Exception;

class Table {
	use GetSet;
	use Table_Aliases;
	use Table_Proxies;
	public $_columns = [];
	public $belongsTo = [];
	public $hasMany = [];
	public $belongsToMany = [];
	public $hasManyThrough = [];
	public $hasOneThrough = [];
	public $messages = [];
	public $bang = null;
	function __construct() {
		
	}
	function analyze() {
		// Fetch all the table's columns
		$stmt = $this->bang->execute("PRAGMA table_info({$this->name})");
		$columns = $stmt->fetchAll(PDO::FETCH_CLASS, "Bang\Column");
		$this->_columns = [];
		foreach ($columns as $column) {
			$this->_columns[$column->name] = $column;
			$column->table = $this;
		}


		// Add informations about foreign keys
		$stmt = $this->bang->execute("PRAGMA foreign_key_list({$this->name})");
		$foreignKeys = $stmt->fetchAll(PDO::FETCH_OBJ);
		foreach ($foreignKeys as $foreignKey) {
			$foreignTable = $this->bang->getTable($foreignKey->table);
			unset($foreignKey->table);
			// $this->belongsTo[$foreignTable->name] = $foreignTable;
			$this->addBelongsTo($foreignTable, true);
			// $foreignTable->hasMany[$this->name] = $this;
			$foreignKey->foreignTable = $foreignTable;
			foreach ($foreignKey as $name=>$info) {
				$this->_columns[$foreignKey->from]->$name = $info;
			}
		} 
	}
	function analyzeBelongsToMany() {
		$tables = array_values($this->belongsTo);
		for($i = 0, $n = count($tables) - 1; $i < $n; $i += 1) {
			for($j = $i + 1, $m = count($tables); $j < $m; $j += 1) {
				$tables[$i]->belongsToMany[$tables[$j]->name] = $tables[$j];
				$tables[$j]->belongsToMany[$tables[$i]->name] = $tables[$i];
			}
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
	function get_isJunctionTable() {
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
		$t0 = $this->bang->getTable($subs[0]);
		$t1 = $this->bang->getTable($subs[1]);
		if (!$t0 || !$t1) {
			return false;
		}
		// Do we have a foreign key for each table
		if (!isset($this->_columns[$t0->foreignKey]) || !isset($this->_columns[$t1->foreignKey])) {
			return false;
		}
		return true;
	}
	function addHasMany($table, $complete = true) {
		if ($table->isJunctionTable) {
			return;
		}
		if (isset($this->hasMany[$table->name])) {
			return;
		}
		$this->hasMany[$table->name] = $table;
		if ($complete) {
			$table->addBelongsTo($this, false);
		}
	}
	function addBelongsTo($table, $complete = true) {
		if (isset($this->belongsTo[$table->name])) {
			return;
		}
		$this->belongsTo[$table->name] = $table;
		if ($complete) {
			$table->addHasMany($this, false);
		}
	}
	function get_fillableColumns() {
		$result = array_filter($this->_columns, function ($col) { 
			return $col->name !== "id";
		});
		return $result;
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
		foreach ($this->_columns as $column) {
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
	function go() {
		$name = $this->name;
		$this->messages[] = "• Processing Table '$name'.";
		// $this->messages[] = $this->report();
		$this->processRoutes();
		$this->processModel();
		$this->processController();
		$this->processViews();
	}
	public function processRoutes() {
		$path = "routes/web.php";
		$path = $this->bang->output_path($path);
		$web = file_get_contents($path);
		//Remove old routes
		$head = "\r\n//route for {$this->model}\r\n";
		$foot = "\r\n//endroute\r\n";
		$pattern = '#'.preg_quote($head).'.*'.preg_quote($foot).'#ms';
		$web = preg_replace($pattern, "", $web);
		//Add new routes
		$routes = $this->bang->applyTemplate("routes_web.php", $this);
		$routes = $head.$routes.$foot;
		$web .= $routes;
		file_put_contents($path, $web);
		$this->messages[] = "• Creating routes un file 🗎'{$path}'\r\n  using Model 🖼'{$this->model}' from template.";
	}
	public function processModel() {
		$path = "app/{$this->model}.php";
		$path = $this->bang->output_path($path);
		$this->bang->applyTemplate("model.php", $this, $path);
		$this->messages[] = "• Creating file 🗎'{$path}'\r\n  with Model 🖼'{$this->model}' from template.";
	}
	public function processController() {
		$path = "app/Http/Controllers/{$this->controller}.php";
		$path = $this->bang->output_path($path);
		$this->bang->applyTemplate("controller.php", $this, $path);
		$this->messages[] = "• Creating file 🗎'{$path}'\r\n  with Controller 🖰'$this->controller' from template.";
	}
	public function processViews()
	{
		$views = $this->bang->template_path("view_table_*");
		$views = $this->bang->glob($views);
		foreach ($views as $view) {
			$path = basename($view);
			$path = substr($path, 0, -4);
			$path = explode("_", $path);
			array_shift($path);
			$path[0] = $this->singular;
			$path = implode("/", $path);
			$path = "resources/views/{$path}.blade.php";
			$path = $this->bang->output_path($path);
			$this->bang->applyTemplate($view, $this, $path);
			$this->messages[] = "• Creating file 🗎'{$path}'\r\n  with View 👁'{$this->singular}.index' from template.";

		}
	}

}
