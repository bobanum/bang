<?php
return <<<EOT
<li>
<a href="{{action('$obj->controller@show', $obj->singularVar)}}">{{{$obj->singularVar}->{$obj->label}}}</a>
<span class="actions">
<a href="{{action('$obj->controller@show', $obj->singularVar)}}">View</a>
<a href="{{action('$obj->controller@edit', $obj->singularVar)}}">Edit</a>
<a href="{{action('$obj->controller@delete', $obj->singularVar)}}">Delete</a>
</span>
</li>
EOT;