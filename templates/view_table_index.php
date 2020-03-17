<?php
return <<<EOT
@extends('layout.index')
@section('content')
<h1>Liste des {$obj->name}</h1>
@if(count($obj->pluralVar) > 0)
<div class="$obj->plural">
	@foreach($obj->pluralVar as $obj->singularVar)
	<div><a href="{{action('$obj->controller@show', $obj->singularVar)}}">{{{$obj->singularVar}->{$obj->label}}}</a></div>
	@endforeach
</div>
@else
<div>Il n'y a aucun {$obj->name}.</div>
@endif
@endsection
EOT;