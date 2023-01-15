@extends('layout.index', ['title'=>'List of pays'])
@section('content')
<h1>Liste des pays</h1>
@include('pay.list')
<a href="{{action('PayController@create')}}">New pay</a>
@endsection