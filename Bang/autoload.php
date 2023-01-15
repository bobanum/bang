<?php
spl_autoload_register(function ($class) {
    $parts = explode('\\', $class);
    if ($parts[0] !== 'Bang') return;
    $parts[0] = "src";
    array_unshift($parts, __DIR__);
    $path = implode('/', $parts) . ".php";
    if (file_exists($path)) {
        include($path);
        return;
    }
    $path = implode('/', $parts) . "/Index.php";
    if (file_exists($path)) {
        include($path);
        return;
    }
});
