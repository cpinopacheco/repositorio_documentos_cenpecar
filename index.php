<?php $page = isset($_GET['page']) ? $_GET['page'] : 'index';
$shouldAnimate = $page != 'cargar_documento' && $page != 'buscar_documentos'; ?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Repositorio Documentos Cenpecar</title>
  <link rel="stylesheet" href="styles/style.css">
  <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body>
  <?php
  include('header.php');
  require_once "config.php";
  require_once "navigation.php";

  // Crear una conexión a la base de datos
  $database = new Database();
  $db = $database->getConnection();
  ?>

  <!-- Menú de navegación -->
  <nav class="navbar <?= $shouldAnimate ? 'animated-navbar' : ''; ?>">
    <div class="navbar-container">
      <a href="?page=cargar_documento" class="navbar-link <?= isset($_GET['page']) && $_GET['page'] == 'cargar_documento' ? 'active' : ''; ?>">
        <i data-lucide="upload-cloud" class="navbar-icon"></i>
        Cargar Documentos
      </a>
      <div class="navbar-separator"></div>
      <a href="?page=buscar_documentos" class="navbar-link <?= isset($_GET['page']) && $_GET['page'] == 'buscar_documentos' ? 'active' : ''; ?>">
        <i data-lucide="search" class="navbar-icon"></i>
        Buscar Documento
      </a>
    </div>
  </nav>

  <div class="content">
    <?php
    if (isset($_GET['page'])) {
      loadPage($_GET['page']);
    } else {
      echo '<h1 class="welcome-text">Bienvenido al repositorio de documentos CENPECAR. A través de esta aplicación, podrá cargar, buscar y descargar diferentes tipos de documentos digitales. Este sistema está diseñado para almacenar, organizar y gestionar documentos de manera segura y eficiente.</h1>';
    }
    ?>
  </div>

  <script>
    lucide.createIcons();
  </script>
</body>

</html>