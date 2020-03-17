@extends('layout.index')
@section('content')
<h1>Liste des provinces</h1>
@if(count($provinces) > 0)
<div class="provinces">
	@foreach($provinces as $province)
	<div><a href="{{action('ProvinceController@show', $province)}}">{{$province->nom}}</a></div>
	@endforeach
</div>
@else
<div>Il n'y a aucun provinces.</div>
@endif
@endsection