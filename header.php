<?php
$page = isset($_GET['page']) ? $_GET['page'] : 'index';

$shouldAnimate = $page != 'cargar_documento' && $page != 'buscar_documentos';
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Repositorio Documentos Cenpecar</title>
</head>

<body>
  <div class="header">
    <a href="javascript:void(0);" class="back-button" role="button" onclick="goBack()">
      <span class="sr-only"></span>
      <span class="back-text">Volver</span>
    </a>
    <a href="index.php">
      <img class="logo-image" src="assets/img/cenpecar-logo.png" alt="cenpecar-logo" />
    </a>
    <span class="checkbox-wrapper-10">
      <span class="text-button">Modo oscuro:</span>
      <input checked="" type="checkbox" id="cb5" class="tgl tgl-flip" />
      <label for="cb5" class="tgl-btn" id="toggleDarkMode"></label>
    </span>
  </div>
  <section class="banner">
    <h1 class="banner-text <?= $shouldAnimate ? 'animated-banner-text' : ''; ?>">Repositorio de Documentos CENPECAR</h1>
  </section>
  <script src="script.js"></script>
</body>

</html>