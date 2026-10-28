// Filtro de la colección destacada por género
document.querySelectorAll('#generos .nav-link').forEach(boton => {
    boton.addEventListener('click', () => {
        document.querySelectorAll('#generos .nav-link').forEach(b => b.classList.remove('active'));
        boton.classList.add('active');
        const genero = boton.dataset.genero;
        document.querySelectorAll('.libro-col').forEach(col => {
            col.classList.toggle('d-none', genero !== 'todos' && col.dataset.genero !== genero);
        });
    });
});
