<?php
return <<<EOT
@component('{$obj->singular}.show.column', ['label'=>'{$column->name}'])
{{\${$obj->singular}->{$column->name}}}
@endcomponent
EOT;
