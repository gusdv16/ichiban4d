<?php

class Busquedas
{

    public function getBusquedas()
    {
        $vector = array();
        $conexion = new Conexion();
        $db = $conexion->getConexion();
        $sql = "SELECT * FROM busquedas WHERE activo=1 ORDER BY id DESC";
        // $sql = "SELECT * FROM busquedas ORDER BY id DESC LIMIT 5";
        $consulta = $db->prepare($sql);
        $consulta->execute();
        while ($fila = $consulta->fetch()) {
            $vector[] = array(
                "id" => $fila['id'],
                "busqueda" => $fila['busqueda'],
                "calidad" => $fila['calidad'],
            );
        } //fin del ciclo while 

        return $vector;
    }

    // DEVUELVE LOS TOTALES //
    // -1 | CUENTA TODOS LOS REGISTROS
    //  1 | SOLO CUENTA LOS ACTIVOS
    //  0 | CANTIDAD DE DESACTIVADOS
    public function getCantidadBusquedas($activo)
    {
        $conexion = new Conexion();
        $db = $conexion->getConexion();
        if ($activo == -1)
            $sql = "SELECT COUNT(*) FROM busquedas";
        else
            $sql = "SELECT COUNT(*) FROM busquedas WHERE activo=:activo";

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


    public function addBusqueda($busqueda, $calidad)
    {

        $conexion = new Conexion();
        $db = $conexion->getConexion();
        $sql = "INSERT INTO busquedas (busqueda, calidad) VALUES (:busqueda,:calidad)";
        $consulta = $db->prepare($sql);
        $consulta->bindParam(':busqueda', $busqueda);
        $consulta->bindParam(':calidad', $calidad);
        $consulta->execute();

        return '{"msg":"Busqueda agregada"}';
    }

    public function deleteBusqueda()
    {
        $conexion = new Conexion();
        $db = $conexion->getConexion();
        $sqlID = "SELECT MIN(ID) as id FROM busquedas WHERE activo=1";

        $consulta = $db->prepare($sqlID);
        $consulta->execute();
        $fila = $consulta->fetch();
        $id = $fila['id'];

        $sql = "UPDATE busquedas SET activo=0 WHERE id=:id";
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
