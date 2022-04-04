<?php
// Basic autoloader for classes
spl_autoload_register();

function myAutoLoader($className){
    $path = "classes/";
    $extension = ".classes.php";
    $fullPath = $path . $className . $extension;

    if(!file_exists($fullPath)){
        return false;
    }

    include_once $fullPath;
}