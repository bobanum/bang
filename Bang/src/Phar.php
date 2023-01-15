<?php
namespace Bang;
trait Phar {
	/**
	 * Returns a path to a file inside the phar file
	 * @param  string [$file=""] Optional file name or path to append
	 * @return string Absolute path
	 */
	static function phar_path($file="") {
		$result = self::base_path();
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
	static function base_path($file = "") {
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
	static function template_path($file = "") {
		$result = self::base_path("templates");
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
	static function asset_path($file = "") {
		$result = self::base_path("assets");
		if ($file) {
			$result .= "/$file";
		}
		return $result;
	}
}