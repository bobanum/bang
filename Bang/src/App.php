<?php

namespace Bang;

use PDO;
use Exception;

error_reporting(E_ALL);
class App
{
	// use GetSet;
	use Phar;
	static protected $db_path;
	static protected $db;
	static protected $pdo;
	public $results = "..";
	static public function run($db_path)
	{
		self::$db_path = $db_path;
		if (false != (self::$db_path = realpath($db_path))) {
			self::$db_path = $db_path;
		} elseif (false != (self::$db_path = realpath(dirname($_SERVER['SCRIPT_FILENAME']) . "/" . $db_path))) {
			self::$db_path = $db_path;
		} else {
			throw new Exception("database '$db_path' not found");
		}
		self::$db = new Database(self::get_pdo());
		$messages[] = "\r\nSETTING CONFIGURATIONS\r\n═════════════════════════════════════════════════════════════════";
		self::setConfigs();
		$messages[] = "\r\nGLOBAL VIEWS\r\n═════════════════════════════════════════════════════════════════";
		self::processViews();
		$messages[] = "\r\nCOPYING ASSETS\r\n═════════════════════════════════════════════════════════════════";
		App::copyAssets();
		array_push($messages, ...self::$db->run());
		echo self::$db;
		return $messages;
	}
	/**
	 * GETTER Connects to the database and returns the PDO object (cached)
	 * Makes fetch return objects automatically
	 * @return \PDO Link to the database
	 */
	static function get_pdo()
	{
		if (!self::$pdo) {
			$pdo = new PDO("sqlite:" . self::$db_path);
			if ($pdo->errorInfo()[1]) {
				var_dump($pdo->errorInfo());
			}
			$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_CLASS);
			self::$pdo = $pdo;
		}
		return self::$pdo;
	}

	/**
	 * Recursively copies a directory inside another. Also works with files.
	 * @param string $src Source directory
	 * @param string $dst Destination directory
	 */
	static function copy_dir($src, $dst)
	{
		return; /////////////////////////////////////////////////////////
		if (is_dir($src)) {
			$dst .= "/" . basename($src);
			if (!is_dir($dst)) {
				mkdir($dst);
			}
		} else {
			copy($src, $dst);
			return;
		}

		$files = self::glob("$src/*");
		foreach ($files as $file) {
			if ((basename($file) === '.') || (basename($file) === '..')) {
				continue;
			}
			self::copy_dir($file, $dst . '/' . basename($file));
		}
	}

	/**
	 * A glob that works with phar
	 * @param  string $p Pattern
	 * @return array  Array of files matching pattern
	 */
	static function glob($pattern)
	{
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
	static function output_path($file = "")
	{
		//TODO Stop assuming db in database folder
		$result = dirname(self::$db_path);
		$result = dirname($result);
		$result = realpath($result);
		if ($file) {
			$result .= "/" . $file;
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
	static function applyTemplate($template, $obj, $path = null)
	{
		if (!file_exists($template)) {
			$template = self::template_path($template);
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
	 * Executes the copying of the assets
	 */
	static function copyAssets()
	{
		$messages[] = "• Adding 🗁'" . self::asset_path("css") . "'\r\n  to 🗁'" . self::output_path("public") . "'";
		self::copy_dir(self::asset_path("css"), self::output_path("public"));

		$messages[] = "• Adding 🗁'" . self::asset_path("images") . "'\r\n  to 🗁'" . self::output_path("public") . "'";
		self::copy_dir(self::asset_path("images"), self::output_path("public"));
		return $messages;
	}
	/**
	 * Executes the processing of the views
	 */
	static function processViews()
	{
		$messages = [];
		$views = self::template_path("view_layout_*");
		$views = self::glob($views);
		foreach ($views as $view) {
			$path = basename($view);
			$path = substr($path, 0, -4);
			$path = explode("_", $path);
			array_shift($path);
			$path = implode("/", $path);
			$path = "resources/views/{$path}.blade.php";
			$path = self::output_path($path);
			// $viewName = substr($view, strlen(__DIR__)+19, -4);
			// $viewPath = str_replace("_", "/", $viewName);
			// $path = "resources/views/{$viewPath}.blade.php";
			// $path = self::output_path($path);
			$messages[] = "• Creating file 🗎'{$path}'\r\n  with View 👁'layout {$view}.' from template.";
			self::applyTemplate($view, self::$db, $path);
		}
		return $messages;
	}

	/**
	 * Executes the changes to the settings
	 */
	static function setConfigs()
	{
		$messages = [];

		$messages[] = "• Nothing to do (yet)";
		// $messages[] = "• Setting database '".self::$db_path."'";
		// $config = file_get_contents(self::output_path("config/database.php"));
		// $config = str_replace("'default' => env('DB_CONNECTION', 'mysql')", "'default' => 'sqlite'", $config);
		// $config = str_replace("'database' => env('DB_DATABASE', database_path('database.sqlite'))", "'database' => '{${self::$db_path}}'", $config);
		// file_put_contents(self::output_path("config/database.php"), $config);
		return $messages;
	}
}
