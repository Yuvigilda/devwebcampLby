<?php

namespace Controllers;

use Model\Evento;
use Model\Registro;
use Model\Usuario;
use MVC\Router;

class DashboardController{
    public static function index(Router $router){

        $registros = Registro::get(5);//retorna los primero 5 registros
        
        foreach($registros as $registro){
            $registro->usuario = Usuario::find($registro->usuario_id);
        }
        //calcular ingresos
        $virtuales =  Registro::total('paquete_id',2);
        $presenciales = Registro::total('paquete_id',1);

        $ingresos = ($virtuales * 49) + ($presenciales * 199);

        //obtener eventos con mas y menos lugares disponibles
        $menos_lugares =  Evento::ordenarLimite('disponibles', 'ASC', 5);
        $mas_lugares =  Evento::ordenarLimite('disponibles', 'DESC', 5);

        $router->render('admin/dashboard/index',[
            'titulo' => 'Panel de administracion',
            'registros' => $registros,
            'ingresos' => $ingresos,
            'menos_lugares' => $menos_lugares,
            'mas_lugares' => $mas_lugares
        ]);
    }
}

?>