<?php
function debuguerar($variable){
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}
function s($html): string{
    $s = htmlspecialchars($html);
    return $s;
}

function pagina_actual($path): bool{
    return str_contains($_SERVER['PATH_INFO'] ?? '/', $path) ? true : false;
}
//funcion para autenticar el usurio
function is_auth():bool{
    if(!isset($_SESSION)){
        session_start();
    }
    return isset($_SESSION['nombre']) && !empty($_SESSION);
}

function is_admin():bool{
     if(!isset($_SESSION)){
        session_start();
    }
    return isset($_SESSION['admin']) && !empty($_SESSION['admin']);
}

function confirm():bool{
    //debuguerar($_SESSION);
    //$_SESSION['confirmado'] === "1" ? true : false;

    if(!isset($_SESSION)){
        session_start();
    }
    if(isset($_SESSION['confirmado']) && $_SESSION['confirmado'] === "1"){
        return true;
    }else{
        return false;
    }
}

function AOS_animacion(): void{
    $efectos = ['fade-up','fade-down','fade-left','fade-right'];
    $efecto = array_rand($efectos,1);
    echo ' data-aos="' . $efectos[$efecto] . '" ';
}


?>