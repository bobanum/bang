<?php
return <<<EOT
@extends('layout.index', ['title'=>'{$obj->singular} : '.\${$obj->singular}->{$obj->label}])
@section('content')
<h1>{{\${$obj->singular}->{$obj->label}}}</h1>
<div class="columns">
	{$obj->show_columns}
</div>
@endsection
EOT;