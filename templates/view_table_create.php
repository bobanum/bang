<?php
return <<<EOT
@extends('layout.index', ['title'=>'New $obj->singular'])
@section('content')
<h1>Create {$obj->label}</h1>
<form action="{{action('{$obj->model}Controller@store')}}" method="post" >
    {{ method_field('post') }}
    {{-- @component('{$obj->singular}.form.column', ['label'=>'id'])
    <input type="text" id="id" name="id" value="{{\${$obj->singular}->id}}" />
    @endcomponent --}}
    @include('{$obj->singular}.form', ['{$obj->singular}'=>\${$obj->singular}, 'verb'=>'post'])
    <div><input type="submit" name=""><a href="{{action('{$obj->model}Controller@index')}}">Cancel</a></div>
</form>
@endsection
EOT;