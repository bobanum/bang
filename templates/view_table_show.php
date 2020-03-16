<?php
return <<<EOT
<h1>{{\${$table->sing}->{$table->label}}}</h1>
<div class="columns">
	{$table->show_columns}
</div>
EOT;