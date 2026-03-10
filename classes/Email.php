<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email
{
    public $email;
    public $nombre;
    public $token;

    public function __construct($email, $nombre, $token)
    {

        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    public function enviarConfirmacion()
    {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USE'];
        $mail->Password = $_ENV['EMAIL_PASS'];

        $mail->setFrom('cuentasappsalon4@gmail.com');
        $mail->addAddress($this->email, $this->nombre);
        $mail->Subject = 'Confirma tu cuenta';

        $mail->isHTML(true);
        $mail->CharSet = 'utf-8';

        $contenido = '<thml>';
        $contenido .= "<p><strong>Hola:" . $this->nombre . "</strong>Te has registrado conrrectamente en DevWebCamp, pero es necesario confirmar tu cuenta</p>";
        $contenido .= "<p>Preciona aqui: <a href='" . $_ENV['HOST'] . "/confirmar-cuenta?token=" . $this->token . "'>Confirmar Cuenta </a></p>";
        $contenido .= "<p>Si tu no creaste esta cuenta puedes ignorar el mensaje</p>";
        $contenido .= '</html>';

        $mail->Body = $contenido;

        $mail->send();
    }
    public function enviarInstrucciones()
    {
        $mail = new PHPMailer();
        $mail->isSMTP();
         $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USE'];
        $mail->Password = $_ENV['EMAIL_PASS'];

        $mail->setFrom('cuentasappsalon4@gmail.com');
        $mail->addAddress($this->email, $this->nombre);
        $mail->Subject = 'Reestablece tu password';

        $mail->isHTML(true);
        $mail->CharSet = 'utf-8';

        $contenido = '<thml>';
        $contenido .= "<p><strong>Hola:" . $this->nombre . "</strong>Has socilicitado reestablecer tu password, sigue el siguiente enlace para poder hacerlo</p>";
        $contenido .= "<p>Preciona aqui: <a href='" . $_ENV['HOST'] . "/reestablecer?token=" . $this->token . "'>Reestablecer Password </a></p>";
        $contenido .= "<p>Si tu no solicitaste el cambio puedes ignorar el mensaje</p>";
        $contenido .= '</html>';

        $mail->Body = $contenido;

        $mail->send();
    }
}
