<?php
// spl_autoload_register();
header("content-type: text/plain");
spl_autoload_register(function ($class) {
    include '' . $class . '.php';
});

$path_db = "monde.sqlite";

echo \Bang\Bang::go($path_db);