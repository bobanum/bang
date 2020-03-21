<?php
return <<<EOT
    /** */
    public function {$obj->plural}() {
        return \$this->belongsTo('App\\$obj->model');
    }
EOT;
