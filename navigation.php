<?php
function loadPage($page)
{
    switch ($page) {
        case 'cargar_documento':
            include('cargar_documento.php');
            break;
        case 'buscar_documentos':
            include('buscar_documentos.php');
            break;
        default:
            echo '<h1 class="welcome-text">Bienvenido al repositorio de documentos CENPECAR. A través de esta aplicación, podrá cargar, buscar y descargar diferentes tipos de documentos digitales. Este sistema está diseñado para almacenar, organizar y gestionar documentos de manera segura y eficiente.</h1>';
            break;
    }
}
