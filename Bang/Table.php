<?php
namespace Bang;

use PDO;
use Exception;

class Table {
	use GetSet;
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
		$this->fetchColumns();
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
		if (!isset($this->columns[$t0->foreignKey]) || !isset($this->columns[$t1->foreignKey])) {
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
	function get_columns() {
		if (!$this->_columns) {
			$this->_columns = $this->fetchColumns();
		}
		return $this->_columns;
	}
	function fetchColumns() {
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
			//TODO Check pertinence
			foreach ($foreignKey as $name=>$info) {
				$result[$foreignKey->from]->$name = $info;
			}
		} 
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
	function get_foreignKey() {
		$result = $this->singular."_id";
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
	function get_fillableColumns() {
		$result = $this->columns;
		$result = array_filter($result, function ($col) { 
			return $col->name !== "id";
		});
		return $result;
	}
	function get_fillable() {
		$result = $this->fillableColumns;
		$result = array_map(function ($col) { return $col->name; }, $result);
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
	function get_singular() {
		$result = $this->normalized;
		if (substr($result, -1) === "s") {
			return substr($result, 0, -1);
		} else {
			return $result;
		}
	}
	function get_plural() {
		$result = $this->normalized;
		// echo $result;exit;
		if (substr($result, -1) === "s") {
			return $result;
		} else {
			return $result . "s";
		}
	}
	function get_views() {
		$result = $this->singular;
		return $result;
	}
	function get_show_columns() {
		$result = [];
		foreach($this->columns as $column) {
			if ($column->isForeign) {
				$result[] = $this->bang->applyTemplate("v_show_foreign.php", ['obj'=>$this, 'foreign'=>$column->foreignTable]);
			} else {
				$result[] = $this->bang->applyTemplate("v_index_list.php", ['obj'=>$this, 'column'=>$column]);
			}
		}
		return implode("\r\n",$result);
	}
	function get_show_many() {
		$result = [];
		foreach($this->hasMany as $table) {
			$result[] = $this->bang->applyTemplate("v_show_many.php", ['obj'=>$this, 'foreign'=>$table]);
		}
		return implode("\r\n",$result);
	}
	function get_form_columns() {
		$result = [];
		foreach($this->columns as $column) {
			$result[] = $this->bang->applyTemplate("v_form_list.php", ['obj'=>$this, 'column'=>$column]);
		}

		return implode("\r\n",$result);
	}
	function get_label() {
		// $result = array_values($this->columns)[2]->name; //TODO Find best column (1st text...)
		foreach ($this->columns as $column) {
			if (strpos(strtolower($column->type), "text") !== false || strpos(strtolower($column->type), "char") !== false) {
				return $column->name;
			}
		}
		return "id";
	}
	function get_relations() {
		$result = [];
		foreach ($this->belongsTo as $table) {
			$result[] = $this->bang->applyTemplate("r_belongsTo.php", $table);
		}
		foreach ($this->hasMany as $table) {
			$result[] = $this->bang->applyTemplate("r_hasMany.php", $table);
		}
		foreach ($this->belongsToMany as $table) {
			$result[] = $this->bang->applyTemplate("r_belongsToMany.php", $table);
		}
		foreach ($this->hasManyThrough as $tables) {
			$result[] = $this->bang->applyTemplate("r_hasManyThrough.php", array_combine(['obj', 'obj2'], $tables));
		}
		foreach ($this->hasOneThrough as $tables) {
			$result[] = $this->bang->applyTemplate("r_hasOneThrough.php", array_combine(['obj', 'obj2'], $tables));
		}
		return implode("\r\n", $result);
	}
	function get_rules() {		
		$columns = $this->fillableColumns;
		$result = [];
		foreach($columns as $col) {
			$result[] = '		$result[\''.$col->name.'\'] = \'required\';';
		}
		return implode("\r\n", $result);
	}
	function get_fakeColumns() {		
		$columns = $this->fillableColumns;
		$result = [];
		foreach($columns as $col) {
			$result[] = '		$result->'.$col->name.' = $faker->word;';
		}
		return implode("\r\n", $result);
	}
	function get_deleteForeign() {
		$tables = $this->bang->hasMany($this);
		$result = [];
		foreach($tables as $table) {
			$result[] = "\\App\\$table->model::where('{$this->singular}_id', {$this->singularVar}->id)->delete();";
		}
		return implode("\r\n", $result);
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
