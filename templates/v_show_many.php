<?php
return <<<EOT
@component('layout.column.show', ['label'=>'{$foreign->name}'])
@include('{$foreign->singular}.list', ['{$foreign->plural}' => {$obj->singularVar}->{$foreign->plural}])
@endcomponent
EOT;
