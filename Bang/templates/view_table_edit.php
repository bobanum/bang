<?php
return <<<EOT
@extends('layout.index', ['title'=>'New $obj->singular'])
@section('content')
<h1>Edit {$obj->label}</h1>
<form action="{{action('{$obj->model}Controller@update', {$obj->singularVar})}}" method="post" >
    {{ method_field('put') }}
    {{-- @component('layout.column.form', ['label'=>'id'])
    <input type="text" id="id" name="id" value="{{{$obj->singularVar}->id}}" />
    @endcomponent --}}
    @include('{$obj->singular}.form', ['{$obj->singular}'=>{$obj->singularVar}])
    <div><input type="submit" name=""><a href="{{action('{$obj->model}Controller@show', {$obj->singularVar})}}">Cancel</a></div>
</form>
@endsection
EOT;