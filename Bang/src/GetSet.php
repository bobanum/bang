<?php
namespace Bang;
trait GetSet {
	function __get($name)
	{
		if(substr($name, -3) === "Var") {
			$name = substr($name, 0, -3);
			return '$'.$this->$name;
		}
		$get_name = "get_$name";
		if (method_exists($this, $get_name)) {
			return $this->$get_name();
		}
		throw new \Exception("Bad property name '$name'.");
	}

}