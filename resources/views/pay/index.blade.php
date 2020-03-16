@extends('layout.index')
@section('contenu')
<h1>Liste des pays</h1>
@if(count($pays) > 0)
<div class="pays">
	@foreach($pays as $pay)
	<div><a href="{{action('PayController@show', $pay)}}">{{$pay->nom}}</a></div>
	@endforeach
</div>
@else
<div>Il n'y a aucun pays.</div>
@endif
@endsection