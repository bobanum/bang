<h1>{{$pay->nom}}</h1>
<div class="columns">
	@component('layout.column.show', ['label'=>'id'])
{{$pay->id}}
@endcomponent
@component('layout.column.show', ['label'=>'nom'])
{{$pay->nom}}
@endcomponent
@component('layout.column.show', ['label'=>'ISO'])
{{$pay->ISO}}
@endcomponent
@component('layout.column.show', ['label'=>'nom2'])
{{$pay->nom2}}
@endcomponent
@component('layout.column.show', ['label'=>'continent'])
{{$pay->continent}}
@endcomponent
@component('layout.column.show', ['label'=>'capitale'])
{{$pay->capitale}}
@endcomponent
@component('layout.column.show', ['label'=>'population'])
{{$pay->population}}
@endcomponent
@component('layout.column.show', ['label'=>'nomHabitants'])
{{$pay->nomHabitants}}
@endcomponent
@component('layout.column.show', ['label'=>'superficie'])
{{$pay->superficie}}
@endcomponent
@component('layout.column.show', ['label'=>'densite'])
{{$pay->densite}}
@endcomponent
@component('layout.column.show', ['label'=>'popUrbaine'])
{{$pay->popUrbaine}}
@endcomponent
@component('layout.column.show', ['label'=>'frontieres'])
{{$pay->frontieres}}
@endcomponent
@component('layout.column.show', ['label'=>'cotes'])
{{$pay->cotes}}
@endcomponent
@component('layout.column.show', ['label'=>'eauxTerritoriales'])
{{$pay->eauxTerritoriales}}
@endcomponent
@component('layout.column.show', ['label'=>'heure'])
{{$pay->heure}}
@endcomponent
@component('layout.column.show', ['label'=>'moisFroids'])
{{$pay->moisFroids}}
@endcomponent
@component('layout.column.show', ['label'=>'moisFroidsTemp'])
{{$pay->moisFroidsTemp}}
@endcomponent
@component('layout.column.show', ['label'=>'moisChaud'])
{{$pay->moisChaud}}
@endcomponent
@component('layout.column.show', ['label'=>'moisChaudsTemp'])
{{$pay->moisChaudsTemp}}
@endcomponent
</div>