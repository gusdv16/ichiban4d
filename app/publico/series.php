<?php
require_once('../config/config.php');
require_once('../clases/series.php');

if (isset($_POST['tipo'])) {
    $series = new Series();

    //Agrego nueva serie
    if ($_POST['tipo'] == "add" && isset($_POST['serie'])) {
        $series->addSerie($_POST['serie']);
    }
    include "../includes/menu_series.php";
}
