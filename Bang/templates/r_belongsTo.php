<?php
return <<<EOT
    /** */
    public function {$obj->singular}() {
        return \$this->belongsTo('App\\$obj->model');
    }
EOT;
