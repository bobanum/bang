<?php
return <<<EOT
    <li><a href="{{action('{$obj->controller}@index')}}">{$obj->plural}</a></li>
EOT;
