<?php
return <<<EOT
<nav>
<ul>
    <li><a href=".">Home</a></li>
    @section('nav')
    <li><a href="#">Home</a></li>
    {$obj->nav_items}
    @show
</ul>
</nav>
EOT;
