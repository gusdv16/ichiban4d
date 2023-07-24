<?php
require_once('../config/config.php');
require_once('../clases/busquedas.php');

if (isset($_POST['tipo'])) {
    $busquedas = new Busquedas();

    //Agrego nueva busqueda
    if ($_POST['tipo'] == "add" && isset($_POST['q']) && isset($_POST['c'])) {
        $busquedas->addBusqueda($_POST['q'], $_POST['c']);
    }

    $totalActivos = $busquedas->getCantidadBusquedas(1);

    if ($totalActivos > 5)
        $busquedas->deleteBusqueda();


    $vectorBusquedas = array();
    $vectorBusquedas = $busquedas->getBusquedas();

    echo '<li class="list-group-item active">Últimas busquedas</li>';
    foreach ($vectorBusquedas as $busqueda) {
?>
        <li class="list-group-item d-flex list-group-item-action justify-content-between align-items-center">
            <a href="javascript:buscar('<?= $busqueda['busqueda']; ?>','<?= $busqueda['calidad']; ?>','2');">
                <?= $busqueda['busqueda']; ?><?php if ($busqueda['calidad'] != "") echo " + " . $busqueda['calidad']; ?>
            </a>
        </li>
    <?php } ?>
<?php } ?>