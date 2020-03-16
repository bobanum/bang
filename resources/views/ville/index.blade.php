@extends('layout.index')
@section('contenu')
<h1>Liste des villes</h1>
@if(count($villes) > 0)
<div class="villes">
	@foreach($villes as $ville)
	<div><a href="{{action('VilleController@show', $ville)}}">{{$ville->nom}}</a></div>
	@endforeach
</div>
@else
<div>Il n'y a aucun villes.</div>
@endif
@endsection