<?php
namespace Bang;

class Column {
	use GetSet;
    function get_references() {
        
    }

    function report_line($width = 65) {
        $result = "";
        $result .= "║ ";
        $result .= str_pad($this->name, $width-31, " ");
        $result .= "│ ";
        $result .= str_pad($this->type, 21, " ");
        $result .= "│ ";
        $result .= (!empty($this->pk)) ? "PK " : "   ";
        $result .= "║";
        return $result;
    }

}
