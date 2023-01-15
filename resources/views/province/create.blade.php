@extends('layout.index', ['title'=>'New province'])
@section('content')
<h1>Create nom</h1>
<form action="{{action('ProvinceController@store')}}" method="post" >
    {{ method_field('post') }}
    {{-- @component('layout.column.form', ['label'=>'id'])
    <input type="text" id="id" name="id" value="{{$province->id}}" />
    @endcomponent --}}
    @include('province.form', ['province'=>$province, 'verb'=>'post'])
    <div><input type="submit" name=""><a href="{{action('ProvinceController@index')}}">Cancel</a></div>
</form>
@endsection