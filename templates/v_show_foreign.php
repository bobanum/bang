<?php
return <<<EOT
@component('layout.column.show', ['label'=>'{$foreign->singular}'])
{{{$obj->singularVar}->{$foreign->singular}->{$foreign->label}}}
@endcomponent
EOT;
