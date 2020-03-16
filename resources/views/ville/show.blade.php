<h1>{{$ville->nom}}</h1>
<div class="columns">
	
@component(ville.show.column, ['label'=>'id'])
{{$ville->id}}
@endcomponent

@component(ville.show.column, ['label'=>'province_id'])
{{$ville->province_id}}
@endcomponent

@component(ville.show.column, ['label'=>'pays_id'])
{{$ville->pays_id}}
@endcomponent

@component(ville.show.column, ['label'=>'nom'])
{{$ville->nom}}
@endcomponent

@component(ville.show.column, ['label'=>'population'])
{{$ville->population}}
@endcomponent
	@component(ville.show.column, ['label'=>'nom'])
	{{$ville->nom}}
	@endcomponent
	@component(ville.show.column, ['label'=>'auperficie'])
	{{$ville->superficie}}
	@endcomponent
</div>