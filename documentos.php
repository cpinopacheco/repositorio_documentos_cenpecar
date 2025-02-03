<?php
// Documentos.php

class Documentos
{
    private $conn;
    private $table_name = "documentos";

    public $id;
    public $nombre;
    public $tipo_archivo;
    public $seccion;
    public $ano;
    public $ruta_archivo;
    public $fecha_subida;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function crearDocumento()
    {
        $query = "INSERT INTO " . $this->table_name . " (nombre, tipo_archivo, seccion, ano, ruta_archivo) VALUES (:nombre, :tipo_archivo, :seccion, :ano, :ruta_archivo)";

        $stmt = $this->conn->prepare($query);

        // Sanitizar
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->tipo_archivo = htmlspecialchars(strip_tags($this->tipo_archivo));
        $this->seccion = htmlspecialchars(strip_tags($this->seccion));
        $this->ano = htmlspecialchars(strip_tags($this->ano));
        $this->ruta_archivo = htmlspecialchars(strip_tags($this->ruta_archivo));

        // Bind
        $stmt->bindParam(":nombre", $this->nombre);
        $stmt->bindParam(":tipo_archivo", $this->tipo_archivo);
        $stmt->bindParam(":seccion", $this->seccion);
        $stmt->bindParam(":ano", $this->ano);
        $stmt->bindParam(":ruta_archivo", $this->ruta_archivo);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}
