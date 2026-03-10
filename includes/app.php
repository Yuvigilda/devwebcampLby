<?php

use Model\ActiveRecord;
require __DIR__ . '/../vendor/autoload.php';


require 'funciones.php';
require 'database.php';

ActiveRecord::setDB($db);

?>