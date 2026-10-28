// Se carga en <head>, antes de pintar la página

// Con JavaScript activo, las secciones .reveal empiezan ocultas para animar su aparición
document.documentElement.classList.add('js');

// Si una portada de Open Library no existe, se quita la imagen y queda la portada dibujada con CSS.
// El evento error no burbujea, por eso se escucha en fase de captura desde el documento.
document.addEventListener('error', function (e) {
    if (e.target.matches && e.target.matches('.portada img')) e.target.remove();
}, true);
