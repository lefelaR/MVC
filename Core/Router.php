<?php
namespace app\Core;

class Router
{
public Request $request;
    protected array $routes = [];

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }


    public function get( $path,  $callback )
    {
        $this->routes['get'][$path] = $callback;
    }

    public function resolve()
    {
     $path = $this->request->getPath();
     $method = $this->request->getMethod();
     $callback = $this->routes[$method][$path] ?? false;
     if(false === $callback)
     {
         echo 'not found';
         die;
     }
     echo call_user_func($callback);
      var_dump($path, $method, $callback);
    }
}