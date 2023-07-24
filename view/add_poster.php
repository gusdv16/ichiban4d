<?php
require_once('../app/config/config.php');
require_once('../app/clases/posters.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../favicon.ico" type="image/x-icon" />
    <title>Agregar Poster</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/estilos.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
    <script src="../js/code.js"></script>
</head>

<body>
    <div class="container">
        <div class="row align-items-center justify-content-center py-3">
            <div class="col-12 col-lg-6" id="main">
                <div class="alert alert-warning" role="alert" id="msj" style="display: none;">
                    This is a warning alert—check it out!
                </div>

                <form id="addposter" enctype="multipart/form-data" method="post" onsubmit="return false">
                    <div class="form-row justify-content-center">
                        <div class="col-12 mb-3">
                            <label for="code">Codigo</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="code" name="code">
                            </div>
                        </div>

                        <div class="col-12 mb-3">
                            <label for="stock">Stock</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="stock" name="stock" value="0">
                            </div>
                        </div>

                        <div class="col-12 mb-3">
                            <label for="category">Categoria</label>
                            <div class="input-group">
                                <select class="custom-select" id="category" name="category">
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
                        </div>

                        <div class="col-12 mb-3">
                            <label for="type">Tipo</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="type" id="type1" checked value="poster">
                                    <label class="form-check-label" for="type1">Poster</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="type" id="type2" value="sticker">
                                    <label class="form-check-label" for="type2">Sticker</label>
                                </div>
                        </div>

                        <div class="col-12 mb-3 form-group">
                            <label for="addImagenPoster">Imagen</label>
                            <input type="file" class="form-control" id="addImagenPoster" name="addImagenPoster" required1 onchange="verificarFormatoImagen('addImagenPoster');" />
                        </div>

                        <div class="col-4 mb-3">
                            <button class="btn btn-primary" type="submit" id="btnAddPoster" onclick="addPoster();">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none;"></span>
                                Guardar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>