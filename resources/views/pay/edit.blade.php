@extends('layout.index', ['title'=>'New pay'])
@section('content')
<h1>Edit nom</h1>
<form action="{{action('PayController@update', $pay)}}" method="post" >
    {{ method_field('put') }}
    {{-- @component('layout.column.form', ['label'=>'id'])
    <input type="text" id="id" name="id" value="{{$pay->id}}" />
    @endcomponent --}}
    @include('pay.form', ['pay'=>$pay])
    <div><input type="submit" name=""><a href="{{action('PayController@show', $pay)}}">Cancel</a></div>
</form>
@endsection