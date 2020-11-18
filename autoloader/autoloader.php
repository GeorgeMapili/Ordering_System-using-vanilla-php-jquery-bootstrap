<?php

function autoload($className)
{

    if (file_exists(__DIR__ . "/../core/$className.php")) {
        require_once __DIR__ . "/../core/$className.php";
    }
}
spl_autoload_register("autoload");
