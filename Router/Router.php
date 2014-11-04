<?php

namespace Towry\Router;

require "AARouter.php";

class Router 
{
    protected $routes = array();
    
    protected static $adapter;

    protected static $adapterClass;

    public static function getInstance()
    {
        if ( is_null( static::$adapter ) ) {
            if ( is_null(static::$adapterClass) ) {
                static::$adapterClass = __NAMESPACE__ . '\\AARouter';
            }

            static::$adapter = new static::$adapterClass;
        }

        return static::$adapter;
    }

    /**
     * Same as getInstance
     */
    public static function instance()
    {
        $adapter = static::getInstance();

        return $adapter;
    }

    public static function setAdapter( $adapter )
    {
        static::$adapterClass = $adapter;
    }
    
    public static function get( $url, $controller )
    {
        $adapter = static::getInstance();

        return $adapter->get( $url, $controller );
    }

    public static function post( $url, $controller )
    {
        $adapter = static::getInstance();

        return $adapter->post( $url, $controller );
    }

    public static function map()
    {
        $adapter = static::getInstance();

        return $adapter->map();
    }
}
