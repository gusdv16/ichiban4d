<?php

class Posters
{

    public function getPosters()
    {
        $vector = array();
        $conexion = new Conexion();
        $db = $conexion->getConexion();
        $sql = "SELECT * FROM posters ORDER BY id";
        $consulta = $db->prepare($sql);
        $consulta->execute();
        while ($fila = $consulta->fetch()) {
            $vector[] = array(
                "id" => $fila['id'],
                "imagen" => $fila['imagen'],
                "code" => $fila['code'],
                "stock" => $fila['stock'],
                "category" => $fila['category'],
                "type" => $fila['type'],
                "vendido" => $fila['vendido'],
            );
        } //fin del ciclo while 

        return $vector;
    }
    public function getPostersId($id)
    {
        $vector = array();
        $conexion = new Conexion();
        $db = $conexion->getConexion();
        $sql = "SELECT * FROM posters WHERE id=:id ORDER BY id";
        $consulta = $db->prepare($sql);
        $consulta->bindParam(':id', $id);
        $consulta->execute();
        while ($fila = $consulta->fetch()) {
            $vector[] = array(
                "id" => $fila['id'],
                "imagen" => $fila['imagen'],
                "code" => $fila['code'],
                "stock" => $fila['stock'],
                "category" => $fila['category'],
                "type" => $fila['type'],
                "vendido" => $fila['vendido'],
            );
        } //fin del ciclo while 

        return $vector;
    }

    // DEVUELVE LOS TOTALES //
    // -1 | CUENTA TODOS LOS REGISTROS
    //  1 | SOLO CUENTA LOS ACTIVOS
    //  0 | CANTIDAD DE DESACTIVADOS
    public function getCantidadPosters($activo)
    {
        $conexion = new Conexion();
        $db = $conexion->getConexion();
        if ($activo == -1)
            $sql = "SELECT COUNT(*) FROM posters";
        else
            $sql = "SELECT COUNT(*) FROM posters WHERE activo=:activo";

        $consulta = $db->prepare($sql);
        $consulta->bindParam(':activo', $activo);
        $consulta->execute();
        $total = $consulta->fetchColumn();

        return $total;
    }

    public function addPoster($code, $stock, $type, $category, $imagen)
    {
        $conexion = new Conexion();
        $db = $conexion->getConexion();
        $sql = "INSERT INTO posters (code,stock,type,category,imagen) VALUES (:code,:stock,:type,:category,:imagen)";
        $consulta = $db->prepare($sql);
        $consulta->bindParam(':code', $code);
        $consulta->bindParam(':stock', $stock);
        $consulta->bindParam(':type', $type);
        $consulta->bindParam(':category', $category);
        $consulta->bindParam(':imagen', $imagen);
        $consulta->execute();

        return '{"msg":"Poster agregada"}';
    }

    public function deletePoster()
    {
        $conexion = new Conexion();
        $db = $conexion->getConexion();
        $sqlID = "SELECT MIN(ID) as id FROM posters WHERE activo=1";

        $consulta = $db->prepare($sqlID);
        $consulta->execute();
        $fila = $consulta->fetch();
        $id = $fila['id'];

        $sql = "UPDATE posters SET activo=0 WHERE id=:id";
        $consulta = $db->prepare($sql);
        $consulta->bindParam(':id', $id);
        $consulta->execute();

        return '{"msg":"beneficio eliminado"}';
    }

    public function venderPoster($id, $vendido = "si")
    {
        $conexion = new Conexion();
        $db = $conexion->getConexion();
        $sql = "UPDATE posters SET vendido=:vendido WHERE id=:id";
        $consulta = $db->prepare($sql);
        $consulta->bindParam(':id', $id);
        $consulta->bindParam(':vendido', $vendido);
        $consulta->execute();

        return '{"msg":"Se vendio Poster"}';
    }

    public function venderPosterStock($id)
    {
        $conexion = new Conexion();
        $db = $conexion->getConexion();
        $sql = "UPDATE posters SET stock=(stock-1) WHERE id=:id";
        $consulta = $db->prepare($sql);
        $consulta->bindParam(':id', $id);
        $consulta->execute();

        return '{"msg":"Se vendio Poster"}';
    }

    public function getCategory()
    {
        $vector = array();
        $conexion = new Conexion();
        $db = $conexion->getConexion();
        $sql = "SELECT * FROM category ORDER BY id";
        $consulta = $db->prepare($sql);
        $consulta->execute();
        while ($fila = $consulta->fetch()) {
            $vector[] = array(
                "id" => $fila['id'],
                "name" => $fila['name'],
            );
        } //fin del ciclo while 

        return $vector;
    }

    public function convertImageWebp($directorio, $strExt)
    {
        $image = "";
        $extension = $strExt;
        if ($extension == 'jpeg' || $extension == 'jpg')
            $image = imagecreatefromjpeg($directorio);
        elseif ($extension == 'gif')
            $image = imagecreatefromgif($directorio);
        elseif ($extension == 'png')
            $image = imagecreatefrompng($directorio);

        imagewebp($image, $directorio . ".webp", 80);
    }
}
