<?php
require_once('../config/config.php');
require_once('../clases/posters.php');
$directorio = "../../img/catalogo/";

if (isset($_REQUEST['tipo'])) {
    include "../includes/inc_listado_posters.php";
} else {
    if (!empty($_REQUEST['code']) || !empty($_REQUEST['stock']) || !empty($_FILES['file']['name'])) {
        $uploadedFile = '';
        if (!empty($_FILES["file"]["type"])) {
            // $fileName = time() . '_' . $_FILES['file']['name'];
            $fileName = $_FILES['file']['name'];
            $valid_extensions = array("jpeg", "jpg", "png");
            $temporary = explode(".", $_FILES["file"]["name"]);
            $file_extension = end($temporary);
            if ((($_FILES["file"]["type"] == "image/png") || ($_FILES["file"]["type"] == "image/jpg") || ($_FILES["file"]["type"] == "image/jpeg")) && in_array($file_extension, $valid_extensions)) {
                $sourcePath = $_FILES['file']['tmp_name'];
                $targetPath =  $directorio . $fileName;
                if (move_uploaded_file($sourcePath, $targetPath)) {
                    $uploadedFile = $fileName;
                }
            }
        }

        $code = $_REQUEST['code'];
        $stock = $_REQUEST['stock'];
        $type = $_REQUEST['type'];
        $category = $_REQUEST['category'];
    }


    //Agrego nuevo poster
    $posters = new Posters();
    if ($code != "" && $stock != "" && $type != "" && $category != "" && $uploadedFile != "") {
        $posters->addPoster($code, $stock, $type, $category, $uploadedFile);
        $posters->convertImageWebp($targetPath, $file_extension);
    }

    //$totalActivos = $posters->getCantidadPosters(1);

    $vectorPosters = array();
    $vectorPosters = $posters->getPosters();

    echo '<li class="list-group-item active">Guardado</li>';
    foreach ($vectorPosters as $poster) {
?>
        <li class="list-group-item d-flex list-group-item-action justify-content-between align-items-center">
            <?= $poster['code']; ?>,
            <?= $poster['type']; ?>,
            <?= $poster['imagen']; ?>
        </li>
    <?php } ?>
<?php } ?>