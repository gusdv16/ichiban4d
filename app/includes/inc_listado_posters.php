<?php
$posters = new Posters();

if (isset($_REQUEST['idPoster'])) {
    if ($_REQUEST['tipo'] == "venta")
        $posters->venderPosterStock($_REQUEST['idPoster']);
    if ($_REQUEST['tipo'] == "deshacer")
        $posters->venderPoster($_REQUEST['idPoster'], "no");
    if ($_REQUEST['tipo'] == "carrito") {
        $vectorPosters = array();
        $vectorPosters = $posters->getPostersId($_REQUEST['idPoster']);
        foreach ($vectorPosters as $poster) {
?>
            <div class="card">
                <div class="row no-gutters">
                    <div class="col-md-4">
                        <picture>
                            <!-- <source srcset="img/catalogo/<?= $poster['imagen']; ?>.webp" type="image/webp"> -->
                            <source srcset="img/catalogo/<?= $poster['imagen']; ?>" type="image/jpeg">
                            <img class="card-img-top" src="img/catalogo/<?= $poster['imagen']; ?>" alt="<?= $poster['imagen']; ?>" title="<?= $poster['imagen']; ?>" draggable="false" />
                        </picture>
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title"><?= $poster['code']; ?></h5>
                            <p class="card-text"><small class="text-muted">Cantidad: <?= $poster['stock']; ?></small></p>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        }
    }
}

$imprimir = true;
if (isset($_REQUEST['tipo']) && $_REQUEST['tipo'] == "carrito")
    $imprimir = false;

if ($imprimir) {
    $vectorPosters = array();
    $vectorPosters = $posters->getPosters();
    foreach ($vectorPosters as $poster) {
        ?>
        <?php if ($poster['stock'] > 0) { ?>
            <div class="card col-2 element-item activo">
                <span class="badge badge-danger" style="opacity:0;">Sin Stock</span>
            <?php } else { ?>
                <div class="card col-2 element-item finalizado vendido">
                    <span class="badge badge-danger">Sin Stock</span>
                <?php  } ?>
                <?php if ($poster['stock'] > 0) { ?>
                    <a class="compar_poster" data-hash="<?= $poster['id']; ?>" id="<?= $poster['id']; ?>" onclick="venderPosterStock(<?= $poster['id']; ?>);">                    
                <?php } ?>
                    <picture>
                        <source srcset="img/catalogo/<?= $poster['imagen']; ?>.webp" type="image/webp">
                        <source srcset="img/catalogo/<?= $poster['imagen']; ?>" type="image/jpeg">
                        <img class="card-img-top" src="img/catalogo/<?= $poster['imagen']; ?>" alt="<?= $poster['imagen']; ?>" title="<?= $poster['imagen']; ?>" draggable="false" />
                    </picture>
                    <div class="card-body">
                        <p class="card-text"><small class="text-muted"><?= $poster['category']; ?></small></p>
                        <h5 class="card-title"><?= $poster['code']; ?></h5>
                        <h5 class="card-title"><?= $poster['stock']; ?></h5>
                    </div>
                    <?php if ($poster['stock'] > 0) echo "</a>"; ?>
                </div>
            <?php } ?>
        <?php } ?>