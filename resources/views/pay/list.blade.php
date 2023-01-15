@if(count($pays) > 0)
<ul class="pays">
	@foreach($pays as $pay)
    @include('pay.list.row')
	@endforeach
</ul>
@else
<div>No pays.</div>
@endif