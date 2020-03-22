<?php
namespace Bang;
use PDO;
use Exception;
error_reporting(E_ALL);
class Bang {
	use GetSet;
	public $exclude = ['sqlite_sequence'];
	protected $db;
	public $messages = [];
	public $results = "..";
	protected $_pdo = null;
	protected $_tables = null;
	/**
	 * Constructor
	 * @private
	 * @param string $db The path to the database
	 */
	function __construct($db) {
		if (false != ($this->db = realpath($db))) {
			$this->db = $db;
		} elseif (false != ($this->db = realpath(dirname($_SERVER['SCRIPT_FILENAME'])."/".$db))) {
			$this->db = $db;
		} else {
			throw new Exception("database '$db' not found");
		}
		$this->analyze();
	}
	function analyze() {
		$this->_tables = $this->fetchTables();
		Table::analyze($this->_tables);
		// Check for belongsTo and hasMany
		// foreach($this->_tables as $table) {
		// 	$table->analyze();
		// }
		// Check for belongsTo
		// foreach($this->tables as $table) {
		// 	$table->analyzeBelongsToMany();
		// }
		// Check for belongsToMany
		// foreach($this->tables as $table) {
		// 	$table->analyzeBelongsToMany();
		// }
		// Check for hasManyThrough
		// foreach($this->tables as $table) {
		// 	$table->analyzeHasManyThrough();
		// }
		return;
	}
	/**
	 * GETTER Fetches all the tables of the database. (kept cached)
	 * @return array The tables of the database
	 * @deprecated
	 * @todo Search and destroy
	 */
	function get_tables() {
		if (empty($this->_tables)) {
			$this->_tables = $this->fetchTables();
		}
		return $this->_tables;
	}
	/**
	 * GETTER Fetches all the tables of the database. (kept cached)
	 * @return array The tables of the database
	 */
	function fetchTables() {
		$pdo = $this->pdo;
		$stmt = $pdo->prepare("SELECT * FROM sqlite_master WHERE type='table'");	//TODO Indexes
		$stmt->execute();
		$tables = $stmt->fetchAll(PDO::FETCH_CLASS, "Bang\Table");
		$tables = array_filter($tables, function ($table) { return !in_array($table->name, $this->exclude);});
		$result = [];
		foreach ($tables as $table) {
			$result[$table->name] = $table;
			$table->bang = $this;
		} 
		return $result;
	}
	function getTable($name) {
		if (isset($this->_tables[$name])) {
			return $this->_tables[$name];
		}
		foreach($this->_tables as $table) {
			if (strtolower($name) === $table->singular) {
				return $table;
			}
		}
	}
	function get_junctionTables() {
		$tables = $this->tables;
		$result = [];
		foreach($tables as $key=>$table) {
			$subs = explode("_", $key);
			if (count($subs) !== 2) {
				continue;
			}
			if ($subs[1] <= $subs[0]) {
				continue;
			}
			$subs[0].="s";
			if (!isset($this->tables[$subs[0]])) {	//TODO better plural
				continue;
			}
			$subs[1].="s";
			if (!isset($this->tables[$subs[1]])) {	//TODO better plural
				continue;
			}
			//TODO Check foreign keys?
			$result[$key] = $subs;
		}
		return $this->_tables;
	}
	
