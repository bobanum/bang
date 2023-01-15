@extends('layout.index', ['title'=>'New province'])
@section('content')
<h1>Delete {{$province->nom}}</h1>
<form action="{{action('ProvinceController@destroy', $province)}}" method="post" >
    @csrf
{{ method_field('delete') }}
    <p>Do you really want to delete that item.</p>
    <div><input type="submit" value="Delete"/><a href="{{action('ProvinceController@show', $province)}}">Cancel</a></div>
</form>
@endsection