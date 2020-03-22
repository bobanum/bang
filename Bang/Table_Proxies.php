<?php
namespace Bang;

trait Table_Proxies {
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
		foreach($this->_columns as $column) {
			if ($column->dflt_value !== null) {
				$result[] = "\t\t'{$column->name}' => {$column->dflt_value},";
			}
		}
		$result[] = "\t];";	
		return implode("\r\n", $result);
	}
	function get_fillable() {
		$result = $this->fillableColumns;
		$result = array_map(function ($col) { return $col->name; }, $result);
		$result = implode("', '", $result);
		$result = "\tprotected \$fillable = ['$result'];\r\n";
		return $result;
	}
	function get_show_columns() {
		$result = [];
		foreach($this->_columns as $name=>$column) {
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
		foreach($this->belongsToMany as $table) {
			$result[] = $this->bang->applyTemplate("v_show_many.php", ['obj'=>$this, 'foreign'=>$table]);
		}
		// foreach($this->hasManyThrough as $table) {
		// 	$result[] = $this->bang->applyTemplate("v_show_many.php", ['obj'=>$this, 'foreign'=>$table]);
		// }
		return implode("\r\n",$result);
	}
	function get_form_columns() {
		$result = [];
		foreach($this->_columns as $column) {
			$result[] = $this->bang->applyTemplate("v_form_list.php", ['obj'=>$this, 'column'=>$column]);
		}

		return implode("\r\n",$result);
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
}
