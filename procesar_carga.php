<?php

require_once "config.php";
require_once "Documentos.php";

// Función para procesar la carga de documentos
function procesarCarga($files, $post)
{
    $seccion = $post['seccion'];
    $ano = $post['ano'];  // Obtener el año seleccionado

    // Crear una conexión a la base de datos
    $database = new Database();
    $db = $database->getConnection();

    $mensajes = [];
    $documentos_cargados = 0;
    $documentos_existentes = [];

    // Procesar cada archivo
    foreach ($files['documento']['name'] as $key => $archivo_nombre) {
        $archivo_tmp = $files['documento']['tmp_name'][$key];
        $archivo_tamano = $files['documento']['size'][$key];

        // Obtener la extensión del archivo
        $archivo_extension = pathinfo($archivo_nombre, PATHINFO_EXTENSION);

        // Especificar la ruta donde se guardará el archivo
        $directorio_destino = "uploads/";
        $ruta_archivo = $directorio_destino . basename($archivo_nombre);

        // Verificar si el archivo ya existe en la base de datos con el mismo nombre, sección y año
        $query = "SELECT COUNT(*) as count FROM documentos WHERE nombre = :nombre AND seccion = :seccion AND ano = :ano";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':nombre', $archivo_nombre);
        $stmt->bindParam(':seccion', $seccion);
        $stmt->bindParam(':ano', $ano);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row['count'] > 0) {
            $documentos_existentes[] = $archivo_nombre;
            continue; // Saltar a la siguiente iteración del bucle
        }

        // Mover el archivo al directorio de destino
        if (move_uploaded_file($archivo_tmp, $ruta_archivo)) {
            // Crear una instancia del modelo de Documentos
            $documento = new Documentos($db);

            // Establecer los valores de los campos
            $documento->nombre = $archivo_nombre;
            $documento->tipo_archivo = $archivo_extension;
            $documento->seccion = $seccion;
            $documento->ano = $ano;
            $documento->ruta_archivo = $ruta_archivo;

            // Intentar guardar el documento en la base de datos
            if ($documento->crearDocumento()) {
                $documentos_cargados++;
            } else {
                $mensajes[] = "No se pudo guardar el documento $archivo_nombre en la base de datos.";
            }
        } else {
            $mensajes[] = "Error al mover el archivo $archivo_nombre al directorio de destino.";
        }
    }

    if ($documentos_cargados > 0) {
        $mensajes[] = "$documentos_cargados archivo(s) cargado(s) correctamente.";
    }

    foreach ($documentos_existentes as $archivo_existente) {
        $mensajes[] = "El documento $archivo_existente ya existe en la base de datos con la misma sección y año.";
    }

    if (empty($mensajes)) {
        $mensajes[] = "No se pudo cargar ningún archivo.";
    }

    return $mensajes;
}

// Verificar si se han subido archivos y llamar a la función procesarCarga
if (isset($_FILES['documento']['name']) && !empty($_FILES['documento']['name'][0])) {
    $mensajes = procesarCarga($_FILES, $_POST);
    echo implode('<br>', $mensajes);
} else {
    echo "No se ha subido ningún archivo o ha ocurrido un error.";
}
