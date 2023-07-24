<?php
$vectorSeries = array();
$series = new Series();
$vectorSeries = $series->getSeries();

echo '<li class="list-group-item list-group-item-info list-group-item-action active">Series viendo</li>';
foreach ($vectorSeries as $serie) {
    $capitulos = $series->getCantidadCapitulos($serie['id']);
    $capitulosBajados = $series->getCantidadCapitulosBajados($serie['id']);
?>
    <li class="serie list-group-item list-group-item-action list-group-item-success d-flex justify-content-between align-items-center">
        <a class="text-reset" href="javascript:buscar('<?= $serie['nombre']; ?>','1080','2');">
            <?= $serie['nombre']; ?>
        </a>
        <span class="badge badge-warning badge-pill"><?= $capitulosBajados . "/" . $capitulos; ?></span>
        <div class="drop" id="serie_<?= $serie['id']; ?>" data-id="<?= $serie['id']; ?>" ondrop="dropCapituloEnSerie(event);" ondragover="allowDrop(event)"><?= $serie['nombre']; ?></div>
    </li>
<?php } ?>