@if(count($provinces) > 0)
<ul class="provinces">
	@foreach($provinces as $province)
    @include('province.list.row')
	@endforeach
</ul>
@else
<div>No provinces.</div>
@endif