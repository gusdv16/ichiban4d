<?php
require_once('../app/config/config.php');
require_once('../app/clases/posters.php');
$directorio = "../img/catalogo/";

function listarArchivos($path)
{
    $posters = new Posters();

    // Abrimos la carpeta que nos pasan como parámetro
    $dir = opendir($path);
    // Leo todos los ficheros de la carpeta
    while ($elemento = readdir($dir)) {
        // Tratamos los elementos . y .. que tienen todas las carpetas
        if ($elemento != "." && $elemento != "..") {
            // Si es una carpeta
            if (is_dir($path . $elemento)) {
                // Muestro la carpeta
                echo "<p><strong>CARPETA: " . $elemento . "</strong></p>";
                // Si es un fichero
            } else {
                // Muestro el fichero
                echo "<br />" . $elemento;
            }

            $posters->convertImageWebp($path . $elemento, "jpg");
        }
    }
}
// Llamamos a la función para que nos muestre el contenido de la carpeta gallery
listarArchivos($directorio);
