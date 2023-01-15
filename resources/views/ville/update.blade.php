<h1>{{$ville->nom}}</h1>
<div class="columns">
	@component('layout.column.show', ['label'=>'id'])
{{$ville->id}}
@endcomponent
@component('layout.column.show', ['label'=>'province'])
<a href="{{action('ProvinceController@show', $ville->province)}}">{{$ville->province->nom}}</a>
@endcomponent
@component('layout.column.show', ['label'=>'pay'])
<a href="{{action('PayController@show', $ville->pay)}}">{{$ville->pay->nom}}</a>
@endcomponent
@component('layout.column.show', ['label'=>'nom'])
{{$ville->nom}}
@endcomponent
@component('layout.column.show', ['label'=>'population'])
{{$ville->population}}
@endcomponent
</div>