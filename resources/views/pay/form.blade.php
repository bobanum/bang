<div class="columns">
@csrf	
@component('layout.column.form', ['label'=>'id'])
<input type="text" id="id" name="id" value="{{$pay->id}}" />
@endcomponent
@component('layout.column.form', ['label'=>'nom'])
<input type="text" id="nom" name="nom" value="{{$pay->nom}}" />
@endcomponent
@component('layout.column.form', ['label'=>'ISO'])
<input type="text" id="ISO" name="ISO" value="{{$pay->ISO}}" />
@endcomponent
@component('layout.column.form', ['label'=>'nom2'])
<input type="text" id="nom2" name="nom2" value="{{$pay->nom2}}" />
@endcomponent
@component('layout.column.form', ['label'=>'continent'])
<input type="text" id="continent" name="continent" value="{{$pay->continent}}" />
@endcomponent
@component('layout.column.form', ['label'=>'capitale'])
<input type="text" id="capitale" name="capitale" value="{{$pay->capitale}}" />
@endcomponent
@component('layout.column.form', ['label'=>'population'])
<input type="text" id="population" name="population" value="{{$pay->population}}" />
@endcomponent
@component('layout.column.form', ['label'=>'nomHabitants'])
<input type="text" id="nomHabitants" name="nomHabitants" value="{{$pay->nomHabitants}}" />
@endcomponent
@component('layout.column.form', ['label'=>'superficie'])
<input type="text" id="superficie" name="superficie" value="{{$pay->superficie}}" />
@endcomponent
@component('layout.column.form', ['label'=>'densite'])
<input type="text" id="densite" name="densite" value="{{$pay->densite}}" />
@endcomponent
@component('layout.column.form', ['label'=>'popUrbaine'])
<input type="text" id="popUrbaine" name="popUrbaine" value="{{$pay->popUrbaine}}" />
@endcomponent
@component('layout.column.form', ['label'=>'frontieres'])
<input type="text" id="frontieres" name="frontieres" value="{{$pay->frontieres}}" />
@endcomponent
@component('layout.column.form', ['label'=>'cotes'])
<input type="text" id="cotes" name="cotes" value="{{$pay->cotes}}" />
@endcomponent
@component('layout.column.form', ['label'=>'eauxTerritoriales'])
<input type="text" id="eauxTerritoriales" name="eauxTerritoriales" value="{{$pay->eauxTerritoriales}}" />
@endcomponent
@component('layout.column.form', ['label'=>'heure'])
<input type="text" id="heure" name="heure" value="{{$pay->heure}}" />
@endcomponent
@component('layout.column.form', ['label'=>'moisFroids'])
<input type="text" id="moisFroids" name="moisFroids" value="{{$pay->moisFroids}}" />
@endcomponent
@component('layout.column.form', ['label'=>'moisFroidsTemp'])
<input type="text" id="moisFroidsTemp" name="moisFroidsTemp" value="{{$pay->moisFroidsTemp}}" />
@endcomponent
@component('layout.column.form', ['label'=>'moisChaud'])
<input type="text" id="moisChaud" name="moisChaud" value="{{$pay->moisChaud}}" />
@endcomponent
@component('layout.column.form', ['label'=>'moisChaudsTemp'])
<input type="text" id="moisChaudsTemp" name="moisChaudsTemp" value="{{$pay->moisChaudsTemp}}" />
@endcomponent
</div>