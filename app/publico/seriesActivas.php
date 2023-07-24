<?php
require_once('../config/config.php');
require_once('../clases/seriesActivas.php');
require_once('../clases/series.php');
require_once('../clases/capitulos.php');

if (isset($_POST['idSerie']) && isset($_POST['idCapitulo'])) {
    $seriesActivas = new SeriesActivas();

    $capitulos = new Capitulos();
    $existeCapitulo = $capitulos->existeCapitulo($_POST['idCapitulo']);

    //Agrego nueva serie
    if ($existeCapitulo)
        $seriesActivas->addSeriesActivas($_POST['idSerie'], $_POST['idCapitulo']);
    else {
        $capitulos->addCapitulo($_POST['idCapitulo'], 'inactivo');
        $seriesActivas->addSeriesActivas($_POST['idSerie'], $_POST['idCapitulo']);
    }

    include "../includes/menu_series.php";
}
