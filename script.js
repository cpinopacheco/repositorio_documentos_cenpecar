// Definición de variables
const toggleDarkModeButton = document.getElementById("toggleDarkMode");
const body = document.body;

// Función para aplicar el modo oscuro o claro basado en localStorage
function applyTheme(theme) {
  if (theme === "dark") {
    body.classList.add("dark-mode");
    toggleDarkModeButton.setAttribute("data-tg-on", "Desactivar");
    toggleDarkModeButton.setAttribute("data-tg-off", "Desactivar");
  } else {
    body.classList.remove("dark-mode");
    toggleDarkModeButton.setAttribute("data-tg-on", "Activar");
    toggleDarkModeButton.setAttribute("data-tg-off", "Activar");
  }
}

// Leer el tema guardado en localStorage al cargar la página
const savedTheme = localStorage.getItem("theme") || "light";
applyTheme(savedTheme);

// Evento para alternar entre modo oscuro y claro
toggleDarkModeButton.addEventListener("click", () => {
  const currentTheme = body.classList.contains("dark-mode") ? "light" : "dark";
  applyTheme(currentTheme);
  localStorage.setItem("theme", currentTheme);
});

/* Función volver boton volver atras */
function goBack() {
  window.history.back();
}
