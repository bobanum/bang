<li>
<a href="{{action('PayController@show', $pay)}}">{{$pay->nom}}</a>
<span class="actions">
<a href="{{action('PayController@show', $pay)}}">View</a>
<a href="{{action('PayController@edit', $pay)}}">Edit</a>
<a href="{{action('PayController@delete', $pay)}}">Delete</a>
</span>
</li>