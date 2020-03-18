<?php
return <<<EOT
@extends('layout.index', ['title'=>'List of $obj->plural'])
@section('content')
<h1>Liste des {$obj->name}</h1>
@if(count($obj->pluralVar) > 0)
<div class="$obj->plural">
	@foreach($obj->pluralVar as $obj->singularVar)
	<div><a href="{{action('$obj->controller@show', $obj->singularVar)}}">{{{$obj->singularVar}->{$obj->label}}}</a></div>
	@endforeach
</div>
@else
<div>No {$obj->name}.</div>
@endif
<a href="{{action('{$obj->model}Controller@create')}}">New {$obj->singular}</a>
@endsection
EOT;