<?php
return <<<EOT
@extends('layout.index', ['title'=>'New $obj->singular'])
@section('content')
<h1>Delete {{{$obj->singularVar}->{$obj->label}}}</h1>
<form action="{{action('{$obj->model}Controller@destroy', {$obj->singularVar})}}" method="post" >
    @csrf
{{ method_field('delete') }}
    <p>Do you really want to delete that item.</p>
    <div><input type="submit" value="Delete"/><a href="{{action('{$obj->model}Controller@show', {$obj->singularVar})}}">Cancel</a></div>
</form>
@endsection
EOT;