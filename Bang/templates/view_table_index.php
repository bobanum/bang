<?php
return <<<EOT
@extends('layout.index', ['title'=>'List of $obj->plural'])
@section('content')
<h1>Liste des {$obj->name}</h1>
@include('{$obj->singular}.list')
<a href="{{action('{$obj->model}Controller@create')}}">New {$obj->singular}</a>
@endsection
EOT;