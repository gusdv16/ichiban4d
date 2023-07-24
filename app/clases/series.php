<?php

class Series
{

    public function getSeries()
    {
        $vector = array();
        $conexion = new Conexion();
        $db = $conexion->getConexion();
        $sql = "SELECT * FROM series WHERE activo=1 ORDER BY id DESC";
        // $sql = "SELECT * FROM series ORDER BY id DESC LIMIT 5";
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
    public function getCantidadSeries($activo)
    {
        $conexion = new Conexion();
        $db = $conexion->getConexion();
        if ($activo == -1)
            $sql = "SELECT COUNT(*) FROM series";
        else
            $sql = "SELECT COUNT(*) FROM series WHERE activo=:activo";

        $consulta = $db->prepare($sql);
        $consulta->bindParam(':activo', $activo);
        $consulta->execute();
        $total = $consulta->fetchColumn();

        return $total;
    }

    public function getCantidadCapitulos($idSerie)
    {
        $conexion = new Conexion();
        $db = $conexion->getConexion();
        $sql = "SELECT COUNT(*) FROM series_capitulos WHERE idSerie=:idSerie";
        $consulta = $db->prepare($sql);
        $consulta->bindParam(':idSerie', $idSerie);
        $consulta->execute();
        $total = $consulta->fetchColumn();

        return $total;
    }

    public function getCantidadCapitulosBajados($idSerie)
    {
        $conexion = new Conexion();
        $db = $conexion->getConexion();
        $sql = "SELECT COUNT(*) FROM series_capitulos INNER JOIN capitulos  ON series_capitulos.hashCapitulo = capitulos.hash WHERE series_capitulos.idSerie=:idSerie AND capitulos.estado='bajado' GROUP BY series_capitulos.idSerie";
        $consulta = $db->prepare($sql);
        $consulta->bindParam(':idSerie', $idSerie);
        $consulta->execute();
        $total = $consulta->fetchColumn();

        if ($total < 1)
            $total = 0;

        return $total;
    }

    public function addSerie($serie)
    {

        $conexion = new Conexion();
        $db = $conexion->getConexion();
        $sql = "INSERT INTO series (nombre) VALUES (:serie)";
        $consulta = $db->prepare($sql);
        $consulta->bindParam(':serie', $serie);
        $consulta->execute();

        return '{"msg":"Serie agregada"}';
    }

    public function deleteSerie()
    {
        $conexion = new Conexion();
        $db = $conexion->getConexion();
        $sqlID = "SELECT MIN(ID) as id FROM series WHERE activo=1";

        $consulta = $db->prepare($sqlID);
        $consulta->execute();
        $fila = $consulta->fetch();
        $id = $fila['id'];

        $sql = "UPDATE series SET activo=0 WHERE id=:id";
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
