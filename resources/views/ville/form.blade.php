<div class="columns">
@csrf	
@component('layout.column.form', ['label'=>'id'])
<input type="text" id="id" name="id" value="{{$ville->id}}" />
@endcomponent
@component('layout.column.form', ['label'=>'province_id'])
<input type="text" id="province_id" name="province_id" value="{{$ville->province_id}}" />
@endcomponent
@component('layout.column.form', ['label'=>'pays_id'])
<input type="text" id="pays_id" name="pays_id" value="{{$ville->pays_id}}" />
@endcomponent
@component('layout.column.form', ['label'=>'nom'])
<input type="text" id="nom" name="nom" value="{{$ville->nom}}" />
@endcomponent
@component('layout.column.form', ['label'=>'population'])
<input type="text" id="population" name="population" value="{{$ville->population}}" />
@endcomponent
</div>