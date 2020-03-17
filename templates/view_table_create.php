<?php
return <<<EOT
@extends('layout.index', ['title'=>'New $obj->singular'])
@section('content')
<h1>Create {$obj->label}</h1>
@include('{$obj->singular}.form', ['{$obj->singular}'=>\${$obj->singular}, 'verb'=>'post'])
@endsection
EOT;