<?php
return <<<EOT
    /** */
    public function {$obj->plural}() {
        return \$this->belongsToMany('App\\$obj->model');
    }
EOT;
