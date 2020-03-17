<?php
return <<<EOT
<header>
@section('header')
    <img src="{{asset('images/logo.svg')}}" alt="Logo">
    <h1>{{\$title}}</h1>
@show
</header>
EOT;
