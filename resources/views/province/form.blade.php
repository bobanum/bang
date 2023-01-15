<div class="columns">
@csrf	
@component('layout.column.form', ['label'=>'id'])
<input type="text" id="id" name="id" value="{{$province->id}}" />
@endcomponent
@component('layout.column.form', ['label'=>'pay_id'])
<input type="text" id="pay_id" name="pay_id" value="{{$province->pay_id}}" />
@endcomponent
@component('layout.column.form', ['label'=>'nom'])
<input type="text" id="nom" name="nom" value="{{$province->nom}}" />
@endcomponent
@component('layout.column.form', ['label'=>'superficie'])
<input type="text" id="superficie" name="superficie" value="{{$province->superficie}}" />
@endcomponent
</div>