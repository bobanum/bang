<?php
return <<<EOT
@component('{$table->sing}.show.column', ['label'=>'{$column->name}'])
{{\${$table->sing}->{$column->name}}}
@endcomponent
EOT;
