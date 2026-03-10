<?php

namespace Controllers;

use Classes\Paginacion;
use Intervention\Image\Drivers\Gd\Driver;
use Model\Ponente;
use MVC\Router;
use Intervention\Image\ImageManager;


class PonentesController
{
    public static function index(Router $router)
    {
        $pagina_actual = $_GET['page'];
        $pagina_actual = filter_var($pagina_actual, FILTER_VALIDATE_INT);
        if (!$pagina_actual || $pagina_actual < 1) {
            header('Location: /admin/ponentes?page=1');
        }
        $registros_por_pagina = 7;
        $total = Ponente::total();

        $paginacion = new Paginacion($pagina_actual, $registros_por_pagina, $total);

        if ($paginacion->total_paginas() < $pagina_actual) {
            header('Location: /admin/ponentes?page=1');
        }
        $ponentes = Ponente::paginar($registros_por_pagina, $paginacion->offset());

        if (!is_admin()) {
            header('Location: /login');
        }
        $router->render('admin/ponentes/index', [
            'titulo' => "Ponentes / Conferencias",
            'ponentes' => $ponentes,
            'paginacion' => $paginacion->paginacion()
        ]);
    }
    public static function crear(Router $router)
    {
        if (!is_admin()) {
            header('Location: /login');
        }
        $alertas = [];
        $imagen_png = '';
        $imagen_webp = '';

        $ponente = new Ponente;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!is_admin()) {
                header('Location: /login');
            }
            if(!empty($_FILES['imagen']['tmp_name'])){//nombre del archivo temporal
            $carpeta_imagenes = '../public/img/speakers';

            //crear la carpeta si no existe
            if(!is_dir($carpeta_imagenes)){
                mkdir($carpeta_imagenes,0777,true);
            }
            //Este es la version anterior
            //$imagen_png = Image::make($_FILES['imagen']['tmp_name'])->fit(800,800)->encode('png',80);
            //$imagen_webp = Image::make($_FILES['imagen']['tmp_name'])->fit(800,800)->encode('webp',80);

            // Crear el manager
                $manager = new ImageManager(new Driver());

                // Leer la imagen subida
                $image = $manager->read($_FILES['imagen']['tmp_name']);

                // Ajustar a 800x800 (recorta y escala manteniendo proporción)
                $imagen_png  =(clone $image)->cover(800, 800)->encodeByExtension('png', quality: 80);
                $imagen_webp =(clone $image)->cover(800, 800)->encodeByExtension('webp', quality: 80);

                $nombre_imagen = md5(uniqid(rand(), true)); //dar id unico y rand es que sean aleatorios

                $_POST['imagen'] = $nombre_imagen;
            }
            $_POST['redes'] = json_encode($_POST['redes'], JSON_UNESCAPED_SLASHES);

            $ponente->sincronizar($_POST);

            $alertas = $ponente->validar();

            if(empty($alertas)){
                //guardar imagenes
                $imagen_png->save($carpeta_imagenes . '/' . $nombre_imagen . '.png');
                $imagen_webp->save($carpeta_imagenes . '/' . $nombre_imagen . '.webp');

                $resultado = $ponente->guardar();

                if($resultado){
                    header('Location: /admin/ponentes?page=0');
                }
            }
        }

        $router->render('admin/ponentes/crear', [
            'titulo' => "Registrar Ponente",
            'alertas' => $alertas,
            'ponente' => $ponente,
            'redes' => json_decode($ponente->redes)
        ]);
    }

    public static function editar(Router $router){
        if (!is_admin()) {
            header('Location: /login');
        }
        $alertas = [];
        $id = $_GET['id'];
        $id = filter_var($id, FILTER_VALIDATE_INT);

        if(!$id){
            header('Location: /admin/ponentes');
        }
        $ponente = Ponente::find($id);

        if(!$ponente){
            header('Location: /admin/ponentes');

        }
        $ponente->imagen_actual = $ponente->imagen;

        

        if($_SERVER['REQUEST_METHOD'] ==='POST'){
           
            if (!is_admin()) {
                
            header('Location: /login');
        }
        if(!empty($_FILES['imagen']['tmp_name'])){
            $carpeta_imagenes = '../public/img/speakers';
            
            // Crear el manager
                $manager = new ImageManager(new Driver());

                // Leer la imagen subida
                $image = $manager->read($_FILES['imagen']['tmp_name']);

                // Ajustar a 800x800 (recorta y escala manteniendo proporción)
                $imagen_png  =(clone $image)->cover(800, 800)->encodeByExtension('png', quality: 80);
                $imagen_webp =(clone $image)->cover(800, 800)->encodeByExtension('webp', quality: 80);

                $nombre_imagen = md5(uniqid(rand(), true)); //dar id unico y rand es que sean aleatorios

                $_POST['imagen'] = $nombre_imagen;
            }else{
                $_POST['imagen'] = $ponente->imagen_actual;
            }

            
            $_POST['redes'] = json_encode($_POST['redes'], JSON_UNESCAPED_SLASHES);//escapar de los diagonales para no inyectar codigo mailicioso
            $ponente->sincronizar($_POST);
                //debuguerar($_POST);
            $alertas = $ponente->validar();

            if(empty($alertas)){
                if(isset($nombre_imagen)){
                          //guardar imagenes
                $imagen_png->save($carpeta_imagenes . '/' . $nombre_imagen . '.png');
                $imagen_webp->save($carpeta_imagenes . '/' . $nombre_imagen . '.webp');   

                }
                 $resultado = $ponente->guardar();
                 if($resultado){
                    header('Location: /admin/ponentes?page=0');

                 }
            }

        }

        $router->render('admin/ponentes/editar',[
            'titulo' => "Actualizar ponente",
            'alertas' => $alertas,
            'ponente' => $ponente,
            'redes' => json_decode($ponente->redes)

        ]);
    }

    public static function eliminar(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            if (!is_admin()) {
            header('Location: /login');
        }
        $id = $_POST['id'];
        $ponente = Ponente::find($id);
        if(!isset($ponente)){
            header('Location: /admin/ponentes');
        }
        $resultado = $ponente->eliminar();
        if($resultado){
            header('Location: /admin/ponentes?page=0');
        }
        }
    }
}
