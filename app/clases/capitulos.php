<?php

class Capitulos
{

    public function getCapitulos()
    {
        $vector = array();
        $conexion = new Conexion();
        $db = $conexion->getConexion();
        $sql = "SELECT hash,estado FROM capitulos WHERE activo=1";
        $consulta = $db->prepare($sql);
        $consulta->execute();
        while ($fila = $consulta->fetch()) {
            $vector[] = array(
                "hash" => $fila['hash'],
                "estado" => $fila['estado'],
            );
        } //fin del ciclo while 

        return $vector;
    }

    // DEVUELVE LOS TOTALES //
    // -1 | CUENTA TODOS LOS REGISTROS
    //  1 | SOLO CUENTA LOS ACTIVOS
    //  0 | CANTIDAD DE DESACTIVADOS
    public function getCantidadCapitulos($activo)
    {
        $conexion = new Conexion();
        $db = $conexion->getConexion();
        if ($activo == -1)
            $sql = "SELECT COUNT(*) FROM capitulos";
        else
            $sql = "SELECT COUNT(*) FROM capitulos WHERE activo=:activo";

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

    public function existeCapitulo($hash)
    {
        $existe = false;
        $conexion = new Conexion();
        $db = $conexion->getConexion();
        $sql = "SELECT * FROM capitulos WHERE hash=:hash";
        $consulta = $db->prepare($sql);
        $consulta->bindParam(':hash', $hash);
        $consulta->execute();
        $total = $consulta->fetchColumn();
        if ($total > 0)
            $existe = true;

        return $existe;
    }


    public function addCapitulo($hash, $estado)
    {

        $conexion = new Conexion();
        $db = $conexion->getConexion();
        $sql = "INSERT INTO capitulos (hash,estado) VALUES (:hash,:estado)";
        $consulta = $db->prepare($sql);
        $consulta->bindParam(':hash', $hash);
        $consulta->bindParam(':estado', $estado);
        $consulta->execute();

        return '{"msg":"Capitulo agregada"}';
    }

    public function deleteCapitulo()
    {
        $conexion = new Conexion();
        $db = $conexion->getConexion();
        $sqlID = "SELECT MIN(ID) as id FROM capitulos WHERE activo=1";

        $consulta = $db->prepare($sqlID);
        $consulta->execute();
        $fila = $consulta->fetch();
        $id = $fila['id'];

        $sql = "UPDATE capitulos SET activo=0 WHERE id=:id";
        $consulta = $db->prepare($sql);
        $consulta->bindParam(':id', $id);
        $consulta->execute();

        return '{"msg":"beneficio eliminado"}';
    }

    public function updateCapitulo($hash, $estado)
    {

        $conexion = new Conexion();
        $db = $conexion->getConexion();
        $sql = "UPDATE capitulos SET estado=:estado WHERE hash=:hash";
        $consulta = $db->prepare($sql);
        $consulta->bindParam(':hash', $hash);
        $consulta->bindParam(':estado', $estado);
        $consulta->execute();

        return '{"msg":"capitulo actualizado"}';
    }
}