	/**
	 * GETTER Connects to the database and returns the PDO object (cached)
	 * Makes fetch return objects automatically
	 * @return \PDO Link to the database
	 */
	function get_pdo() {
		if (!$this->_pdo) {
			$pdo = new PDO("sqlite:{$this->db}");
			if ($pdo->errorInfo()[1]) {
				var_dump($pdo->errorInfo());
			}
			$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_CLASS);
			$this->_pdo = $pdo;
		}
		return $this->_pdo;
	}
	
	/**
	 * Returns the prepared statement of given SQL
	 * @param  string        $sql SQL to prepare
	 * @return \PDOStatement The prepared statement
	 */
	function prepare($sql) {
		$pdo = $this->pdo;
		$stmt = $pdo->prepare($sql);
		return $stmt;
	}
	
	/**
	 * Execute given SQL with optional data
	 * @param  string        $sql       SQL to execute
	 * @param  array         [$data=[]] Array ot data to fill the '?' or :column
	 * @return \PDOStatement The PDO statement ready to fetch
	 */
	function execute($sql, $data=[]) {
		$pdo = $this->pdo;
		$stmt = $pdo->prepare($sql);
		$stmt->execute($data);
		return $stmt;
	}
	
	/**
	 * Returns table for each hasMany clauses linked to given table
	 * @param  Table     $table [[Description]]
	 * @return [Table[]] [[Description]]
	 */
	function hasMany(Table $table) {
		$result = [];
		foreach($this->tables as $table2) {
			// foreach($table2->foreignKeys as $foreignKey) {
			// 	if ($foreignKey->table === $table->name) {
			// 		$result[$table2->name] = $table2;
			// 		break;
			// 	}
			// }
		}
		return $result;
	}
	
	/**
	 * Recursively copies a directory inside another. Also works with files.
	 * @param string $src Source directory
	 * @param string $dst Destination directory
	 */
	function copy_dir($src, $dst) {
		if (is_dir($src)) {
			$dst .= "/".basename($src);
			if (!is_dir($dst)) {
				mkdir($dst);
			}
		} else {
			copy($src, $dst);
			return;
		}
		
		$files = $this->glob("$src/*");		
		foreach($files as $file) {
			if ((basename($file) === '.' ) || (basename($file) === '..' )) {
				continue;
			}
			$this->copy_dir($file, $dst.'/'.basename($file));
		}
	}
	
	/**
	 * A glob that works with phar
	 * @param  string $p Pattern
	 * @return array  Array of files matching pattern
	 */
	function glob($pattern) {
		$result = [];
		$directory = dirname($pattern);
		$dh = opendir($directory);
		while (false !== ($file = readdir($dh))) {
			if (fnmatch($pattern, "$directory/$file")) {
				$result[] = "$directory/$file";
			}
		}
		return $result;
	}

	/**
	 * Returns the path to where the files are ultimately created (eg : root of laravel folder)
	 * @param  string [$file=""] Optional file name or path to append
	 * @return string Absolute path
	 */
	function output_path($file="") {
		//TODO Stop assuming db in database folder
		$result = dirname($this->db);
		$result = dirname($result);
		$result = realpath($result);
		if ($file) {
			$result .= "/".$file;
		}
		return $result;
	}
	
	/**
	 * Returns a path to a file inside the phar file
	 * @param  string [$file=""] Optional file name or path to append
	 * @return string Absolute path
	 */
	function phar_path($file="") {
		$result = $this->base_path();
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
	
	/**
	 * Returns a path to a file inside the phar file
	 * @param  string [$file=""] Optional file name or path to append
	 * @return string Absolute path
	 */
	function base_path($file = "") {
		$result = str_replace("\\", "/", dirname(__DIR__));
		if ($file) {
			$result .= "/$file";
		}
		return $result;
	}
	
	/**
	 * Returns a path to the templates folder inside the phar file
	 * @param  string [$file=""] Optional file name or path to append
	 * @return string Absolute path
	 */
	function template_path($file = "") {
		$result = $this->base_path("templates");
		if ($file) {
			$result .= "/$file";
		}
		return $result;
	}
	
	/**
	 * Returns a path to a the assets inside the phar file
	 * @param  string [$file=""] Optional file name or path to append
	 * @return string Absolute path
	 */
	function asset_path($file = "") {
		$result = $this->base_path("assets");
		if ($file) {
			$result .= "/$file";
		}
		return $result;
	}
	
	/**
	 * Applies given template using given object and can save the result.
	 * @param  string $template    The file path of template to use
	 * @param  object $obj         Data to use inside the template
	 * @param  string [$path=null] Where to put the result
	 * @return string Resulting code from template
	 */
	function applyTemplate($template, $obj, $path=null) {
		if (!file_exists($template)) {

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

	/**
	 * Executes the commands
	 */
	function go() {
		$this->messages[] = "\r\nSETTING CONFIGURATIONS\r\n═════════════════════════════════════════════════════════════════";
		$this->setConfigs();
		$this->messages[] = "\r\nGLOBAL VIEWS\r\n═════════════════════════════════════════════════════════════════";
		$this->processViews();
		$this->messages[] = "\r\nCOPYING ASSETS\r\n═════════════════════════════════════════════════════════════════";
		$this->copyAssets();
		$this->messages[] = "\r\nMANAGING TABLES\r\n═════════════════════════════════════════════════════════════════";
		foreach ($this->tables as $table) {
			$table->go();
			$this->messages = array_merge($this->messages, $table->messages);
		}
		echo implode("\r\n", $this->messages);
	}

	/**
	 * Executes the copying of the assets
	 */
	function copyAssets() {
		$this->messages[] = "• Adding 🗁'{$this->asset_path("css")}'\r\n  to 🗁'{$this->output_path("public")}'";
		$this->copy_dir($this->asset_path("css"), $this->output_path("public"));
		
		$this->messages[] = "• Adding 🗁'{$this->asset_path("images")}'\r\n  to 🗁'{$this->output_path("public")}'";
		$this->copy_dir($this->asset_path("images"), $this->output_path("public"));
	}
	function get_nav_items() {
		$result = [];
		foreach($this->_tables as $table) {
			$result[] = $this->applyTemplate("v_layout_nav_item.php", ['obj'=>$table]);
		}
		return implode("\r\n",$result);
	}
	/**
	 * Executes the processing of the views
	 */
	function processViews()
	{
		$views = $this->template_path("view_layout_*");
		$views = $this->glob($views);
		foreach ($views as $view) {
			$path = basename($view);
			$path = substr($path, 0, -4);
			$path = explode("_", $path);
			array_shift($path);
			$path = implode("/", $path);
			$path = "resources/views/{$path}.blade.php";
			$path = $this->output_path($path);
			// $viewName = substr($view, strlen(__DIR__)+19, -4);
			// $viewPath = str_replace("_", "/", $viewName);
			// $path = "resources/views/{$viewPath}.blade.php";
			// $path = $this->output_path($path);
			$this->messages[] = "• Creating file 🗎'{$path}'\r\n  with View 👁'layout {$view}.' from template.";
			$this->applyTemplate($view, $this, $path);

		}
	}
	
	/**
	 * Executes the changes to the settings
	 */
	function setConfigs() {
		$this->messages[] = "• Setting database '{$this->db}'";
		$config = file_get_contents($this->output_path("config/database.php"));
		$config = str_replace("'default' => env('DB_CONNECTION', 'mysql')", "'default' => 'sqlite'", $config);
		$config = str_replace("'database' => env('DB_DATABASE', database_path('database.sqlite'))", "'database' => '{$this->db}'", $config);
		file_put_contents($this->output_path("config/database.php"), $config);
	}
}