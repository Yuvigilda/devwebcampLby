<?php

namespace Controllers;

use MVC\Router;
use Model\Registro;
use Model\Evento;
use Model\Categoria;
use Model\Dia;
use Model\EventosRegistros;
use Model\Hora;
use Model\Usuario;
use Model\Paquete;
use Model\Regalo;
use Model\Ponente;
use Throwable;

class RegistroController
{

    public static function crear(Router $router)
    {

        if (!is_auth()) {

            header('Location: /');

            return;
        }
        //verificar si el usuario ya esta registrado
        $registro = Registro::where('usuario_id', $_SESSION['id']);
        //$registro->paquete_id = "0";

        // debuguerar($registro);
        if ($registro && ($registro->paquete_id === "3" || $registro->paquete_id === "2")) {
            header('Location: /boleto?id=' . urlencode($registro->token));
        }
        if ($registro && $registro->paquete_id === "1") {
            header('Location: /finalizar-registro/conferencias');
            return;
        }

        $router->render('registro/crear', [
            'titulo' => 'Finalizar registro'
        ]);
    }

    public static function gratis()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (!is_auth()) {
                header('Location: /');
                return;
            }


            $registro = Registro::where('usuario_id', $_SESSION['id']);

            if (isset($registro) && $registro->paquete_id === "3") {
                header('Location: /boleto?id=' . urlencode($registro->token));
                return;
            }

            //crear nuevo token
            $token = substr(uniqid(md5(rand()), true), 0, 8);

            //crear registtro
            $datos = array(
                'paquete_id' => 3,
                'pago_id' => '',
                'token' => $token,
                'usuario_id' => $_SESSION['id']
            );
            $registro = new Registro($datos);

            $resultado = $registro->guardar();

