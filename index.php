<?php
require_once('app/config/config.php');
require_once('app/clases/busquedas.php');
require_once('app/clases/posters.php');
require_once('app/feed.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
    <title>STIKERS</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="stylesheet" href="css/estilos.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
    <script src="js/code.js"></script>
    <script src="js/isotope.pkgd.min.js"></script>
    
    <style>
        @media (min-width: 34em) {
            .card-columns {
                -webkit-column-count: 5;
                -moz-column-count: 5;
                column-count: 5;
            }
        }

        @media (max-width: 1000px) {
            .card-columns {
                -webkit-column-count: 4;
                -moz-column-count: 4;
                column-count: 4;
            }
        }

        @media (max-width: 800px) {
            .card-columns {
                -webkit-column-count: 3;
                -moz-column-count: 3;
                column-count: 3;
            }
        }

        .vendido {
            opacity: 0.5;
        }

        .compar_poster {
            /*  position: absolute;
            top: 0;
            left: 0;
            text-align: center; */
            display: block;
            color: #000;
        }

        .drop {
            position: fixed;
            top: 0;
            right: -320px;
            width: 320px;
            height: 100vh;
            background-color: #dcdcdc;
            overflow: auto;
            transition: all 700ms;
        }

        .opencart {
            position: fixed;
            top: 0;
            right: 0;
            width: 70px;
            height: 100vh;
            z-index: 2;
        }

        .drop.open {
            right: 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row py-3">

            <div class="col" id="main">
                <div class="form-row align-items-center justify-content-center">
                    <div class="col-12 col-md-auto my-1">
                        <div class="input-group">
                            <input type="text" class="form-control" id="busqueda" name="busqueda" aria-describedby="basic-addon3">
                        </div>
                    </div>
                    <div class="col-12 col-md-auto my-1">
                        <label class="mr-sm-2 sr-only" for="inlineFormCustomSelect">Preference</label>
                        <select class="custom-select mr-sm-2" id="calidad" name="calidad">
                        <option selected>Categorias</option>
                            <?php
                            $categories = new Posters();
                            $vectorCategories = array();
                            $vectorCategories = $categories->getCategory();
                            foreach ($vectorCategories as $category) {
                            ?>
                                <option value="<?= $category['name']; ?>"><?= $category['name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-6 col-md-auto my-1">
                        <button type="submit" class="btn btn-primary w-100" onclick="buscar('busqueda','calidad');">Buscar...</button>
                    </div>
                    <div class="col-auto filter-button-group">
                        <a class="btn btn-primary activo" data-filter=".activo">Activos</a>
                        <a class="btn btn-primary" data-filter=".finalizado">Finalizados</a>
                    </div>
                </div>

                <div class="card-columns row justify-content-around grid" id="listado">
                    <?php include "app/includes/inc_listado_posters.php"; ?>
                </div>

                <div class="progress" style="display: none;">
                    <div class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                </div>

                <div class="fixed-bottom text-right p-4" id="cargador" style="display: none;">
                    <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>

            </div>

        </div>
    </div>


    <!-- Position it -->
    <div style="position: fixed; bottom: 0; right: 0;margin: 10px;">
        <div class="toast" id="alertaEliminado" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="mr-auto">Eliminado</strong>
                <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close" onclick=' $("#alertaEliminado").removeClass("fade show");'>
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="toast-body">
                El poster ha sido vendido, <a href="javascript:deshacerVendido();">Deshacer accion</a>
            </div>
        </div>
    </div>
    <input type="hidden" name="ideliminado" id="ideliminado">

    <div class="drop" id="carrito" data-id="carrito" ondrop="drop(event);" ondragover="allowDrop(event)" onmouseout1="$('#carrito').removeClass('open');">
        <div id="btnComprar" class="col-12 text-center p-1" onclick="comprarPosters();" style="display: none;">
            <div class="btn btn-primary">Comprar</div>
        </div>
        <input type="hidden" name="postersCarrito" id="postersCarrito">
    </div>
    <!-- <div class="opencart" onmouseover="$('#carrito').addClass('open');"></div> -->
</body>

</html>