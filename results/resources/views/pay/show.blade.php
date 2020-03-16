<h1>{{$pay->nom}}</h1>
<div class="columns">
	
@component('pay.show.column', ['label'=>'id'])
{{$pay->id}}
@endcomponent

@component('pay.show.column', ['label'=>'nom'])
{{$pay->nom}}
@endcomponent

@component('pay.show.column', ['label'=>'ISO'])
{{$pay->ISO}}
@endcomponent

@component('pay.show.column', ['label'=>'nom2'])
{{$pay->nom2}}
@endcomponent

@component('pay.show.column', ['label'=>'continent'])
{{$pay->continent}}
@endcomponent

@component('pay.show.column', ['label'=>'capitale'])
{{$pay->capitale}}
@endcomponent

@component('pay.show.column', ['label'=>'population'])
{{$pay->population}}
@endcomponent

@component('pay.show.column', ['label'=>'nomHabitants'])
{{$pay->nomHabitants}}
@endcomponent

@component('pay.show.column', ['label'=>'superficie'])
{{$pay->superficie}}
@endcomponent

@component('pay.show.column', ['label'=>'densite'])
{{$pay->densite}}
@endcomponent

@component('pay.show.column', ['label'=>'popUrbaine'])
{{$pay->popUrbaine}}
@endcomponent

@component('pay.show.column', ['label'=>'frontieres'])
{{$pay->frontieres}}
@endcomponent

@component('pay.show.column', ['label'=>'cotes'])
{{$pay->cotes}}
@endcomponent

@component('pay.show.column', ['label'=>'eauxTerritoriales'])
{{$pay->eauxTerritoriales}}
@endcomponent

@component('pay.show.column', ['label'=>'heure'])
{{$pay->heure}}
@endcomponent

@component('pay.show.column', ['label'=>'moisFroids'])
{{$pay->moisFroids}}
@endcomponent

@component('pay.show.column', ['label'=>'moisFroidsTemp'])
{{$pay->moisFroidsTemp}}
@endcomponent

@component('pay.show.column', ['label'=>'moisChaud'])
{{$pay->moisChaud}}
@endcomponent

@component('pay.show.column', ['label'=>'moisChaudsTemp'])
{{$pay->moisChaudsTemp}}
@endcomponent
	@component('pay.show.column', ['label'=>'nom'])
	{{$pay->nom}}
	@endcomponent
	@component('pay.show.column', ['label'=>'auperficie'])
	{{$pay->superficie}}
	@endcomponent
</div>