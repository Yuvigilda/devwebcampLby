<?php 
namespace Model;

class Ponente extends ActiveRecord{
    protected static $tabla = 'ponentes';
    protected static $columnasDB = ['id','nombre','apellido','ciudad','pais','imagen','tags','redes'];

    public $id;
    public $nombre;
    public $apellido;
    public $ciudad;
    public $pais;
    public $imagen;
    public $tags;
    public $redes;

    public $imagen_actual;

    public function __construct($args = [])
        {
            $this->id = $args['id'] ?? null;
            $this->nombre = $args['nombre'] ?? '';
            $this->apellido = $args['apellido'] ?? '';
            $this->ciudad = $args['ciudad'] ?? '';
            $this->pais = $args['pais'] ?? '';
            $this->imagen = $args['imagen'] ?? '';
            $this->tags = $args['tags'] ?? '';
            $this->redes = $args['redes'] ?? '';
        }

        public function validar()
            {
                if(!$this->nombre){
                    self::$alertas['error'][] = 'El nombre es obligatorio';
                }
                 if(!$this->apellido){
                    self::$alertas['error'][] = 'El apellido es obligatorio';
                }
                 if(!$this->ciudad){
                    self::$alertas['error'][] = 'El campo ciudad es obligatorio';
                }
                 if(!$this->pais){
                    self::$alertas['error'][] = 'El campo pais es obligatorio';
                }
                 if(!$this->imagen){
                    self::$alertas['error'][] = 'La imagen es obligatorio';
                }
                 if(!$this->tags){
                    self::$alertas['error'][] = 'El campo areas  es obligatorio';
                }
                
                return self::$alertas;
                
            }

}
