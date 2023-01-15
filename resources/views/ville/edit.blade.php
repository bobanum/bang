@extends('layout.index', ['title'=>'New ville'])
@section('content')
<h1>Edit nom</h1>
<form action="{{action('VilleController@update', $ville)}}" method="post" >
    {{ method_field('put') }}
    {{-- @component('layout.column.form', ['label'=>'id'])
    <input type="text" id="id" name="id" value="{{$ville->id}}" />
    @endcomponent --}}
    @include('ville.form', ['ville'=>$ville])
    <div><input type="submit" name=""><a href="{{action('VilleController@show', $ville)}}">Cancel</a></div>
</form>
@endsection