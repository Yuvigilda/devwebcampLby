<?php

namespace Model;

class Registro extends ActiveRecord
{
    protected static $tabla = 'registros';
    protected static $columnasDB = ['id', 'paquete_id', 'pago_id', 'token', 'usuario_id', 'regalo_id'];

    public $id;
    public $paquete_id;
    public $pago_id;
    public $token;
    public $usuario_id;
    public $regalo_id;

    public $usuario;
    public $paquete;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->paquete_id = $args['paquete_id'] ?? '';
        $this->pago_id = $args['pago_id'] ?? '';
        $this->token = $args['token'] ?? '';
        $this->usuario_id = $args['usuario_id'] ?? '';
        // Solo asignar regalo por defecto si es paquete 2
        if (isset($args['paquete_id']) && $args['paquete_id'] == 2) {
            $this->regalo_id = 1;
        } else {
            $this->regalo_id = $args['regalo_id'] ?? null;
        }
    }
}
