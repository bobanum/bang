@extends('layout.index', ['title'=>'New ville'])
@section('content')
<h1>Create nom</h1>
<form action="{{action('VilleController@store')}}" method="post" >
    {{ method_field('post') }}
    {{-- @component('layout.column.form', ['label'=>'id'])
    <input type="text" id="id" name="id" value="{{$ville->id}}" />
    @endcomponent --}}
    @include('ville.form', ['ville'=>$ville, 'verb'=>'post'])
    <div><input type="submit" name=""><a href="{{action('VilleController@index')}}">Cancel</a></div>
</form>
@endsection