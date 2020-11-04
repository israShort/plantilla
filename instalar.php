<?php

    include_once("config.php");
    include_once("entidades/usuario.php");

    $usuario = new Usuario();
    $usuario->usuario = "ishort";
    $usuario->clave = $usuario->encriptarClave("admin123");
    $usuario->nombre = "Israel";
    $usuario->apellido = "Short";
    $usuario->correo = "israshort@gmail.com";
    $usuario->insertar();

?>