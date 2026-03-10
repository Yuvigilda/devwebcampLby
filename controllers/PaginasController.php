<?php

namespace Controllers;

use Model\Evento;
use Model\Categoria;
use Model\Dia;
use Model\Hora;
use Model\Ponente;
use MVC\Router;
use Model\Usuario;

class PaginasController{
    public static function index(Router $router){


        //obtener el totla de cada bloque
        $ponentes_total = Ponente::total();
        $conferencias_total = Evento::total('categoria_id',1);
        $workshops_total = Evento::total('categoria_id',2);
     
        $ponentes = Ponente::all();

        $router->render('paginas/index',[
            'titulo' => 'Inicio',
            'ponentes_total' => $ponentes_total,
            'ponentes' => $ponentes,
            'conferencias_total' => $conferencias_total,
            'workshops_total' => $workshops_total,
           
        ]);
    }
    public static function error(Router $router){
        $router->render('paginas/error',[
            'titulo' => 'Pagina no disponible'
        ]);
    }
    public static function paquetes(Router $router){
        $router->render('paginas/paquetes',[
            'titulo' =>'Paquetes DevWebCamp'
        ]);
    }
       public static function evento(Router $router){
        $router->render('paginas/devwebcamp',[
            'titulo' =>'Sobre DevWebCamp'
        ]);
    }
    public static function conferencias(Router $router){
        $eventos = Evento::ordenar('hora_id', 'ASC');


        $eventos_formateado = array();

        foreach($eventos as $evento){
            $evento->categoria = Categoria::find($evento->categoria_id); //campo creado en base a na categoria de la tb categorias 
            $evento->dia = Dia::find($evento->dia_id);
            $evento->hora = Hora::find($evento->hora_id);
            $evento->ponente = Ponente::find($evento->ponente_id);

            if($evento->dia_id === "1" && $evento->categoria_id === "1"){
                $eventos_formateado['conferencias_v'][] = $evento;
            }
            if($evento->dia_id === "2" && $evento->categoria_id === "1"){
                $eventos_formateado['conferencias_s'][] = $evento;
            }
            if($evento->dia_id === "1" && $evento->categoria_id === "2"){
                $eventos_formateado['workshops_v'][] = $evento;
            }
            if($evento->dia_id === "2" && $evento->categoria_id === "2"){
                $eventos_formateado['workshops_s'][] = $evento;
            }
        }
        $router->render('paginas/conferencias',[
            'titulo' => 'Conferencias y WorkShops',
            'eventos' => $eventos_formateado
        ]);
    }
}