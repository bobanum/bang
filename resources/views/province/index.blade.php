@extends('layout.index', ['title'=>'List of provinces'])
@section('content')
<h1>Liste des provinces</h1>
@include('province.list')
<a href="{{action('ProvinceController@create')}}">New province</a>
@endsection