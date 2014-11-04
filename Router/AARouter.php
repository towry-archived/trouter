<?php

namespace Towry\Router;

class AARouter
{

    private $routes = array();

    private $current;

    public function mapUrl( $method, $url, $controller )
    {
        $parsed = $this->parseUrl($url);

        $pattern = "#^{$parsed['url']}/?(.*)(\?.*)?$#";

        if (! isset($this->routes[$method])) {
            $this->routes[$method] = [];
        }

        $this->routes[$method][$parsed['url']] = array(
            'method'     => $method,
            'params'     => $parsed['params'],
            'controller' => $controller,
            'url'        => $parsed['url'],
            'pattern'    => $pattern
        );

        return $this;
    }

    public function get( $url, $controller )
    {
        return $this->mapUrl('GET', $url, $controller);
    }

    public function post( $url, $controller )
    {
        return $this->mapUrl('POST', $url, $controller);
    }

    public function put( $url, $controller )
    {
        return $this->mapUrl("PUT", $url, $controller);
    }

    public function delete( $url, $controller )
    {
        return $this->mapUrl("DELETE", $url, $controller);
    }

    public function parseUrl( $url )
    {
        $first = strpos( $url, ':');
        if (! $first) {
            return array('params' => [], 'url' => $url );
        }

        $url2 = substr( $url, 0, $first );
        $url2 = rtrim($url2, '/');

        $params = substr( $url, $first + 1 );
        $params = explode( ':', $params );

        $params = array_map( function ($p) {
            return str_replace( '/', '', $p );
        }, $params);

        return array('params' => $params, 'url' => $url2);
    }

    public function map()
    {
        $pathinfo = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '/';
        $pathinfo = rtrim( $pathinfo, '/' );
        $method = strtoupper( $_SERVER['REQUEST_METHOD'] );

        if (! isset($this->routes[$method])) {
            return false;
        }

        $routes = $this->routes[$method];

        $count = 0;

        foreach ($routes as $url => $route) {
            $count++;
            $pattern = $route['pattern'];

            if (preg_match( $pattern, $pathinfo, $matched )) {
                if ($matched[1] === '') {
                    $matched = false;
                } else {
                    $params = explode( '/', $matched[1]);

                    if (count($params) != count($route['params'])) {
                        $matched = false;
                    }
                }

                if ($matched) {
                    $this->current = array(
                        'method' => $route['method'],
                        'params' => $params,
                        'controller' => $route['controller'],
                        'url' => $route['url'],
                    );

                    return $this->current;
                }
            } 
        }

        return false;
    } 

    public function method()
    {
        if (!isset($this->current)) {
            return null;
        } else {
            return $this->current['method'];
        }
    }
}
