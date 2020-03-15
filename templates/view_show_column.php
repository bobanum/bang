<?php
$tmpl = <<<EOT
@extends('layout.index')
@section('contenu')
<h1>Liste des {$table->name}</h1>
@if(count($table->plurVar) > 0)
<div class="$table->plur">
	@foreach($table->plurVar as $table->singVar)
	<div><a href="{{action('$table->controller@show', $table->singVar)}}">{{{$table->singVar}->{$table->label}}}</a></div>
	@endforeach
</div>
@else
<div>Il n'y a aucun {$table->name}.</div>
@endif
@endsection
EOT;