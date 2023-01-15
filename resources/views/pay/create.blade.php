@extends('layout.index', ['title'=>'New pay'])
@section('content')
<h1>Create nom</h1>
<form action="{{action('PayController@store')}}" method="post" >
    {{ method_field('post') }}
    {{-- @component('layout.column.form', ['label'=>'id'])
    <input type="text" id="id" name="id" value="{{$pay->id}}" />
    @endcomponent --}}
    @include('pay.form', ['pay'=>$pay, 'verb'=>'post'])
    <div><input type="submit" name=""><a href="{{action('PayController@index')}}">Cancel</a></div>
</form>
@endsection