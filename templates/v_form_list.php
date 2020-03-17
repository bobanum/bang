<?php
return <<<EOT
@component('{$obj->singular}.form.column', ['label'=>'{$column->name}'])
<input type="text" id="{$column->name}" name="{$column->name}" value="{{\${$obj->singular}->{$column->name}}}" />
@endcomponent
EOT;
