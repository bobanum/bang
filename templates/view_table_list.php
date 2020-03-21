<?php
return <<<EOT
@if(count($obj->pluralVar) > 0)
<ul class="$obj->plural">
	@foreach($obj->pluralVar as $obj->singularVar)
    @include('{$obj->singular}.list.row')
	@endforeach
</ul>
@else
<div>No {$obj->name}.</div>
@endif
EOT;