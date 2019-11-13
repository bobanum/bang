<?php
namespace Bang;
use PDO;
error_reporting(E_ALL);
class Bang {
	static public $exclude = ['sqlite_sequence'];
	static public $db;
	static public $messages = [];
	static public function go($db) {
		self::$db = realpath($db);
		$pdo = new PDO("sqlite:$db");
		$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_CLASS);
		$stmt = $pdo->prepare("SELECT * FROM sqlite_master WHERE type='table'");
		$stmt->execute();
		$tables = $stmt->fetchAll(PDO::FETCH_CLASS, "Bang\Table");
		$tables = array_filter($tables, function ($table) { return !in_array($table->name, self::$exclude);});
		foreach ($tables as $table) {
			$name = $table->name;
			self::$messages[] = "Processing Table '$table->name'.";

			$stmt = $pdo->prepare("PRAGMA table_info({$name})");
			$stmt->execute();
			$table->columns = $stmt->fetchAll(PDO::FETCH_CLASS, "Bang\Column");
			self::processModel($table);
			self::processController($table);
		}
		echo implode("\r\n", self::$messages);
	}
	static public function processModel(Table $table)
	{
		include "model.php";
		$path = dirname(self::$db)."\App";
		if (!file_exists($path)) {
			mkdir($path, 0777, true);
		}
		$path .= "\\$table->model.php";
		self::$messages[] = "Model '$table->model' created at '$path'.";
		file_put_contents($path, $tmpl);
	}
	static public function processController(Table $table)
	{
		include "controller.php";
		$path = dirname(self::$db)."\App\Http\Controllers";
		if (!file_exists($path)) {
			mkdir($path, 0777, true);
		}
		$path .= "\\$table->controller.php";
		self::$messages[] = "Model '$table->controller' created at '$path'.";
		file_put_contents($path, $tmpl);
	}
}