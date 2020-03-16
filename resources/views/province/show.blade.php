<h1>{{$province->nom}}</h1>
<div class="columns">
	
@component(province.show.column, ['label'=>'id'])
{{$province->id}}
@endcomponent

@component(province.show.column, ['label'=>'pay_id'])
{{$province->pay_id}}
@endcomponent

@component(province.show.column, ['label'=>'nom'])
{{$province->nom}}
@endcomponent

@component(province.show.column, ['label'=>'superficie'])
{{$province->superficie}}
@endcomponent
	@component(province.show.column, ['label'=>'nom'])
	{{$province->nom}}
	@endcomponent
	@component(province.show.column, ['label'=>'auperficie'])
	{{$province->superficie}}
	@endcomponent
</div>