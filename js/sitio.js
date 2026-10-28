// Validación visual de Bootstrap para todos los formularios marcados con .needs-validation
document.querySelectorAll('.needs-validation').forEach(function (form) {
    form.addEventListener('submit', function (e) {
        if (!form.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
        }
        form.classList.add('was-validated');
    });
});

// Aparición suave de las secciones al entrar en pantalla
const sinMovimiento = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
const observador = new IntersectionObserver(entradas => {
    entradas.forEach(entrada => {
        if (!entrada.isIntersecting) return;
        entrada.target.classList.add('visible');
        observador.unobserve(entrada.target);
    });
}, { threshold: 0.12 });
document.querySelectorAll('.reveal').forEach(el => observador.observe(el));

// Contadores animados de las estadísticas
const contador = new IntersectionObserver(entradas => {
    entradas.forEach(entrada => {
        if (!entrada.isIntersecting) return;
        const el = entrada.target;
        const fin = Number(el.dataset.contador);
        const inicio = performance.now();
        const paso = ahora => {
            const t = Math.min((ahora - inicio) / 1400, 1);
            el.textContent = Math.round(fin * (1 - Math.pow(1 - t, 3)));
            if (t < 1) requestAnimationFrame(paso);
        };
        requestAnimationFrame(paso);
        contador.unobserve(el);
    });
}, { threshold: 0.6 });
if (!sinMovimiento) document.querySelectorAll('[data-contador]').forEach(el => contador.observe(el));
