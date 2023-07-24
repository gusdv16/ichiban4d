<?php
require_once('config/config.php');
require_once('clases/posters.php');

if (isset($_FILES['subir_archivo'])) {
    $directorio = '../archivos/';
    $subir_archivo = $directorio . basename($_FILES['subir_archivo']['name']);
    echo "<div>";
    if (move_uploaded_file($_FILES['subir_archivo']['tmp_name'], $subir_archivo)) {
        echo "El archivo es válido y se cargó correctamente.<br><br>";
    } else {
        echo "La subida ha fallado";
    }
    echo "</div>";

    require_once 'PHPExcel/Classes/PHPExcel.php';
    $inputFileType = PHPExcel_IOFactory::identify($subir_archivo);
    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
    $objPHPExcel = $objReader->load($subir_archivo);

    $sheet = $objPHPExcel->getSheet(0);
    $highestRow = $sheet->getHighestRow();
    $highestColumn = $sheet->getHighestColumn();

    $posters = new Posters();

    for ($row = 2; $row <= $highestRow; $row++) {
        if ($sheet->getCell("A" . $row)->getValue() != "") {
            $uploadedFile =  $sheet->getCell("A" . $row)->getValue();
        }
        if ($sheet->getCell("B" . $row)->getValue() != "") {
            $ancho =  $sheet->getCell("B" . $row)->getValue();
        }
        if ($sheet->getCell("C" . $row)->getValue() != "") {
            $alto =  $sheet->getCell("C" . $row)->getValue();
        }

        $posters->addPoster($alto, $ancho, $uploadedFile);
    }
}
?>



<form enctype="multipart/form-data" action="" method="POST">
    <div class="input-group">
        <input type="hidden" name="MAX_FILE_SIZE" value="512000" />
        <p> <label>Subir archivo <input name="subir_archivo" type="file" class="btn btn-outline-secondary" /></label></p>

        <p> <input type="submit" value="Enviar Archivo" class="btn btn-outline-secondary" /></p>
    </div>
</form>