<?php
// buscar_documentos.php

require_once "config.php";

// Crear una conexión a la base de datos con manejo de errores
try {
  $database = new Database();
  $db = $database->getConnection();
} catch (Exception $e) {
  die("Error al conectar a la base de datos: " . $e->getMessage());
}

// Definir íconos según el tipo de archivo
$iconos = [
  'pdf' => 'assets/icons/pdf_icon.png',
  'doc' => 'assets/icons/word_icon.png',
  'docx' => 'assets/icons/word_icon.png',
  'xls' => 'assets/icons/excel_icon.png',
  'xlsx' => 'assets/icons/excel_icon.png'
];

function obtener_documentos($db, $seccion, $ano, $nombre)
{
  $query = "SELECT nombre, tipo_archivo, seccion, ano FROM documentos WHERE LOWER(nombre) LIKE LOWER(:nombre)";

  if ($seccion !== '0') {
    $query .= " AND seccion = :seccion";
  }

  if ($ano !== '') {
    $query .= " AND ano = :ano";
  }

  $query .= " ORDER BY nombre ASC";

  $stmt = $db->prepare($query);

  // Validar y asignar parámetros
  $nombre = '%' . htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8') . '%';
  $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);

  if ($seccion !== '0') {
    $stmt->bindParam(':seccion', $seccion, PDO::PARAM_STR);
  }

  if ($ano !== '') {
    $stmt->bindParam(':ano', $ano, PDO::PARAM_INT);
  }

  try {
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  } catch (Exception $e) {
    die("Error al obtener documentos: " . $e->getMessage());
  }
}

$seccion_seleccionada = htmlspecialchars($_GET['seccion'] ?? '0', ENT_QUOTES, 'UTF-8');
$ano_seleccionado = htmlspecialchars($_GET['ano'] ?? '', ENT_QUOTES, 'UTF-8');
$nombre = htmlspecialchars($_GET['nombre'] ?? '', ENT_QUOTES, 'UTF-8');
$documentos = obtener_documentos($db, $seccion_seleccionada, $ano_seleccionado, $nombre);
$cantidad_archivos = count($documentos);
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Buscar Documentos</title>
  <link rel="stylesheet" href="styles/style.css" />
</head>

<body>
  <div class="main-container">
    <form id="filterForm" class="form">
      <label for="nombre" class="form-label">Buscar Documentos</label>
      <input class="search-input" type="text" name="nombre" placeholder="Nombre de documento..." />
      <div class="filter-container">
        <p class="filter-title">Filtrar documentos</p>
        <div class="select-container">
          <label for="seccion">Sección:</label>
          <select class="section-select" name="seccion">
            <option value="0" <?php echo $seccion_seleccionada === '0' ? 'selected' : ''; ?>>Todas las secciones</option>
            <option value="Perfeccionamiento" <?php echo $seccion_seleccionada === 'Perfeccionamiento' ? 'selected' : ''; ?>>Perfeccionamiento</option>
            <option value="Capacitación" <?php echo $seccion_seleccionada === 'Capacitación' ? 'selected' : ''; ?>>Capacitación</option>
            <option value="Ambientes Virtuales" <?php echo $seccion_seleccionada === 'Ambientes Virtuales' ? 'selected' : ''; ?>>Ambientes Virtuales</option>
            <option value="Finanzas" <?php echo $seccion_seleccionada === 'Finanzas' ? 'selected' : ''; ?>>Finanzas</option>
            <option value="Aseguramiento de la Calidad" <?php echo $seccion_seleccionada === 'Aseguramiento de la Calidad' ? 'selected' : ''; ?>>Aseguramiento de la Calidad</option>
          </select>
        </div>
        <div class="select-container">
          <label for="ano">Año:</label>
          <select class="section-select" name="ano">
            <option value="" <?php echo $ano_seleccionado === '' ? 'selected' : ''; ?>>Seleccione año</option>
            <?php for ($i = date("Y"); $i >= 2020; $i--): ?>
              <option value="<?php echo $i; ?>" <?php echo $ano_seleccionado == $i ? 'selected' : ''; ?>><?php echo $i; ?></option>
            <?php endfor; ?>
          </select>
        </div>
      </div>
      <input type="submit" value="Buscar" class="submit-button" />
    </form>
    <section class="table-container" id="resultTable">
      <div class="file-counter">Archivos encontrados: <?php echo $cantidad_archivos; ?></div>
      <div class="document-table">
        <table class="table">
          <thead>
            <tr>
              <th>Nombre</th>
              <th>Sección</th>
              <th>Tipo de Archivo</th>
            </tr>
          </thead>
          <tbody id="documentTableBody">
            <?php foreach ($documentos as $documento): ?>
              <tr>
                <td><?php echo htmlspecialchars($documento['nombre'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php echo htmlspecialchars($documento['seccion'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td>
                  <a href="uploads/<?php echo htmlspecialchars($documento['nombre'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank" class="file-link">
                    <img class="file-image" src="<?php echo htmlspecialchars($iconos[$documento['tipo_archivo']], ENT_QUOTES, 'UTF-8'); ?>" />Descargar
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </section>
  </div>
  <div class="tooltip-container">
    <div class="tooltip-button-container">
      <p class="tooltip-title">Cómo buscar documentos:</p>
      <button class="tooltip-button">?</button>
      <div class="tooltip-content">
        Utilice el campo de búsqueda para encontrar documentos por nombre, o si lo prefiere, puede emplear los filtros para encontrar documentos por sección y año.
      </div>
    </div>
  </div>
  <script>
    document.getElementById('filterForm').addEventListener('submit', function(event) {
      event.preventDefault(); // Prevenir el comportamiento por defecto del formulario
      const seccion = document.querySelector('select[name="seccion"]').value;
      const ano = document.querySelector('select[name="ano"]').value;
      const nombre = document.querySelector('input[name="nombre"]').value;
      const params = new URLSearchParams({
        seccion,
        ano,
        nombre
      });
      fetch('buscar_documentos.php?' + params.toString(), {
          method: 'GET'
        })
        .then(response => response.text())
        .then(data => {
          // Extraer solo el tbody del HTML recibido
          const parser = new DOMParser();
          const doc = parser.parseFromString(data, 'text/html');
          const tbody = doc.querySelector('#documentTableBody');

          // Verifica que tbody no sea null antes de acceder a sus propiedades
          if (tbody) {
            document.getElementById('documentTableBody').innerHTML = tbody.innerHTML;
            // Actualizar el contador de archivos
            const cantidad_archivos = doc.querySelector('.file-counter').textContent;
            document.querySelector('.file-counter').textContent = cantidad_archivos;
            // Mostrar la sección table-container si hay resultados
            document.querySelector('.table-container').style.display = 'block';
            // Desplazamiento suave hacia la tabla de resultados
            document.getElementById('resultTable').scrollIntoView({
              behavior: 'smooth'
            });
          } else {
            console.error('No se encontró el elemento #documentTableBody en la respuesta del servidor.');
            // Ocultar la sección table-container si no hay resultados
            document.querySelector('.table-container').style.display = 'none';
          }
        })
        .catch(error => console.error('Error:', error));
    });
  </script>
</body>

</html>