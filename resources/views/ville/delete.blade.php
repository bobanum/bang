@extends('layout.index', ['title'=>'New ville'])
@section('content')
<h1>Delete {{$ville->nom}}</h1>
<form action="{{action('VilleController@destroy', $ville)}}" method="post" >
    @csrf
{{ method_field('delete') }}
    <p>Do you really want to delete that item.</p>
    <div><input type="submit" value="Delete"/><a href="{{action('VilleController@show', $ville)}}">Cancel</a></div>
</form>
@endsection