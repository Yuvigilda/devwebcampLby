<?php

namespace Controllers;

use Classes\Email;
use MVC\Router;
use Model\Usuario;

class AuthController
{
    public static function login(Router $router)
    {
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = new Usuario($_POST);
           
            $alertas = $usuario->validarLogin();
            // debuguerar($alertas);

         

            if (empty($alertas)) {
                //verificar que el usuario exista
                $usuario = Usuario::where('email', $usuario->email);
                 
                if (!$usuario || !$usuario->confirmado) {
                    Usuario::setAlerta('error', 'El usuario no existe o no esta confirmado');
                } else {
                    //el usuario existe
                    if (password_verify($_POST['password'], $usuario->password)) {
                        //inicar sesión
                        session_start();
                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre;
                        $_SESSION['apellido'] = $usuario->apellido;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['confirmado'] = $usuario->confirmado;
                        $_SESSION['admin'] = $usuario->admin ?? null;

                        //redireccionar 
                        if ($usuario->admin) {
                            header('Location: /admin/dashboard');
                        } else {
                            header('Location: /');
                        }
                    }else{
                        Usuario::setAlerta('error', 'Password Incorrecto');
                    }
                }
            }
        }
        $alertas = Usuario::getAlertas();
        $router->render('auth/login', [
            'titulo' => 'Iniciar Sesión',
            'alertas' => $alertas
        ]);
    }
    public static function registro(Router $router)
    {
        $alertas = [];
        $usuario = new Usuario;


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // debuguerar($_POST);

            $usuario->sincronizar($_POST);
            $alertas = $usuario->validar_cuenta();
            if (empty($alertas)) {
                $existeUsuario = Usuario::where('email', $usuario->email);

                if ($existeUsuario) {
                    Usuario::setAlerta('error', 'El usuario ya esta registrado');
                    $alertas = Usuario::getAlertas();
                } else {
                    // hash al password
                    $usuario->hashPassword();

                    //eliminar el password 2 o repetido
                    unset($usuario->password2);

                    //generar token

                    $usuario->crearToken();

                    //crear al nuevo usuario
                    $resultado = $usuario->guardar();

                    //Enciar el email de confirmacion

                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarConfirmacion();

                    if ($resultado) {
                        header('Location: /mensaje');
                    }
                }
            }
        }

        $router->render('auth/registro', [
            'titulo' => 'Crea tu cuenta en DevWebCamp',
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }
    public static function mensaje(Router $router)
    {
        $router->render('auth/mensaje', [
            'titulo' => 'Cuenta creada exitosamente'
        ]);
    }
    public static function confirmar(Router $router)
    {
        $token = s($_GET['token']);

        if (!$token) header('Location: /');

        // encontrar al usuario con el token
        $usuario = Usuario::where('token', $token);

        if (empty($usuario)) {
            Usuario::setAlerta('error', 'Token no válido, la cuenta no se confirmó');
        } else {
            //confirmar cuenta
            $usuario->confirmado = 1;
            $usuario->token = '';
            unset($usuario->password2);

            $usuario->guardar();

            Usuario::setAlerta('exito', 'Cuenta confirmada correctamente');
        }

        $router->render('auth/confirmar', [
            'titulo' => 'Confirma tu cuenta DevWebCamp',
            'alertas' => Usuario::getAlertas()
        ]);
    }

    public static function logout(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            session_start();
            $_SESSION = [];
            header('Location: /');
        }
    }
    public static function olvide(Router $router){
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            
            $usuario = new Usuario($_POST);
      
            $alertas = $usuario->validarEmail();

            if(empty($alertas)){

                //buscar el usuario
                $usuario = Usuario::where('email', $usuario->email);

                if($usuario && $usuario->confirmado){
                    //generar token
                    $usuario->crearToken();

                    unset($usuario->password2);
                    $usuario->guardar();

                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    
                    
                    $email->enviarInstrucciones();


                    $alertas['exito'][] = 'Hemos enviado las instrucciones a tu  email';
                }else{
                    $alertas['error'][]='El usuario no existe o no esta confirmado';
                }
            }
        }
        $router->render('auth/olvide',[
            'titulo' => 'Olvidé mi password',
            'alertas' => $alertas
        ]);
    }
    public static function reestablecer(Router $router){
        $token = s($_GET['token']);

        $token_valido = true;
        if(!$token) header('Location: /');

        $usuario = Usuario::where('token', $token);
        
        
        if(empty($usuario)){
            Usuario::setAlerta('error', 'Token no valido, Intenta de nuevo');
            $token_valido =false;
        }
        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            $usuario->sincronizar($_POST);
           
            $alertas = $usuario->validarPassword();
            if(empty($alertas)){
                $usuario->hashPassword();

                $usuario->token = "";
                
              
                $resultado = $usuario->guardar();
               
                if($resultado){
                    header('Location: /login');
                }
            }
        }
        $alertas = Usuario::getAlertas();
        $router->render('auth/reestablecer', [
            'titulo' => 'Reestablecerpassword',
            'alertas' => $alertas,
            'token_valido' => $token_valido
        ]);
    }
}
