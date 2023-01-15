<?php
return <<<EOT
<header>
@section('header')
    <img src="{{asset('images/logo_bang.svg')}}" height="192" alt="Logo">
    <h1>{{\$title}}</h1>
@show
</header>
EOT;
