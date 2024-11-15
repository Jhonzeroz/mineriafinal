<?php
    
    $pagina = isset($_GET['Administrador']) ? strtolower($_GET['Administrador']) : 'configuracion_app';
    require_once 'paginas/header.php';
    require_once 'paginas/' . $pagina . '.php';
   ?>