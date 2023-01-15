@if(count($villes) > 0)
<ul class="villes">
	@foreach($villes as $ville)
    @include('ville.list.row')
	@endforeach
</ul>
@else
<div>No villes.</div>
@endif