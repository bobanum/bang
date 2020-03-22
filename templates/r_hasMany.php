<?php
return <<<EOT
    /** */
    public function {$obj->plural}() {
        return \$this->hasMany('App\\$obj->model');
    }
EOT;
