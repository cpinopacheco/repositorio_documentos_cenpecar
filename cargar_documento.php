<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cargar Documento</title>
    <link rel="stylesheet" href="styles/style.css" />
</head>

<body>
    <div class="main-container">
        <!-- Div para mostrar el mensaje de alerta -->
        <div id="alert-container"></div>

        <!-- Formulario de carga de documentos -->
        <form id="uploadForm" method="post" enctype="multipart/form-data" class="form">
            <label class="form-label" for="documento">Cargar Documento</label>
            <input class="input-file" type="file" name="documento[]" id="documento" multiple required accept=".pdf,.doc,.docx,.xls,.xlsx">
            <div class="filter-container">
                <div class="select-container">
                    <label for="seccion">Sección:</label>
                    <select class="section-select" name="seccion" id="seccion" required>
                        <option value="" disabled selected>Seleccione sección</option>
                        <option value="Perfeccionamiento">Perfeccionamiento</option>
                        <option value="Capacitación">Capacitación</option>
                        <option value="Ambientes Virtuales">Ambientes Virtuales</option>
                        <option value="Finanzas">Finanzas</option>
                        <option value="Aseguramiento de la Calidad">Aseguramiento de la Calidad</option>
                    </select>
                </div>
                <div class="select-container">
                    <label for="ano">Año:</label>
                    <select class="section-select" name="ano" id="ano" required>
                        <option value="" disabled selected>Seleccione año</option>
                        <?php for ($i = date("Y"); $i >= 2020; $i--): ?>
                            <option value="<?php echo $i; ?>" <?php echo $ano_seleccionado == $i ? 'selected' : ''; ?>><?php echo $i; ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>
            <button type="submit" class="submit-button">Cargar</button>
        </form>
    </div>
    <div class="tooltip-container">
        <div class="tooltip-button-container">
            <p class="tooltip-title">Cómo cargar documentos:</p>
            <button class="tooltip-button">?</button>
            <div class="tooltip-content">
                Para cargar un documento, seleccione el archivo y luego especifique el año y la sección correspondientes. Puede cargar uno o varios archivos a la vez, los cuales deben estar en formato PDF, Word o Excel.
            </div>
        </div>
    </div>
    <script>
        document.getElementById('uploadForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevenir el comportamiento por defecto del formulario

            const formData = new FormData(this);

            fetch('procesar_carga.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    // Mostrar el mensaje de alerta con animación
                    const alertContainer = document.getElementById('alert-container');
                    alertContainer.innerHTML = ''; // Limpiar el contenido anterior

                    // Dividir los mensajes por saltos de línea
                    const messages = data.split('<br>');

                    messages.forEach(message => {
                        if (message.trim() !== '') {
                            const alertDiv = document.createElement('div');
                            alertDiv.innerHTML = message;

                            // Determinar si el mensaje es de éxito o error
                            if (message.includes("No se pudo cargar ningún archivo") || message.includes("No se ha subido ningún archivo") || message.includes("No se pudo guardar") || message.includes("ya existe en la base de datos")) {
                                alertDiv.classList.add('alert-error');
                            } else {
                                alertDiv.classList.add('alert-message');
                            }

                            alertDiv.classList.add('alert-visible');
                            alertContainer.appendChild(alertDiv);

                            // Ocultar la alerta después de 4 segundos con animación
                            setTimeout(() => {
                                alertDiv.style.opacity = 0;
                                alertDiv.style.transform = 'translateY(-20px)';
                                setTimeout(() => {
                                    alertDiv.remove();
                                }, 500); // Espera a que la animación termine antes de ocultar completamente
                            }, 4000);
                        }
                    });
                })
                .catch(error => console.error('Error:', error));
        });
    </script>
</body>

</html>