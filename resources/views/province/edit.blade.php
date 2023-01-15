@extends('layout.index', ['title'=>'New province'])
@section('content')
<h1>Edit nom</h1>
<form action="{{action('ProvinceController@update', $province)}}" method="post" >
    {{ method_field('put') }}
    {{-- @component('layout.column.form', ['label'=>'id'])
    <input type="text" id="id" name="id" value="{{$province->id}}" />
    @endcomponent --}}
    @include('province.form', ['province'=>$province])
    <div><input type="submit" name=""><a href="{{action('ProvinceController@show', $province)}}">Cancel</a></div>
</form>
@endsection