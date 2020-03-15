<?php
return <<<EOT
<h1>{{\${$table->sing}->{$table->label}}}</h1>
<div class="columns">
	{$table->show_columns}
	@component({$table->sing}.show.column, ['label'=>'nom'])
	{{\${$table->sing}->nom}}
	@endcomponent
	@component({$table->sing}.show.column, ['label'=>'auperficie'])
	{{\${$table->sing}->superficie}}
	@endcomponent
</div>
EOT;