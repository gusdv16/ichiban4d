<?php
require_once('../config/config.php');
require_once('../clases/capitulos.php');

if (isset($_POST['hash']) && isset($_POST['estado'])) {
    $capitulos = new Capitulos();
    $guardar = true;
    $finalizado = false;

    $vectorCapitulos = array();
    $capitulos = new Capitulos();
    $vectorCapitulos = $capitulos->getCapitulos();

    foreach ($vectorCapitulos as $capitulo) {
        if ($capitulo['hash'] == $_POST['hash']) {
            $guardar = false;
            if ($capitulo['estado'] == "bajado")
                $finalizado = true;
            break;
        }
    }

    if (!$finalizado) {
        //Agrego nueva capitulo
        if ($guardar)
            $capitulos->addCapitulo($_POST['hash'], $_POST['estado']);
        else
            $capitulos->updateCapitulo($_POST['hash'], $_POST['estado']);
    }
}
