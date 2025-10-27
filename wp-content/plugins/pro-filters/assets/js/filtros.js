document.addEventListener('DOMContentLoaded', () => {
  const desktopForm = document.querySelector('#filtro-productos');
  const mobileForm = document.querySelector('#filtro-productos-mobile');
  const forms = [desktopForm, mobileForm].filter(Boolean);
  const form = desktopForm || mobileForm;
  const contenedor = document.querySelector('#productos-filtrados');
  const ordenarSelect = document.querySelector('#ordenar');
  const contadorProductos = Array.from(document.querySelectorAll('#contador-productos, #contador-productos-mobile'));
  const getLoopWidgets = () =>
    Array.from(document.querySelectorAll('.elementor-widget-loop-grid[data-id]'));
  const hasLoopWidgets = getLoopWidgets().length > 0;
  const convertFieldsetToCollapsible = fieldset => {
    if (!fieldset || fieldset.dataset.collapsibleReady === '1') {
      return;
    }

    const legend = fieldset.querySelector(':scope > legend');
    const title = legend ? legend.textContent.trim() : (fieldset.getAttribute('aria-label') || '').trim();
    const hasChecked = !!fieldset.querySelector('input[type="checkbox"]:checked');
    const details = document.createElement('details');
    details.dataset.collapsibleReady = '1';
    details.classList.add('wfp-collapsible');
    fieldset.classList.forEach(cls => details.classList.add(cls));

    const summary = document.createElement('summary');
    summary.className = 'wfp-collapsible__summary';

    const titleSpan = document.createElement('span');
    titleSpan.className = 'wfp-collapsible__title';
    titleSpan.textContent = title || fieldset.getAttribute('data-title') || fieldset.getAttribute('data-label') || '';
    if (!titleSpan.textContent.trim()) {
      titleSpan.textContent = 'Filtro';
    }
    summary.appendChild(titleSpan);

    const content = document.createElement('div');
    content.className = 'wfp-collapsible__content';

    const children = Array.from(fieldset.childNodes);
    children.forEach(node => {
      if (node === legend) {
        return;
      }
      if (typeof Node !== 'undefined' && node.nodeType === Node.TEXT_NODE && node.textContent.trim() === '') {
        return;
      }
      content.appendChild(node);
    });

    details.appendChild(summary);
    details.appendChild(content);

    if (hasChecked) {
      details.setAttribute('open', '');
    }

    fieldset.replaceWith(details);
  };

  const enhanceMobileFilters = () => {
    if (!mobileForm) {
      return;
    }

    const fieldsets = mobileForm.querySelectorAll('fieldset.wfp-filter-group');
    fieldsets.forEach(fieldset => {
      convertFieldsetToCollapsible(fieldset);
    });
  };

  enhanceMobileFilters();

  if (mobileForm && typeof MutationObserver === 'function') {
    const observer = new MutationObserver(() => {
      enhanceMobileFilters();
    });
    observer.observe(mobileForm, { childList: true, subtree: true });
  }

  const escapeSelector = value => {
    if (window.CSS && typeof window.CSS.escape === 'function') {
      return window.CSS.escape(value);
    }
    return String(value).replace(/["\\]/g, '\\$&');
  };

  const enforceSingleSelection = checkbox => {
    if (!checkbox || checkbox.type !== 'checkbox' || !checkbox.dataset.rootId || !checkbox.checked) {
      return;
    }

    const selector = `input[type="checkbox"][data-root-id="${escapeSelector(checkbox.dataset.rootId)}"]`;
    document.querySelectorAll(selector).forEach(other => {
      if (other !== checkbox) {
        other.checked = false;
      }
    });
  };

  const syncForms = sourceForm => {
    if (!sourceForm || forms.length < 2) {
      return;
    }

    const sourceInputs = sourceForm.querySelectorAll('input[type="checkbox"]');

    forms.forEach(targetForm => {
      if (targetForm === sourceForm) {
        return;
      }

      const targetMap = new Map();
      targetForm.querySelectorAll('input[type="checkbox"]').forEach(input => {
        targetMap.set(`${input.name}|${input.value}`, input);
      });

      sourceInputs.forEach(input => {
        const key = `${input.name}|${input.value}`;
        const target = targetMap.get(key);
        if (target) {
          target.checked = input.checked;
        }
      });
    });
  };

  forms.forEach(formEl => {
    formEl
      .querySelectorAll('input[type="checkbox"][data-root-id]')
      .forEach(input => {
        if (input.checked) {
          enforceSingleSelection(input);
        }
      });
  });

  document.addEventListener('change', event => {
    const input = event.target;
    if (input?.matches?.('input[type="checkbox"][data-root-id]')) {
      enforceSingleSelection(input);
    }
  });

  if (!form || (!contenedor && !hasLoopWidgets)) {
    window.wfpActualizarProductos = () => {};
    return;
  }

  const getFormValues = (sourceForm = form) => {
    if (sourceForm && form && sourceForm !== form) {
      syncForms(sourceForm);
    }

    const activeForm = form || sourceForm;
    const formData = activeForm ? new FormData(activeForm) : new FormData();
    const ordenar = ordenarSelect?.value || '';

    if (ordenar) {
      formData.set('ordenar', ordenar);
    } else {
      formData.delete('ordenar');
    }

    return formData;
  };

  const actualizarContador = (source = document) => {
    const totalEl = source.querySelector ? source.querySelector('.productos-cantidad') : null;
    if (totalEl && contadorProductos.length) {
      const total = totalEl.dataset.total || '0';
      const texto = `${total} producto${total === '1' ? '' : 's'} encontrados`;
      contadorProductos.forEach(el => {
        el.textContent = texto;
      });
    }
  };

  const syncElementorLoopGrids = doc => {
    const widgets = doc.querySelectorAll('.elementor-widget-loop-grid[data-id]');
    if (!widgets.length) return;

    widgets.forEach(widget => {
      const id = widget.getAttribute('data-id');
      if (!id) return;

      const currentWidget = document.querySelector(`.elementor-widget-loop-grid[data-id="${id}"]`);
      if (!currentWidget) return;

      const newLoop = widget.querySelector('.elementor-loop-container');
      const currentLoop = currentWidget.querySelector('.elementor-loop-container');
      if (newLoop && currentLoop) {
        currentLoop.innerHTML = newLoop.innerHTML;
      }

      const newPagination = widget.querySelector('.e-loop__pagination');
      const currentPagination = currentWidget.querySelector('.e-loop__pagination');
      if (currentPagination) {
        if (newPagination) {
          currentPagination.innerHTML = newPagination.innerHTML;
        } else {
          currentPagination.innerHTML = '';
        }
      } else if (newPagination) {
        const container = currentWidget.querySelector('.elementor-loop-container');
        if (container) {
          container.insertAdjacentElement('afterend', newPagination.cloneNode(true));
        }
      }

      if (window.elementorFrontend && window.elementorFrontend.elementsHandler && window.jQuery) {
        window.elementorFrontend.elementsHandler.runReadyTrigger(window.jQuery(currentWidget));
      }
    });
  };

  const toggleLoopWidgetsBusy = busy => {
    getLoopWidgets().forEach(widget => {
      if (busy) {
        widget.setAttribute('aria-busy', 'true');
        widget.classList.add('wfp-loop-updating');
      } else {
        widget.removeAttribute('aria-busy');
        widget.classList.remove('wfp-loop-updating');
      }
    });
  };

  const actualizarProductos = (pagina = 1, sourceForm = form) => {
    const formData = getFormValues(sourceForm);
    formData.set('pagina', pagina);

    const paramsForHistory = new URLSearchParams();
    formData.forEach((value, key) => {
      paramsForHistory.append(key, value);
    });
    const historyParams = paramsForHistory.toString();
    const historyUrl = historyParams ? `${window.location.pathname}?${historyParams}` : window.location.pathname;

    if (contenedor) {
      contenedor.innerHTML = '<p>Cargando productos...</p>';
    }
    toggleLoopWidgetsBusy(true);

    const canUseAjaxEndpoint = !hasLoopWidgets && window.filtroAjax?.ajax_url;

    if (canUseAjaxEndpoint) {
      const requestData = new FormData();
      formData.forEach((value, key) => {
        requestData.append(key, value);
      });
      requestData.append('action', 'wfp_fetch_products');
      if (window.filtroAjax?.nonce) {
        requestData.append('nonce', window.filtroAjax.nonce);
      }

      fetch(window.filtroAjax.ajax_url, {
        method: 'POST',
        body: requestData,
        credentials: 'same-origin',
      })
        .then(res => res.json())
        .then(response => {
          if (!response?.success) {
            throw new Error(response?.data?.message || 'Error al cargar productos');
          }
          const data = response.data || {};
          if (contenedor && typeof data.html === 'string') {
            contenedor.innerHTML = data.html;
            contenedor.scrollIntoView({ behavior: 'smooth' });
          }
          actualizarContador(contenedor);
          if (history.replaceState) {
            history.replaceState(null, '', historyUrl);
          }
        })
        .catch(console.error)
        .finally(() => {
          toggleLoopWidgetsBusy(false);
        });

      return;
    }

    fetch(historyUrl)
      .then(res => res.text())
      .then(html => {
        const doc = new DOMParser().parseFromString(html, 'text/html');
        const nuevos = doc.querySelector('#productos-filtrados');
        if (nuevos && contenedor) {
          contenedor.innerHTML = nuevos.innerHTML;
          contenedor.scrollIntoView({ behavior: 'smooth' });
        }
        syncElementorLoopGrids(doc);
        actualizarContador(doc);
        if (history.replaceState) {
          history.replaceState(null, '', historyUrl);
        }
      })
      .catch(console.error)
      .finally(() => {
        toggleLoopWidgetsBusy(false);
      });
  };

  if (ordenarSelect) {
    ordenarSelect.addEventListener('change', () => actualizarProductos(1));
  }

  forms.forEach(formEl => {
    formEl.addEventListener('change', e => {
      if (e.target.type === 'checkbox') {
        if (e.target.dataset.rootId) {
          enforceSingleSelection(e.target);
        }
        actualizarProductos(1, formEl);
      }
    });
  });

  document.addEventListener('click', e => {
    const link = e.target.closest('.ajax-pagination a');
    if (link) {
      e.preventDefault();
      const page = parseInt(link.dataset.page, 10);
      if (page) actualizarProductos(page);
      return;
    }

    const loopLink = e.target.closest('.e-loop__pagination a');
    if (loopLink) {
      e.preventDefault();
      let page = parseInt(loopLink.dataset.page, 10);
      if (!page) {
        try {
          const url = new URL(loopLink.href, window.location.origin);
          page = parseInt(url.searchParams.get('paged') || url.searchParams.get('page'), 10);
        } catch (err) {
          page = NaN;
        }
      }
      if (page) actualizarProductos(page);
    }
  });

  actualizarContador();

  window.wfpActualizarProductos = (pagina = 1, sourceForm = form) => {
    actualizarProductos(pagina, sourceForm);
  };
});
