<?php
namespace Bang;

class Column {
    use GetSet;
    public $name;
    function get_references() {
        
    }
    function get_isPrimary() {
        return $this->pk === "1";
    }
    function get_isForeign() {
        return substr($this->name, -3) === "_id";
    }
    // function analyze() {
    //     if ($this->isForeign) {
    //         var_dump("analyze column");
    //     }
    // }
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
