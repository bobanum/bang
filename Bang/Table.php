<?php
namespace Bang;

use Exception;

class Table {
	function __construct()
	{
		
	}
	function __get($name)
	{
		$get_name = "get_$name";
		if (method_exists($this, $get_name)) {
			return $this->$get_name();
		}
		throw new Exception("Bad property name '$name'.");
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
	function get_normalizedVar() {
		$result = explode("_", $this->name);
		$result = array_map('ucfirst', $result);
		$result = implode("", $result);
		return lcfirst($result);
	}
	function get_sing() {
		$result = $this->normalizedVar;
		if (substr($result, -1) === "s") {
			return substr($result, 0, -1);
		} else {
			return $result;
		}
	}
	function get_plur() {
		$result = $this->normalizedVar;
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
}
