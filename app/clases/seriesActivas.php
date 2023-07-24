<?php

class SeriesActivas
{

    public function getSeriesActivas()
    {
        $vector = array();
        $conexion = new Conexion();
        $db = $conexion->getConexion();
        $sql = "SELECT * FROM series_capitulos WHERE activo=1 ORDER BY id DESC";
        $consulta = $db->prepare($sql);
        $consulta->execute();
        while ($fila = $consulta->fetch()) {
            $vector[] = array(
                "id" => $fila['id'],
                "nombre" => $fila['nombre'],
            );
        } //fin del ciclo while 

        return $vector;
    }

    // DEVUELVE LOS TOTALES //
    // -1 | CUENTA TODOS LOS REGISTROS
    //  1 | SOLO CUENTA LOS ACTIVOS
    //  0 | CANTIDAD DE DESACTIVADOS
    public function getCantidadSeriesActivas($activo)
    {
        $conexion = new Conexion();
        $db = $conexion->getConexion();
        if ($activo == -1)
            $sql = "SELECT COUNT(*) FROM series_capitulos";
        else
            $sql = "SELECT COUNT(*) FROM series_capitulos WHERE activo=:activo";

        $consulta = $db->prepare($sql);
        $consulta->bindParam(':activo', $activo);
        $consulta->execute();
        $total = $consulta->fetchColumn();

        return $total;
    }

    public function getBeneficio($id)
    {

        $vector = array();
        $conexion = new Conexion();
        $db = $conexion->getConexion();
        $sql = "SELECT * FROM beneficios WHERE id=:id";
        $consulta = $db->prepare($sql);
        $consulta->bindParam(':id', $id);
        $consulta->execute();
        while ($fila = $consulta->fetch()) {
            $vector[] = array(
                "id" => $fila['id'],
                "headline_texto" => $fila['headline_texto'],
                "headline_numero" => $fila['headline_numero']
            );
        } //fin del ciclo while 

        return $vector[0];
    }


    public function addSeriesActivas($serie, $capitulo)
    {

        $conexion = new Conexion();
        $db = $conexion->getConexion();
        $sql = "INSERT INTO series_capitulos (idSerie,hashCapitulo) VALUES (:serie,:capitulo)";
        $consulta = $db->prepare($sql);
        $consulta->bindParam(':serie', $serie);
        $consulta->bindParam(':capitulo', $capitulo);
        $consulta->execute();

        return '{"msg":"Serie agregada"}';
    }

    public function deleteSerie()
    {
        $conexion = new Conexion();
        $db = $conexion->getConexion();
        $sqlID = "SELECT MIN(ID) as id FROM series_capitulos WHERE activo=1";

        $consulta = $db->prepare($sqlID);
        $consulta->execute();
        $fila = $consulta->fetch();
        $id = $fila['id'];

        $sql = "UPDATE series_capitulos SET activo=0 WHERE id=:id";
        $consulta = $db->prepare($sql);
        $consulta->bindParam(':id', $id);
        $consulta->execute();

        return '{"msg":"beneficio eliminado"}';
    }

    public function updateBeneficio($id, $busqueda, $headline_numero)
    {

        $conexion = new Conexion();
        $db = $conexion->getConexion();
        $sql = "UPDATE beneficios SET headline_texto=:headline_texto, headline_numero=:headline_numero WHERE id=:id";
        $consulta = $db->prepare($sql);
        $consulta->bindParam(':id', $id);
        $consulta->bindParam(':headline_texto', $headline_texto);
        $consulta->bindParam(':headline_numero', $headline_numero);
        $consulta->execute();

        return '{"msg":"beneficio actualizado"}';
    }
}
