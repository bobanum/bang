<?php
return <<<EOT
@component('{$obj->singular}.show.column', ['label'=>'{$foreign->singular}'])
{{{$obj->singularVar}->{$foreign->singular}->{$foreign->label}}}
@endcomponent
EOT;
