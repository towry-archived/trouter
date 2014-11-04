<?php

require "Router/Router.php";

use \Towry\Router\Router;

Router::get('/hi/:id/:name', 'IndexController@index');
Router::get('/hello', function () { echo "Hello"; });

$mapped = Router::map();

if ( !$mapped ) {
    die("Nothing.");
} else {
    $controller = $mapped['controller'];

    if (is_callable( $controller )) {
        call_user_func_array( $controller, $mapped['params'] );
    } else {
        echo "Calling controller: {$controller}";
    }
}
