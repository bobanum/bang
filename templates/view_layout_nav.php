<?php
return <<<EOT
<nav>
<ul>
    <li><a href=".">Home</a></li>
    @section('nav')
    <li><a href="#">Fichier</a></li>
    <li><a href="#">Édition</a></li>
    <li><a href="#">Affichage</a></li>
    <li><a href="#">Aide</a></li>
    @show
</ul>
</nav>
EOT;
