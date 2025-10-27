document.addEventListener('DOMContentLoaded', function () {
  const btnAbrir = document.getElementById('abrir-filtros-mobile');
  const offcanvas = document.getElementById('filtro-offcanvas');
  const btnAplicar = document.getElementById('aplicar-filtros-mobile');
  let overlay = document.getElementById('filtro-offcanvas-overlay');

  if (!btnAbrir || !offcanvas || !btnAplicar) return;

  const form = offcanvas.querySelector('form');

  const handleOutsidePointer = event => {
    if (!offcanvas.contains(event.target) && event.target !== btnAbrir) {
      cerrar();
    }
  };

  const cerrar = () => {
    offcanvas.classList.remove('abierto');
    offcanvas.classList.add('cerrado');
    document.body.style.overflow = '';
    const targetOverlay = document.getElementById('filtro-offcanvas-overlay');
    if (targetOverlay) {
      targetOverlay.classList.remove('visible');
      targetOverlay.style.display = 'none';
    }
    document.removeEventListener('mousedown', handleOutsidePointer);
    document.removeEventListener('touchstart', handleOutsidePointer);
  };

  btnAbrir.addEventListener('click', function () {
    offcanvas.classList.add('abierto');
    offcanvas.classList.remove('cerrado');
    document.body.style.overflow = 'hidden';

    if (!overlay) {
      overlay = document.createElement('div');
      overlay.id = 'filtro-offcanvas-overlay';
      document.body.appendChild(overlay);
    }

    overlay.addEventListener('click', cerrar);

    overlay.classList.add('visible');
    overlay.style.display = '';
    document.addEventListener('mousedown', handleOutsidePointer);
    document.addEventListener('touchstart', handleOutsidePointer);
  });

  btnAplicar.addEventListener('click', function () {
    if (form) {
      if (typeof window.wfpActualizarProductos === 'function') {
        window.wfpActualizarProductos(1, form);
      } else {
        const formData = new FormData(form);
        const params = new URLSearchParams(formData).toString();
        const contenedor = document.querySelector('#productos-filtrados');
        if (contenedor) {
          contenedor.innerHTML = '<p>Cargando productos...</p>';
        }

        fetch(`${window.location.pathname}?${params}`)
          .then(response => response.text())
          .then(html => {
            const doc = new DOMParser().parseFromString(html, 'text/html');
            const nuevos = doc.querySelector('#productos-filtrados');
            if (nuevos && contenedor) {
              contenedor.innerHTML = nuevos.innerHTML;
              contenedor.scrollIntoView({ behavior: 'smooth' });
            }
            history.replaceState(null, '', `${window.location.pathname}?${params}`);
          })
          .catch(console.error);
      }
    }
    cerrar();
  });
});
