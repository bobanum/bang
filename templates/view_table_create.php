<?php
return <<<EOT
<h1>{{\${$obj->singular}->{$obj->label}}}</h1>
<div class="columns">
	{$obj->show_columns}
</div>
EOT;