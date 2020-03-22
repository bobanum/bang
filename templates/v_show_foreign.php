<?php
return <<<EOT
@component('layout.column.show', ['label'=>'{$foreign->singular}'])
<a href="{{action('{$foreign->controller}@show', {$obj->singularVar}->{$foreign->singular})}}">{{{$obj->singularVar}->{$foreign->singular}->{$foreign->label}}}</a>
@endcomponent
EOT;
