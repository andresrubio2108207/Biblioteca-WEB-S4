// Búsqueda y filtro por estado en el listado
const buscar = document.getElementById('buscar');
const filas = document.querySelectorAll('tbody tr[data-estado]');
let estadoActivo = '';

// Minúsculas y sin tildes, para que "Márquez" encuentre "Marquez"
const normalizar = t => t.toLowerCase().normalize('NFD').replace(/\p{Diacritic}/gu, '');

function aplicarFiltro() {
    const texto = normalizar(buscar.value);
    let visibles = 0;
    filas.forEach(fila => {
        const coincide = normalizar(fila.textContent).includes(texto)
            && (!estadoActivo || fila.dataset.estado === estadoActivo);
        fila.classList.toggle('d-none', !coincide);
        if (coincide) visibles++;
    });
    document.getElementById('sinResultados').classList.toggle('d-none', visibles > 0);
}

buscar.addEventListener('input', aplicarFiltro);
if (buscar.value) aplicarFiltro(); // búsqueda que llega desde la portada
document.querySelectorAll('#filtros .btn-filtro').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('#filtros .btn-filtro').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        estadoActivo = btn.dataset.estado;
        aplicarFiltro();
    });
});

// Pasa los datos del préstamo al modal de eliminación
document.getElementById('modalEliminar').addEventListener('show.bs.modal', e => {
    document.getElementById('idEliminar').value = e.relatedTarget.dataset.id;
    document.getElementById('libroEliminar').textContent = e.relatedTarget.dataset.libro;
});
