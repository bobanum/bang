<?php
return <<<EOT
<div class="columns">
@csrf	
{$obj->form_columns}
</div>
EOT;