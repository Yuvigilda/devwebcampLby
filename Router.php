<?php

namespace MVC;

class Router{
    public array $getRouters = [];
    public array $postRouters = [];

    public function get($url, $fn){
        $this->getRouters[$url] =$fn;
    }

    public function post($url, $fn){
        $this->postRouters[$url] =$fn;
    }

    public function comprobarRutas(){
        $url_actual = strtok($_SERVER['REQUEST_URI'], '?') ?? '/';

        $method = $_SERVER['REQUEST_METHOD'] ?? '/';

        if($method === 'GET'){
            $fn = $this->getRouters[$url_actual] ?? null;
        }else{
            $fn = $this->postRouters[$url_actual] ?? null;

        }
        if($fn){
            call_user_func($fn, $this);
        }else{
            header('Location: /404');
        }
    }

    public function render($view, $datos = []){ //arreglo asociativo

        foreach($datos as $key => $value){
            $$key = $value; //variable de variable
        }
        ob_start();
        include_once __DIR__ . "/views/$view.php";

        $contenido = ob_get_clean(); //limpia el bffer

        $url_actual = $_SERVER['PATH_INFO'] ?? '/';

        if(str_contains($url_actual, '/admin')){
            include_once __DIR__ . '/views/admin-layout.php';

        }else{
            include_once __DIR__ . '/views/layout.php';
        }
    }
    
}
?>