            if ($resultado) {
                header('Location: /boleto?id=' . urlencode($registro->token));
                return;
            }
        }
    }

    public static function boleto(Router $router)
    {
        //validar la url
        $id = $_GET['id'];
        if (!$id || !strlen($id) === 8) {
            header('Location: /');
            return;
        }
        //buscar el registro en lla BBDD

        $registro = Registro::where('token', $id);

        if (!$registro) {
            header('Location: /');
            return;
        }

        //colocar las referencias con cruce de datos

        $registro->usuario = Usuario::find($registro->usuario_id);
        $registro->paquete = Paquete::find($registro->paquete_id);


        $router->render('registro/boleto', [
            'titulo' => 'Asistencia a DevWebCamp',
            'registro' => $registro
        ]);
    }
    // public static function pagar()
    // {
    //     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //         // debuguerar($_POST['paqete_id']);
    //         if (!is_auth()) {
    //             header('Location: /login');
    //             return;
    //         }
    //         //validar que el post no enga vacio
    //         if (empty($_POST)) {
    //             echo json_encode([]);
    //             return;
    //         }

    //         //crear el reegistro
    //         $datos = $_POST;
    //         $datos['token'] = substr(md5(uniqid(rand(), true)), 0, 8);
    //         $datos['usuario_id'] = $_SESSION['id'];


    //         try {
    //             $registro = new Registro($datos);

    //             $resultado = $registro->guardar();

    //             if ($resultado) {
    //                 if ((int)$registro->paquete_id === 1) {
    //                     echo json_encode([
    //                         'resultado' => true,
    //                         'redirect' => '/finalizar-registro/conferencias'
    //                     ]);
    //                     exit;
    //                 } else {
    //                     echo json_encode([
    //                         'resultado' => true,
    //                         'redirect' => '/boleto?id=' . urlencode($registro->token)
    //                     ]);
    //                     exit;
    //                 }
    //             } else {
    //                 echo json_encode(['resultado' => false]);
    //                 exit;
    //             }
    //         } catch (Throwable $th) {
    //             echo json_encode([
    //                 'resultado' => 'error en el metodo pagar'
    //             ]);
    //         }
    //     }
    // }

    public static function pagar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!is_auth()) {
                header('Location: /login');
                return;
            }
            error_log("POST recibido en pagar: " . print_r($_POST, true));



            // Recoger datos enviados desde FormData (paquete_id y pago_id)
            $paquete_id = $_POST['paquete_id'] ?? null;
            $pago_id = $_POST['pago_id'] ?? null;

            if (!$paquete_id || !$pago_id) {
                echo json_encode([
                    'resultado' => false,
                    'error' => 'Datos incompletos'
                ]);
                return;
            }

            // Construir datos para el registro
            $datos = [
                'paquete_id' => $paquete_id,
                'pago_id' => $pago_id,
                'token' => substr(md5(uniqid(rand(), true)), 0, 8),
                'usuario_id' => $_SESSION['id'],
                'regalo_id' => 0 // importante para evitar el error

            ];

            try {
                $registro = new Registro($datos);
                $resultado = $registro->guardar();

                if ($resultado) {
                    // Flujo según el paquete
                    if ((int)$registro->paquete_id === 1) {
                        echo json_encode([
                            'resultado' => true,
                            'redirect' => '/finalizar-registro/conferencias'
                        ]);
                        exit;
                    } else {
                        echo json_encode([
                            'resultado' => true,
                            'redirect' => '/boleto?id=' . urlencode($registro->token)
                        ]);
                        exit;
                    }
                } else {
                    echo json_encode(['resultado' => false]);
                    exit;
                }
            } catch (Throwable $th) {
                error_log("Error en pagar: " . $th->getMessage());
                echo json_encode([
                    'resultado' => false,
                    'error' => $th->getMessage()
                ]);
                exit;
            }
        }
    }

    public static function conferencias(Router $router)
    {

        if (!is_auth()) {
            header('Location: /login');
            return;
        }
        $usuario_id = $_SESSION['id'];
        $registro = Registro::where('usuario_id', $usuario_id);

        if (isset($registro) && $registro->paquete_id === '2') {
            header('Location: /boleto?id=' . urlencode($registro->token));
            return;
        }
        if ($registro->paquete_id !== "1") {
            header('Location: /');
            return;
        }
        //redireccionar a boleto virtual en caso de haber finalizado el registro
        if (!empty($registro->regalo_id) && $registro->paquete_id === '1') {
            header('Location: /boleto?id=' . urlencode($registro->token));
            return;
        }
        $eventos = Evento::ordenar('hora_id', 'ASC');
        $eventos_formateado = [];
        foreach ($eventos as $evento) {
            $evento->categoria = Categoria::find($evento->categoria_id);
            $evento->dia = Dia::find($evento->dia_id);
            $evento->hora = Hora::find($evento->hora_id);
            $evento->ponente = Ponente::find($evento->ponente_id);

            if ($evento->dia_id === '1' && $evento->categoria_id === '1') {
                $eventos_formateado['conferencias_v'][] = $evento;
            }

            if ($evento->dia_id === '2' && $evento->categoria_id === '1') {
                $eventos_formateado['conferencias_s'][] = $evento;
            }
            if ($evento->dia_id === '1' && $evento->categoria_id === '2') {
                $eventos_formateado['workshoops_v'][] = $evento;
            }

            if ($evento->dia_id === '2' && $evento->categoria_id === '2') {
                $eventos_formateado['workshops_s'][] = $evento;
            }
        }

        $regalos = Regalo::all('ASC');

        // manejar el registro mediante POST

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!is_auth()) {
                header('Location: /login');
                return;
            }
            $eventos =  explode(',', $_POST['eventos']); //

            if (empty($eventos)) {
                echo json_encode(['resultado' => false]);
                return;
            }
            $registro = Registro::where('usuario_id', $_SESSION['id']);
            if (!isset($registro) || $registro->paquete_id !== '1') {
                echo json_encode(['resultado' => false]);
                return;
            }

            $eventos_array = [];
            //validar la disponibilida de lo eventos seleccionado

            foreach ($eventos as $evento_id) {
                $evento = Evento::find($evento_id);
                //comprobar que el evento exista

                if (!isset($evento) || $evento->disponibles === "0") {
                    echo json_encode(['resultado' => false]);
                    return;
                }
                $eventos_array[] = $evento;
            }
            foreach ($eventos_array  as $evento) {
                $evento->disponibles -= 1; //restar los eventos
                $evento->guardar();

                //almacenar los registros
                $datos = [
                    'evento_id' => (int) $evento->id,
                    'registro_id' => (int) $registro->id
                ];

                $registro_usuario = new EventosRegistros($datos); //pasar datos que se proceso en EventosRegistros

                $registro_usuario->guardar();
            }

            $registro->sincronizar(['regalo_id' => $_POST['regalo_id']]); //esta sincronizando por lo tant esta actualizando
            $resultado =  $registro->guardar(); //actualizando

            // if ($resultado) {
            //     echo json_encode(['resultado' => $resultado, 'token' => $registro->token]);
            // } else {
            //     echo json_encode(['resultado' => false]);
            // }
            if ($resultado) {
                // recargar el registro para asegurar que el token está disponible
                $registro = Registro::where('usuario_id', $_SESSION['id']);
                echo json_encode([
                    'resultado' => true,
                    'token' => $registro->token
                ]);
                exit;
            } else {
                echo json_encode(['resultado' => false]);
                exit;
            }
        }

        $router->render('registro/conferencias', [
            'titulo' => 'Elige Workshops y conferencias',
            'eventos'  => $eventos_formateado,
            'regalos' => $regalos
        ]);
    }
}
