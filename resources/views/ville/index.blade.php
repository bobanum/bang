@extends('layout.index', ['title'=>'List of villes'])
@section('content')
<h1>Liste des villes</h1>
@include('ville.list')
<a href="{{action('VilleController@create')}}">New ville</a>
@endsection