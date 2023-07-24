<div id="capitulo_<?= $hash[0] ?>" data-hash="<?= $hash[0]; ?>" class="card mb-3<?= $estado ?>" draggable="true" ondragstart="drag(event)" ondragend="dragEnd(event)">
    <h5 class="card-header">
        <?= $title ?>
    </h5>
    <div class="card-body">
        <h5 class="card-title d-none d-md-block">
            <?= $date ?>
        </h5>
        <p class="card-text d-none d-md-block">
            <?= $hash[0] ?>
        </p>
        <div class="btn-group w-100" role="group" aria-label="Descarga">
            <a href="javascript:guardarCapitulo('<?= $hash[0] ?>', 'descargando');document.location='magnet:?xt=urn:btih:<?= $hash[0] ?>'" class="btn btn-primary"><i class="bi bi-download"></i> Magnet</a>
            <a href="javascript:guardarCapitulo('<?= $hash[0] ?>', 'bajado' );" class="btn btn-success"><i class="bi bi-download"></i> Bajado</a>
        </div>
    </div>
</div>