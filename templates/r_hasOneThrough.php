<?php
return <<<EOT
    /** */
    public function {$obj->singular}() {
        return \$this->hasOneThrough('App\\$obj->model', 'App\\$obj2->model');
    }
EOT;
