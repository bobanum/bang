<li>
<a href="{{action('ProvinceController@show', $province)}}">{{$province->nom}}</a>
<span class="actions">
<a href="{{action('ProvinceController@show', $province)}}">View</a>
<a href="{{action('ProvinceController@edit', $province)}}">Edit</a>
<a href="{{action('ProvinceController@delete', $province)}}">Delete</a>
</span>
</li>