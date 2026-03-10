<?php 
namespace Model;

class Usuario extends ActiveRecord{
    protected static $tabla = 'usuarios';
    protected static $columnasDB=['id','nombre','apellido','email','password','confirmado','token','admin'];

    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $password;
    public $password2;
    public $confirmado;
    public $token;
    public $admin;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido = $args['apellido'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->confirmado = $args['confirmado'] ?? 0;
        $this->token = $args['token'] ?? 0;
        $this->admin = $args['admin'] ?? 0;
    }

    public function validarLogin(){
        if(!$this->email){
            self::$alertas['error'][] = 'El email del usuario es obligatorio';
        }
         if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)){
            self::$alertas['error'][]='Email no válido';
        }
           if(!$this->password){
            self::$alertas['error'][]='El password no puede ir vacio';
        }
        return self::$alertas;
    }

    public function validar_cuenta(){
        if(!$this->nombre){
            self::$alertas['error'][]='El nombre es obligatorio';
        }
        if(!$this->apellido){
            self::$alertas['error'][]='El apellido es obligatorio';
        }
        if(!$this->email){
            self::$alertas['error'][]='El email es obligatorio';
        }
        if(!$this->password){
            self::$alertas['error'][]='El password es obligatorio';
        }
        if(strlen($this->password) < 8){
            self::$alertas['error'][]='El password debe contener al menos 8 caracteres';
        }
        return self::$alertas;
    }
    public function validarEmail(){
         if(!$this->email){
            self::$alertas['error'][]='El email es obligatorio';
        }
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)){
            self::$alertas['error'][]='EMAIL NO VALIDO';
        }
        return self::$alertas;
    }
    public function validarPassword(){
        if(!$this->password){
            self::$alertas['error'][]='El password no puede ir vacio';
        }
           if(strlen($this->password) < 8){
            self::$alertas['error'][]='El password debe contener al menos 8 caracteres';
        }
         return self::$alertas;

    }
    public function nuevo_password() : array{
        //if(!$this->password)
    
    }

    public function hashPassword() : void{
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }
    public function crearToken() : void{
        $this->token = uniqid();
        
    }
}
