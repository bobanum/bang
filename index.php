<?php
// spl_autoload_register();
error_reporting(E_ALL);
header("content-type: text/plain");
spl_autoload_register(function ($class) {
    include '' . $class . '.php';
});

$path_db = "monde.sqlite";

$bang = new \Bang\Bang($path_db);
$bang->go();