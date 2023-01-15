<?php
return <<<EOT
@component('layout.column.show', ['label'=>'{$column->name}'])
{{\${$obj->singular}->{$column->name}}}
@endcomponent
EOT;
