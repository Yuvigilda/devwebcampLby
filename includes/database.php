<?php
$db = new mysqli($hostname = 'localhost', $username = 'root', $password = '', $database = 'devwebcamp');

if(!$db){
echo "Error: No se pudo conectar a Mysql";
echo "erro : " . mysqli_connect_errno();
exit;
}
?>