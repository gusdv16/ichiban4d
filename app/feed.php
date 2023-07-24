<?php
require_once('config/config.php');
require_once('clases/capitulos.php');

if (isset($_GET['form'])) {

    if (isset($_GET['q']))
        $q = $_GET['q'];
    if (isset($_GET['c']))
        $c = $_GET['c'];

    function feed($feedURL)
    {
        //VERIFICO SI ESTA GUARDADO EL CAPITULO
        $estado = "";
        $vectorCapitulos = array();
        $capitulos = new Capitulos();
        $vectorCapitulos = $capitulos->getCapitulos();
        //

        $url = $feedURL;
        $rss = simplexml_load_file($url);
        foreach ($rss->channel->item as $item) {
            $title = $item->title;  //extraer el titulo
            $link = $item->link;  //extraer el link
            $date = $item->pubDate;  //extraer la fecha y hora
            $hash = $item->xpath('nyaa:infoHash');
            $description = strip_tags($item->description);  //extraer descripcion y recortarla
            if (strlen($description) > 400) {
                $stringCut = substr($description, 0, 200);
                $description = substr($stringCut, 0, strrpos($stringCut, ' ')) . '...';
            }
            //
            $estado = "";

            //VERIFICO SI ESTA GUARDADO EL CAPITULO
            foreach ($vectorCapitulos as $capitulo) {
                if ($capitulo['hash'] == $hash[0]) {
                    switch ($capitulo['estado']) {
                        case 'descargando':
                            $estado = " border-primary";
                            break;
                        case 'bajado':
                            $estado = " border-success";
                            break;
                        case 'inactivo':
                            $estado = " border-dark";
                            break;
                    }
                    break;
                }
            }
            //
            include "../app/includes/inc_lista_capitulo.php";
        }
    }

    $busqueda = '';
    if (isset($_GET['q']))
        $busqueda = $q;

    $calidad = "";
    if (isset($_GET['c']))
        $calidad = "+" . $c;

    $urlRss = "https://nyaa.si/?page=rss&q=%5BPuyaSubs%21%5D" . $calidad . "+" . urlencode($busqueda) . "+&c=0_0&f=0";

    feed($urlRss);
}
