<?php
return <<<EOT
    /** */
    public function {$obj->plural}() {
        return \$this->hasManyThrough('App\\$obj->model', 'App\\$obj2->model');
    }
EOT;
