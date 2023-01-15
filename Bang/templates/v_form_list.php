<?php
return <<<EOT
@component('layout.column.form', ['label'=>'{$column->name}'])
<input type="text" id="{$column->name}" name="{$column->name}" value="{{\${$obj->singular}->{$column->name}}}" />
@endcomponent
EOT;
