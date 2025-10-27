'use strict';

(function () {
	const root = document.querySelector('[data-fusion-product]');
	if (!root) {
		return;
	}

	const selectAll = (selector, context = document) => Array.from(context.querySelectorAll(selector));
	const select = (selector, context = document) => context.querySelector(selector);

	/* =====================
	 * Gallery interactions
	 * ===================== */
	const gallery = select('[data-fusion-gallery]', root);
	const frames = selectAll('[data-fusion-gallery-frame]', gallery || root);
	const thumbs = selectAll('[data-fusion-gallery-thumb]', root);
	const btnPrev = select('[data-fusion-gallery-prev]', root);
	const btnNext = select('[data-fusion-gallery-next]', root);
	let currentFrame = 0;

	const activateFrame = (index) => {
		if (!frames.length) {
			return;
		}
		currentFrame = (index + frames.length) % frames.length;
		frames.forEach((frame, idx) => {
			frame.classList.toggle('is-active', idx === currentFrame);
		});
		thumbs.forEach((thumb, idx) => {
			thumb.classList.toggle('is-active', idx === currentFrame);
		});
		const activeThumb = thumbs[currentFrame];
		if (activeThumb?.scrollIntoView) {
			activeThumb.scrollIntoView({
				inline: 'center',
				block: 'nearest',
				behavior: 'smooth',
			});
		}
	};

	btnPrev?.addEventListener('click', () => activateFrame(currentFrame - 1));
	btnNext?.addEventListener('click', () => activateFrame(currentFrame + 1));
	thumbs.forEach((thumb) => {
		const index = parseInt(thumb.dataset.fusionGalleryThumb || '0', 10);
		thumb.addEventListener('click', () => activateFrame(index));
	});
	activateFrame(0);

	/* =====================
	 * Accordion toggles
	 * ===================== */
	const accordions = selectAll('[data-fusion-accordion]', root);
	accordions.forEach((accordion) => {
		const items = selectAll('.fusion-product__accordion-item', accordion);
		items.forEach((item) => {
			const trigger = select('.fusion-product__accordion-trigger', item);
			trigger?.addEventListener('click', () => {
				const isOpen = item.classList.toggle('is-open');
				if (!isOpen) {
					return;
				}
				items.forEach((other) => {
					if (other !== item) {
						other.classList.remove('is-open');
					}
				});
			});
		});
	});

	/* =====================
	 * Quote modal logic
	 * ===================== */
	const formatCurrency = (value, currency = 'USD') => {
		const safeValue = Number.isFinite(value) ? value : 0;
		try {
			return new Intl.NumberFormat('es-MX', {
				style: 'currency',
				currency,
				minimumFractionDigits: 2,
			}).format(safeValue);
		} catch (error) {
			return `${currency} ${safeValue.toFixed(2)}`;
		}
	};

const parseJSONDataset = (element, key) => {
    if (!element) {
        return null;
    }
    const raw = element.dataset ? element.dataset[key] : null;
    if (!raw) {
        return null;
    }
    try {
        return JSON.parse(raw);
    } catch (error) {
        return null;
    }
};

const parsePricing = (element) => {
    if (!element) {
        return [];
    }
    const parsed = parseJSONDataset(element, 'fusionPricing');
    return Array.isArray(parsed) ? parsed : [];
};

const quoteBoxes = selectAll('[data-fusion-quote]', root);
const quoteBox = quoteBoxes.length ? quoteBoxes[0] : null;
const quoteDialog = select('[data-fusion-quote-dialog]');
const openQuoteButtons = selectAll('[data-fusion-open-quote]', root);
const closeQuoteButtons = selectAll('[data-fusion-close-quote]', quoteDialog || document);
const counters = selectAll('[data-fusion-counter]', quoteDialog || document);
const totalDisplay = select('[data-fusion-quote-total]', quoteDialog || document);
const addToCartBtn = select('[data-fusion-add-to-cart]', quoteDialog || document);
const buyNowBtn = select('[data-fusion-buy-now]', quoteDialog || document);
const body = document.body;

const pricingData = parsePricing(quoteDialog || quoteBox);
const originPayloadRaw = parseJSONDataset(quoteDialog, 'fusionOrigins') || parseJSONDataset(quoteBox, 'fusionOrigins') || [];
const originPayload = Array.isArray(originPayloadRaw)
    ? originPayloadRaw
          .map((item) => {
              const key = item.location_key || item.value || item.key || item.location || item.label || '';
              const label = item.label || item.location || item.raw_label || key;
              return {
                  key,
                  location: item.location || label,
                  label,
                  rawLabel: item.raw_label || label,
                  adults: Number(item.adults) || 0,
                  children: Number(item.children) || 0,
                  currency: item.currency || (pricingData[0]?.currency || 'USD'),
              };
          })
          .filter((item) => item.key)
    : [];
const primaryPrice = pricingData.length ? pricingData[0] : null;
const productId = quoteDialog?.dataset.productId || quoteBox?.dataset.productId;
const productType = quoteDialog?.dataset.productType || quoteBox?.dataset.productType || '';
const datePayload = parseJSONDataset(quoteDialog, 'fusionDates') || parseJSONDataset(quoteBox, 'fusionDates') || [];
const dateSelect = select('[data-fusion-date]', quoteDialog || document);
let selectedDateIndex = quoteDialog?.dataset.defaultDate || quoteBox?.dataset.defaultDate || '';
const originSelects = selectAll('[data-fusion-origin]', document);
const defaultOriginAttr = quoteDialog?.dataset.defaultOrigin || quoteBox?.dataset.defaultOrigin || '';
let selectedOrigin = defaultOriginAttr || originSelects[0]?.value || (originPayload[0]?.key || '');
if (!selectedOrigin && originPayload.length) {
    selectedOrigin = originPayload[0].key;
}

const syncOriginSelects = () => {
    if (!selectedOrigin && originPayload.length) {
        selectedOrigin = originPayload[0].key;
    }
    originSelects.forEach((sel) => {
        if (!sel) {
            return;
        }
        if (!selectedOrigin) {
            return;
        }
        const hasOption = Array.from(sel.options || []).some((opt) => opt.value === selectedOrigin);
        if (hasOption) {
            sel.value = selectedOrigin;
        }
    });
};

syncOriginSelects();

const resolveOriginPrice = () => {
    if (Array.isArray(originPayload) && originPayload.length) {
        const match = originPayload.find(
            (item) => item.key === selectedOrigin || item.location === selectedOrigin || item.label === selectedOrigin
        );
        const source = match || originPayload[0];
        if (source) {
            if (!selectedOrigin || selectedOrigin !== source.key) {
                selectedOrigin = source.key;
                syncOriginSelects();
            }
            return {
                adults: Number(source.adults) || 0,
                children: Number(source.children) || 0,
                currency: source.currency || (primaryPrice?.currency || 'USD'),
            };
        }
    }
    return primaryPrice;
};

originSelects.forEach((sel) => {
    sel.addEventListener('change', (event) => {
        selectedOrigin = event.target.value || '';
        if (!selectedOrigin && originPayload.length) {
            selectedOrigin = originPayload[0].key;
        }
        syncOriginSelects();
        updateTotal();
    });
});

if (dateSelect) {
    if (selectedDateIndex) {
        dateSelect.value = selectedDateIndex;
    }
    dateSelect.addEventListener('change', (event) => {
        selectedDateIndex = event.target.value || '';
    });
}

	const getCounterState = () => {
		const values = counters.map((counter) => {
			const valueEl = select('[data-fusion-counter-value]', counter);
			const labelEl = select('.fusion-product__quote-control-label span', counter);
			const type = labelEl ? labelEl.textContent?.toLowerCase() : '';
			const value = valueEl ? parseInt(valueEl.textContent || '0', 10) : 0;
			return { element: counter, valueEl, type, value };
		});
		const adultsState = values.find((item) => item.type && item.type.includes('adult'));
		const childrenState = values.find((item) => item.type && item.type.includes('menor'));
		return {
			adults: adultsState || values[0],
			children: childrenState || values[1] || values[0],
			all: values,
		};
	};

const updateTotal = () => {
    const priceSource = resolveOriginPrice();
    if (!totalDisplay || !priceSource) {
        return;
    }
    const state = getCounterState();
    const adultsTotal = (state.adults?.value || 0) * (priceSource.adults || 0);
    const childrenTotal = (state.children?.value || 0) * (priceSource.children || 0);
    const total = adultsTotal + childrenTotal;
    totalDisplay.textContent = formatCurrency(total, priceSource.currency || 'USD');
};

	const adjustCounter = (counterEl, delta) => {
		const valueEl = select('[data-fusion-counter-value]', counterEl);
		if (!valueEl) {
			return;
		}
		const current = parseInt(valueEl.textContent || '0', 10);
		const nextValue = Math.max(0, Math.min(30, current + delta));
		valueEl.textContent = String(nextValue);
		updateTotal();
	};

	counters.forEach((counter) => {
		const down = select('[data-fusion-counter-down]', counter);
		const up = select('[data-fusion-counter-up]', counter);
		down?.addEventListener('click', () => adjustCounter(counter, -1));
		up?.addEventListener('click', () => adjustCounter(counter, 1));
	});

const toggleDialog = (show) => {
    if (!quoteDialog) {
        return;
    }
    const shouldShow = typeof show === 'boolean' ? show : quoteDialog.hasAttribute('hidden');
    if (shouldShow) {
        if (dateSelect) {
            if (!selectedDateIndex) {
                const viableOption = Array.from(dateSelect.options).find((option) => option.value && !option.disabled);
                if (viableOption) {
                    selectedDateIndex = viableOption.value;
                }
            }
            if (selectedDateIndex) {
                dateSelect.value = selectedDateIndex;
            }
        }
        if (!selectedOrigin && originPayload.length) {
            selectedOrigin = originPayload[0].key;
        }
        syncOriginSelects();
        quoteDialog.removeAttribute('hidden');
        body.classList.add('fusion-product--modal-open');
        updateTotal();
    } else {
        quoteDialog.setAttribute('hidden', '');
        body.classList.remove('fusion-product--modal-open');
    }
};

openQuoteButtons.forEach((btn) => {
    btn.addEventListener('click', (event) => {
        event.preventDefault();
        toggleDialog(true);
    });
});
	closeQuoteButtons.forEach((button) => {
		button.addEventListener('click', () => toggleDialog(false));
	});
	if (quoteDialog) {
		quoteDialog.addEventListener('click', (event) => {
			if (event.target === quoteDialog) {
				toggleDialog(false);
			}
		});
		document.addEventListener('keydown', (event) => {
			if (event.key === 'Escape') {
				toggleDialog(false);
			}
		});
	}

	const displayNotice = (message, type = 'info') => {
		if (!message) {
			return;
		}
		const container = document.createElement('div');
		container.className = `fusion-product__notice fusion-product__notice--${type}`;
		container.textContent = message;
		document.body.appendChild(container);
		requestAnimationFrame(() => container.classList.add('is-visible'));
		setTimeout(() => {
			container.classList.remove('is-visible');
			setTimeout(() => container.remove(), 350);
		}, 2800);
	};

	const handleAddToCart = async (redirect) => {
		if (!productId) {
			displayNotice('No pudimos identificar el producto.', 'error');
			return;
		}
		const counterState = getCounterState();
		const adultsCount = Math.max(0, counterState.adults?.value || 0);
		const childrenCount = Math.max(0, counterState.children?.value || 0);
		const quantity = Math.max(1, adultsCount + childrenCount);
		if (productType === 'viaje' && Array.isArray(datePayload) && datePayload.length) {
			if (!selectedDateIndex) {
				displayNotice('Selecciona una fecha disponible.', 'error');
				return;
			}
		}
		if (productType === 'viaje' && originPayload.length > 1) {
			if (!selectedOrigin) {
				displayNotice('Selecciona un punto de salida.', 'error');
				return;
			}
		}

		if (redirect) {
			const url = new URL(window.location.href);
			url.searchParams.set('add-to-cart', String(productId));
			url.searchParams.set('quantity', String(quantity));
			if (productType === 'viaje') {
				url.searchParams.set('viaje_adultos', String(adultsCount));
				url.searchParams.set('viaje_menores', String(childrenCount));
			}
			if (productType === 'viaje' && Array.isArray(datePayload) && datePayload.length) {
				url.searchParams.set('viaje_fecha_idx', String(selectedDateIndex));
			}
			if (productType === 'viaje' && originPayload.length) {
				const originValue = selectedOrigin || originPayload[0]?.key || '';
				if (originValue) {
					url.searchParams.set('viaje_origen', String(originValue));
				}
			}
			window.location.href = url.toString();
			return;
		}

		try {
			const ajaxUrl = window.wc_add_to_cart_params?.wc_ajax_url?.replace('%%endpoint%%', 'add_to_cart') || '/?wc-ajax=add_to_cart';
			const params = new URLSearchParams({
				product_id: String(productId),
				quantity: String(quantity),
			});
			if (productType === 'viaje') {
				params.set('viaje_adultos', String(adultsCount));
				params.set('viaje_menores', String(childrenCount));
			}
			if (productType === 'viaje' && Array.isArray(datePayload) && datePayload.length) {
				params.set('viaje_fecha_idx', String(selectedDateIndex));
			}
			if (productType === 'viaje' && originPayload.length) {
				const originValue = selectedOrigin || originPayload[0]?.key || '';
				if (originValue) {
					params.set('viaje_origen', String(originValue));
				}
			}
			const response = await fetch(ajaxUrl, {
				method: 'POST',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
				},
				body: params,
				credentials: 'same-origin',
			});

			if (!response.ok) {
				throw new Error('Request failed');
			}

			const data = await response.json();

			if (data?.error) {
				displayNotice(data.error, 'error');
				return;
			}

			if (window.jQuery) {
				window.jQuery(document.body).trigger('added_to_cart');
				window.jQuery(document.body).trigger('wc_fragment_refresh');
			}
			displayNotice('Tour añadido al carrito.');
			toggleDialog(false);
		} catch (error) {
			displayNotice('No pudimos añadir el tour. Intenta nuevamente.', 'error');
		}
	};

	addToCartBtn?.addEventListener('click', () => handleAddToCart(false));
	buyNowBtn?.addEventListener('click', () => handleAddToCart(true));

	updateTotal();
})();
