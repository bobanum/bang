<?php
return <<<EOT
<h1>Create {$obj->label}</h1>
@include('{$obj->singular}.form', ['{$obj->singular}'=>\${$obj->singular}, 'verb'=>'post'])
EOT;