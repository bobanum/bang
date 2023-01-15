@extends('layout.index', ['title'=>'New pay'])
@section('content')
<h1>Delete {{$pay->nom}}</h1>
<form action="{{action('PayController@destroy', $pay)}}" method="post" >
    @csrf
{{ method_field('delete') }}
    <p>Do you really want to delete that item.</p>
    <div><input type="submit" value="Delete"/><a href="{{action('PayController@show', $pay)}}">Cancel</a></div>
</form>
@endsection