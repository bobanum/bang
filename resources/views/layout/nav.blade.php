<nav>
<ul>
    <li><a href=".">Home</a></li>
    @section('nav')
    <li><a href="#">Home</a></li>
        <li><a href="{{action('ProvinceController@index')}}">provinces</a></li>
    <li><a href="{{action('VilleController@index')}}">villes</a></li>
    <li><a href="{{action('PayController@index')}}">pays</a></li>
    @show
</ul>
</nav>