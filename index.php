<?php
error_reporting(E_ALL);
header("content-type: text/plain");
// GIST https://gist.github.com/bobanum/5051442136a2ff081ea714ac918d6a51
//MODIFIÉ
if (isset($argc)) {

    $message = "";
    
    if ($argc < 2) {
        goto start;
    }
    $options = array(
        'database' => array(
            'alias' => 'd',
            'param' => true,
            'help' => 'Input ',
        ),
        'port' => array(
            'alias' => 't',
            'param' => true,
            'help' => 'Input ',
        ),
        'output' => array(
            'alias' => 'o',
            'param' => true,
            'help' => '(not implemented yet)',
        ),
        'verbose' => array(
            'alias' => 'v',
            'param' => false,
            'help' => '(not implemented yet)',
        ),
        'help' => array(
            'alias' => 'h',
            'param' => false,
            'help' => '(not implemented yet)',
        ),
        'username' => array(
            'alias' => 'u',
            'param' => true,
            'help' => '(not implemented yet)',
        ),
        'password' => array(
            'alias' => 'p',
            'param' => true,
            'help' => '(not implemented yet)',
        ),
    );
    
    array_shift($argv);
    $args = array();
    $options = array();
    while(count($argv)) {
        $arg = array_shift($argv);
        if (substr($arg, 0, 2) === "--") {
            $name = substr($arg, 2);
            if (isset($params[$name]) && $params[$name]) {
                $options[$name] = array_shift($argv);
            } else {
                $options[$name] = true;
            }
        } else if (substr($arg, 0, 1) === "-") {
            $letters = str_split(substr($arg, 1));
            foreach($letters as $letter) {
                if (isset($aliases[$letter])) {
                    $name = $aliases[$letter];
                    if (isset($params[$name]) && $params[$name]) {
                        $options[$name] = array_shift($argv);
                    } else {
                        $options[$name] = true;
                    }
                } else {
                    $message = "Invalid option '$letter'";
                    goto error;
                }
            }
        } else {
            $args[] = $arg;
        }
    }
    goto start;
    error:
    $output = [];
    $output[] = "Usage : Bang [database_path] [options]";
    $output[] = "Options :";
    $output[] = "-o | --output path (not implemented yet)";
    $output[] = "-v | --verbose (not implemented yet)";
    $output[] = "-h | --help (not implemented yet)";
    
    // END GIST
    start:
    if (isset($args)) {
        $db = $args[0];
    } else {
        $path = substr(dirname(__DIR__), 7);
        $db = glob("$path/database/*.sqlite");
        if (count($db) === 0) {
            die("error");
        }
        $db = $db[0];
    }
}else {
    //TEMP:
    // $db = "C:/Users/bo_ba/Desktop/recettesbang/database/resto.sqlite";
    // $db = "C:/Users/bo_ba/Desktop/rebang/database/monde.sqlite";
    $db = "C:/Users/bo_ba/Desktop/chinook/database/chinook.sqlite";

}


spl_autoload_register(function ($class) {
    include '' . $class . '.php';
});

$path_db = $db;

$bang = new \Bang\Bang($path_db);
$bang->go();