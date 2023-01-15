<?php
namespace Bang\Table;

trait Aliases {
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
	function get_label() {
		foreach ($this->_columns as $column) {
			if (strpos(strtolower($column->type), "text") !== false || strpos(strtolower($column->type), "char") !== false) {
				return $column->name;
			}
		}
		return "id";	//TODO Normalize
	}
}
