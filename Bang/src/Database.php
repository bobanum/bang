<?php

namespace Bang;

use PDO;
use Exception;

error_reporting(E_ALL);
class Database
{
	use GetSet;
	public $exclude = ['sqlite_sequence'];
	public $results = "..";
	protected $_pdo = null;
	protected $_tables = null;
	/**
	 * Constructor
	 * @private
	 * @param string $db The path to the database
	 */
	function __construct($pdo)
	{
		$this->_pdo = $pdo;
		$this->analyze();
	}
	/**
	 * Executes the commands
	 */
	function run()
	{
		$messages = [];
		return $messages;
		$messages[] = "\r\nMANAGING TABLES\r\n═════════════════════════════════════════════════════════════════";
		foreach ($this->tables as $table) {
			$table->go();
			$messages = array_merge($messages, $table->messages);
		}
		echo implode("\r\n", $messages);
	}
	function __toString()
	{
		$result = [];
		$result[] = '$this->';
		return implode("\r\n", $result);
	}
	function get_pdo()
	{
		return $this->_pdo;
	}
	/**
	 * Returns the prepared statement of given SQL
	 * @param  string        $sql SQL to prepare
	 * @return \PDOStatement The prepared statement
	 */
	function prepare($sql)
	{
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
	function execute($sql, $data = [])
	{
		$pdo = $this->pdo;
		$stmt = $pdo->prepare($sql);
		$stmt->execute($data);
		return $stmt;
	}

	function analyze()
	{
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
	function get_tables()
	{
		if (empty($this->_tables)) {
			$this->_tables = $this->fetchTables();
		}
		return $this->_tables;
	}
	/**
	 * GETTER Fetches all the tables of the database. (kept cached)
	 * @return array The tables of the database
	 */
	function fetchTables()
	{
		// $pdo = $this->pdo;
		// $stmt = $pdo->prepare("SELECT * FROM sqlite_master WHERE type='table'");	//TODO Indexes
		// $stmt->execute();
		// $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, Table::class, [$this]);

		$tables = Table::fetch($this);
		$tables = array_filter($tables, function ($table) {
			return !in_array($table->name, $this->exclude);
		});
		$names = array_map(fn ($table) => $table->name, $tables);
		$result = array_combine($names, $tables);
		return $result;
	}
	function getTable($name)
	{
		if (isset($this->_tables[$name])) {
			return $this->_tables[$name];
		}
		foreach ($this->_tables as $table) {
			if (strtolower($name) === $table->singular) {
				return $table;
			}
		}
	}
	function get_junctionTables()
	{
		$tables = $this->tables;
		$result = [];
		foreach ($tables as $key => $table) {
			$subs = explode("_", $key);
			if (count($subs) !== 2) {
				continue;
			}
			if ($subs[1] <= $subs[0]) {
				continue;
			}
			$subs[0] .= "s";
			if (!isset($this->tables[$subs[0]])) {	//TODO better plural
				continue;
			}
			$subs[1] .= "s";
			if (!isset($this->tables[$subs[1]])) {	//TODO better plural
				continue;
			}
			//TODO Check foreign keys?
			$result[$key] = $subs;
		}
		return $this->_tables;
	}

	/**
	 * Returns table for each hasMany clauses linked to given table
	 * @param  Table     $table [[Description]]
	 * @return [Table[]] [[Description]]
	 */
	function hasMany(Table $table)
	{
		$result = [];
		foreach ($this->tables as $table2) {
			// foreach($table2->foreignKeys as $foreignKey) {
			// 	if ($foreignKey->table === $table->name) {
			// 		$result[$table2->name] = $table2;
			// 		break;
			// 	}
			// }
		}
		return $result;
	}

	function get_nav_items()
	{
		$result = [];
		foreach ($this->_tables as $table) {
			$result[] = App::applyTemplate("v_layout_nav_item.php", ['obj' => $table]);
		}
		return implode("\r\n", $result);
	}
}
