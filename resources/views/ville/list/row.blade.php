<li>
<a href="{{action('VilleController@show', $ville)}}">{{$ville->nom}}</a>
<span class="actions">
<a href="{{action('VilleController@show', $ville)}}">View</a>
<a href="{{action('VilleController@edit', $ville)}}">Edit</a>
<a href="{{action('VilleController@delete', $ville)}}">Delete</a>
</span>
</li>