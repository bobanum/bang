<h1>{{$province->nom}}</h1>
<div class="columns">
	@component('layout.column.show', ['label'=>'id'])
{{$province->id}}
@endcomponent
@component('layout.column.show', ['label'=>'pay'])
<a href="{{action('PayController@show', $province->pay)}}">{{$province->pay->nom}}</a>
@endcomponent
@component('layout.column.show', ['label'=>'nom'])
{{$province->nom}}
@endcomponent
@component('layout.column.show', ['label'=>'superficie'])
{{$province->superficie}}
@endcomponent
</div>