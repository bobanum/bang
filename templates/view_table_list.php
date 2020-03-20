<?php
return <<<EOT
@if(count($obj->pluralVar) > 0)
<div class="$obj->plural">
	@foreach($obj->pluralVar as $obj->singularVar)
    @include('{$obj->singular}.list.row')
	@endforeach
</div>
@else
<div>No {$obj->name}.</div>
@endif
EOT;