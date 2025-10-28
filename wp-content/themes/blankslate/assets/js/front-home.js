'use strict';

(function () {
	const body = document.body;
	const isFusionHome = body.classList.contains('page-template-template-fusion-home');

	const $ = (selector, context = document) => context.querySelector(selector);
	const $$ = (selector, context = document) => Array.from(context.querySelectorAll(selector));

	const scrollToActivities = () => {
		const target = document.getElementById('actividades');
		if (target) {
			target.scrollIntoView({ behavior: 'smooth', block: 'start' });
		}
	};

	$$('.fusion-scroll-activities').forEach((button) => {
		button.addEventListener('click', (event) => {
			const tag = button.tagName.toLowerCase();
			if (tag === 'button') {
				event.preventDefault();
				scrollToActivities();
				if (mobileMenu && !mobileMenu.classList.contains('hidden')) {
					toggleMobileMenu(false);
				}
				return;
			}

			if (isFusionHome) {
				const href = button.getAttribute('href') || '';
				if (href.includes('#actividades')) {
					event.preventDefault();
					scrollToActivities();
					if (mobileMenu && !mobileMenu.classList.contains('hidden')) {
						toggleMobileMenu(false);
					}
				}
			}
		});
	});

	const mobileMenuToggle = $('.fusion-mobile-menu-toggle');
	const mobileMenu = $('.fusion-mobile-menu');

	const toggleMobileMenu = (forceOpen) => {
		if (!mobileMenu) {
			return;
		}
		const shouldOpen = typeof forceOpen === 'boolean' ? forceOpen : mobileMenu.classList.contains('hidden');
		mobileMenu.classList.toggle('hidden', !shouldOpen);
		body.classList.toggle('overflow-hidden', shouldOpen);
		if (mobileMenuToggle) {
			mobileMenuToggle.classList.toggle('is-active', shouldOpen);
			mobileMenuToggle.setAttribute('aria-expanded', String(shouldOpen));
		}
	};

	if (mobileMenuToggle) {
		mobileMenuToggle.addEventListener('click', () => toggleMobileMenu());
	}

	if (mobileMenu) {
		$$('a, button', mobileMenu).forEach((element) => {
			element.addEventListener('click', () => toggleMobileMenu(false));
		});
	}

	const languageToggle = $('.fusion-language-toggle');
	if (languageToggle) {
		const label = languageToggle.querySelector('[data-language-label]');
		const languages = ['ES', 'EN'];
		let index = 0;
		languageToggle.addEventListener('click', () => {
			index = (index + 1) % languages.length;
			if (label) {
				label.textContent = languages[index];
			}
		});
	}

	const currencyToggle = $('.fusion-currency-toggle');
	const currencySwitcher = $('[data-currency-switcher]');
	const dropdown = currencySwitcher ? currencySwitcher.querySelector('[data-currency-dropdown]') : null;
	if (currencyToggle && currencySwitcher && dropdown) {
		const label = currencySwitcher.querySelector('[data-currency-label]');
		const optionButtons = dropdown.querySelectorAll('[data-currency-code]');
		const activeClass = 'is-open';

		const closeDropdown = () => {
			if (dropdown) {
				dropdown.classList.add('hidden');
			}
			currencySwitcher.classList.remove(activeClass);
			currencyToggle.setAttribute('aria-expanded', 'false');
		};

		const openDropdown = () => {
			if (dropdown) {
				dropdown.classList.remove('hidden');
			}
			currencySwitcher.classList.add(activeClass);
			currencyToggle.setAttribute('aria-expanded', 'true');
		};

		const toggleDropdown = (event) => {
			event.preventDefault();
			event.stopPropagation();
			if (!dropdown) {
				return;
			}
			if (dropdown.classList.contains('hidden')) {
				openDropdown();
			} else {
				closeDropdown();
			}
		};

		const maybeCloseOnOutside = (event) => {
			if (!currencySwitcher.contains(event.target)) {
				closeDropdown();
			}
		};

		currencyToggle.addEventListener('click', toggleDropdown);
		document.addEventListener('click', maybeCloseOnOutside);

		if (optionButtons && optionButtons.length > 0) {
			const updateActiveState = (targetButton) => {
				optionButtons.forEach((button) => {
					button.classList.remove('is-active');
					button.removeAttribute('data-active');
				});
				targetButton.classList.add('is-active');
				targetButton.setAttribute('data-active', 'true');
			};

			optionButtons.forEach((button) => {
				button.addEventListener('click', (event) => {
					event.preventDefault();
					event.stopPropagation();
					const targetCurrency = button.getAttribute('data-currency-code');
					const targetLabel = button.getAttribute('data-currency-label') || targetCurrency;

					if (!targetCurrency) {
						closeDropdown();
						return;
					}

					updateActiveState(button);
					if (label) {
						label.textContent = targetLabel;
					}
					closeDropdown();

					const currentCurrency =
						window.woocs_current_currency && window.woocs_current_currency.name
							? window.woocs_current_currency.name
							: null;
					if (currentCurrency && currentCurrency === targetCurrency) {
						return;
					}

					if (typeof window.woocs_redirect === 'function') {
						window.woocs_redirect(targetCurrency);
					} else {
						const fallbackForm = document.createElement('form');
						fallbackForm.method = 'post';
						fallbackForm.action = window.location.href;
						fallbackForm.classList.add('woocs-fallback-form');
						fallbackForm.innerHTML = `<input type="hidden" name="woocommerce-currency-switcher" value="${targetCurrency}" />`;
						document.body.appendChild(fallbackForm);
						fallbackForm.submit();
					}
				});
			});
		}
	} else if (currencyToggle) {
		const label = currencyToggle.querySelector('[data-currency-label]');
		const currencies = ['MXN', 'USD'];
		let index = 0;
		currencyToggle.addEventListener('click', () => {
			index = (index + 1) % currencies.length;
			if (label) {
				label.textContent = currencies[index];
			}
		});
	}

	const tourTrack = $('[data-fusion-tour-track]');
	if (tourTrack) {
		const prevBtn = $('.fusion-tour-prev');
		const nextBtn = $('.fusion-tour-next');
		const getStep = () => {
			const firstCard = tourTrack.querySelector('[data-fusion-tour-card]');
			if (!firstCard) {
				return 320;
			}
			const cardRect = firstCard.getBoundingClientRect();
			const styles = getComputedStyle(tourTrack);
			const gap = parseFloat(styles.gap || styles.columnGap || '0') || 0;
			return cardRect.width + gap;
		};
		const scrollByStep = (direction) => {
			tourTrack.scrollBy({
				left: direction * getStep(),
				behavior: 'smooth',
			});
		};
		if (prevBtn) {
			prevBtn.addEventListener('click', () => scrollByStep(-1));
		}
		if (nextBtn) {
			nextBtn.addEventListener('click', () => scrollByStep(1));
		}
	}

	const activityContainer = $('[data-fusion-activities]');
	if (activityContainer) {
		const cards = $$('.fusion-activity-card', activityContainer);
		const prev = $('.fusion-activity-prev');
		const next = $('.fusion-activity-next');
		let current = 0;

		const classNames = ['is-center', 'is-next', 'is-next-2', 'is-prev-2', 'is-prev'];
		const clearClasses = (card) => {
			card.classList.remove(...classNames);
		};

		const updateActivityCards = () => {
			const total = cards.length;
			const singleColumn = window.matchMedia('(max-width: 768px)').matches;
			cards.forEach((card, index) => {
				clearClasses(card);
				card.style.display = '';
				const relative = (index - current + total) % total;

				if (relative === 0) {
					card.classList.add('is-center');
				} else if (relative === 1) {
					if (singleColumn) {
						card.style.display = 'none';
					} else {
						card.classList.add('is-next');
					}
				} else if (relative === 2) {
					if (singleColumn) {
						card.style.display = 'none';
					} else {
						card.classList.add('is-next-2');
					}
				} else if (relative === total - 1) {
					if (singleColumn) {
						card.style.display = 'none';
					} else {
						card.classList.add('is-prev');
					}
				} else if (relative === total - 2) {
					if (singleColumn) {
						card.style.display = 'none';
					} else {
						card.classList.add('is-prev-2');
					}
				} else if (singleColumn) {
					card.style.display = 'none';
				} else {
					card.style.display = 'none';
				}
			});
		};

		updateActivityCards();

		const moveActivity = (direction) => {
			if (!cards.length) {
				return;
			}
			current = (current + direction + cards.length) % cards.length;
			updateActivityCards();
		};

		if (prev) {
			prev.addEventListener('click', () => moveActivity(-1));
		}
		if (next) {
			next.addEventListener('click', () => moveActivity(1));
		}
		window.addEventListener('resize', () => {
			requestAnimationFrame(updateActivityCards);
		});
	}

	const clientCarousel = $('[data-fusion-clients]');
	if (clientCarousel) {
		const track = $('[data-fusion-clients-track]', clientCarousel);
		const slides = track ? $$('.w-full', track) : [];
		const dots = $$('[data-client-dot]');
		let currentIndex = 0;
		let timerId = null;

		const updateCarousel = () => {
			if (!track || !slides.length) {
				return;
			}
			const percentage = currentIndex * 100;
			track.style.transform = `translateX(-${percentage}%)`;
			dots.forEach((dot, idx) => {
				if (idx === currentIndex) {
					dot.classList.add('bg-[#0070C0]', 'w-6');
					dot.classList.remove('bg-gray-300', 'w-2');
				} else {
					dot.classList.remove('bg-[#0070C0]', 'w-6');
					dot.classList.add('bg-gray-300', 'w-2');
				}
			});
		};

		const goToSlide = (index) => {
			currentIndex = (index + slides.length) % slides.length;
			updateCarousel();
		};

		const startAuto = () => {
			stopAuto();
			timerId = window.setInterval(() => {
				goToSlide(currentIndex + 1);
			}, 3500);
		};

		const stopAuto = () => {
			if (timerId) {
				clearInterval(timerId);
				timerId = null;
			}
		};

		dots.forEach((dot) => {
			dot.addEventListener('click', () => {
				const idx = Number(dot.getAttribute('data-client-dot'));
				if (!Number.isNaN(idx)) {
					goToSlide(idx);
					startAuto();
				}
			});
		});

		clientCarousel.addEventListener('mouseenter', stopAuto);
		clientCarousel.addEventListener('mouseleave', startAuto);

		updateCarousel();
		startAuto();
	}

	const faqTriggers = $$('.fusion-faq-trigger');
	faqTriggers.forEach((trigger) => {
		const content = trigger.parentElement ? trigger.parentElement.querySelector('.fusion-faq-content') : null;
		if (!content) {
			return;
		}
		content.style.maxHeight = '0px';

		trigger.addEventListener('click', () => {
			const isExpanded = trigger.getAttribute('aria-expanded') === 'true';
			trigger.setAttribute('aria-expanded', String(!isExpanded));
			content.classList.toggle('is-open', !isExpanded);

			if (!isExpanded) {
				content.style.maxHeight = `${content.scrollHeight}px`;
			} else {
				content.style.maxHeight = '0px';
			}

			if (!isExpanded) {
				faqTriggers.forEach((otherTrigger) => {
					if (otherTrigger !== trigger) {
						const otherContent = otherTrigger.parentElement ? otherTrigger.parentElement.querySelector('.fusion-faq-content') : null;
						if (otherContent) {
							otherTrigger.setAttribute('aria-expanded', 'false');
							otherContent.classList.remove('is-open');
							otherContent.style.maxHeight = '0px';
						}
					}
				});
			}
		});
	});

})();
