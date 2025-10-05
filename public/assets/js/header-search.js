(function () {
  const formatPrice = (value) => {
    const amount = Number(value);

    if (Number.isNaN(amount)) {
      return '';
    }

    try {
      return new Intl.NumberFormat('fr-FR', {
        style: 'currency',
        currency: 'EUR',
        minimumFractionDigits: 2,
      }).format(amount / 100);
    } catch (error) {
      return `${amount / 100} €`;
    }
  };

  const debounce = (fn, delay = 250) => {
    let timer;
    return (...args) => {
      clearTimeout(timer);
      timer = setTimeout(() => fn(...args), delay);
    };
  };

  const initHeaderSearch = (root) => {
    const endpoint = root.dataset.endpoint || '';
    const placeholder = root.dataset.placeholder || '';
    const toggle = root.querySelector('.js-header-search-open');
    const wrap = root.querySelector('.search_wrap');
    const overlay = root.querySelector('.search_overlay');
    const input = root.querySelector('.js-header-search-input');
    const results = root.querySelector('.js-header-search-results');
    const form = root.querySelector('form');
    let abortController = null;
    let isOpen = false;

    if (input && placeholder) {
      input.placeholder = placeholder;
    }

    const setExpanded = (state) => {
      toggle?.setAttribute('aria-expanded', state ? 'true' : 'false');
      wrap?.setAttribute('aria-hidden', state ? 'false' : 'true');
    };

    setExpanded(false);

    const clearResults = () => {
      if (!results) {
        return;
      }

      results.innerHTML = '';
      results.hidden = true;
    };

    const onKeyDown = (event) => {
      if (event.key === 'Escape') {
        hide();
      }
    };

    const onOutsideClick = (event) => {
      if (!root.contains(event.target)) {
        hide();
      }
    };

    const show = () => {
      if (isOpen) {
        return;
      }

      isOpen = true;
      root.classList.add('is-open');
      wrap?.classList.add('open');
      wrap?.style.setProperty('display', 'block');
      overlay?.style.setProperty('display', 'block');
      setExpanded(true);
      input?.focus({ preventScroll: true });
      document.addEventListener('keydown', onKeyDown);
      document.addEventListener('click', onOutsideClick);
    };

    const hide = () => {
      if (!isOpen) {
        return;
      }

      isOpen = false;
      root.classList.remove('is-open');
      wrap?.classList.remove('open');
      wrap?.style.removeProperty('display');
      overlay?.style.removeProperty('display');
      clearResults();
      setExpanded(false);
      input?.blur();
      document.removeEventListener('keydown', onKeyDown);
      document.removeEventListener('click', onOutsideClick);
    };

    root.addEventListener('click', (event) => {
      event.stopPropagation();

      const openTarget = event.target.closest('.js-header-search-open');
      if (openTarget) {
        event.preventDefault();
        show();
        return;
      }

      const closeTarget = event.target.closest('.js-header-search-close');
      if (closeTarget) {
        event.preventDefault();
        hide();
      }
    });

    form?.addEventListener('submit', (event) => {
      if (!input) {
        return;
      }

      const query = input.value.trim();

      if (query === '') {
        event.preventDefault();
        clearResults();
        return;
      }

      event.preventDefault();
      hide();

      const action = form.getAttribute('action') || window.location.pathname;
      const targetUrl = new URL(action, window.location.origin);
      const params = new URLSearchParams();

      new FormData(form).forEach((value, key) => {
        if (typeof value === 'string') {
          params.set(key, value);
        }
      });

      const inputName = input.getAttribute('name') || 'q';
      params.set(inputName, query);

      targetUrl.search = params.toString();

      window.location.assign(targetUrl.toString());
    });

    const renderResults = (items) => {
      if (!results) {
        return;
      }

      results.innerHTML = '';

      if (!items.length) {
        const empty = document.createElement('p');
        empty.className = 'search_suggestions__empty';
        empty.textContent = 'Aucun produit trouvé';
        results.appendChild(empty);
        results.hidden = false;
        return;
      }

      const list = document.createElement('ul');
      list.className = 'search_suggestions__list';

      items.forEach((item) => {
        const entry = document.createElement('li');
        entry.className = 'search_suggestions__item';

        const link = document.createElement('a');
        link.className = 'search_suggestions__link';
        link.href = item.url || '#';

        const thumb = document.createElement('span');
        thumb.className = 'search_suggestions__thumb';

        const img = document.createElement('img');
        img.loading = 'lazy';
        img.alt = item.name || '';
        img.src = item.image ? `/assets/uploads/products/${item.image}` : '/assets/images/logo_dark.png';
        thumb.appendChild(img);

        const content = document.createElement('span');
        content.className = 'search_suggestions__content';

        const title = document.createElement('span');
        title.className = 'search_suggestions__title';
        title.textContent = item.name || '';

        const price = document.createElement('span');
        price.className = 'search_suggestions__price';
        if (typeof item.price !== 'undefined' && item.price !== null) {
          price.textContent = formatPrice(item.price);
        }

        content.appendChild(title);
        content.appendChild(price);

        link.appendChild(thumb);
        link.appendChild(content);
        entry.appendChild(link);
        list.appendChild(entry);
      });

      results.appendChild(list);
      results.hidden = false;
    };

    const fetchResults = async () => {
      if (!endpoint || !input) {
        return;
      }

      const query = input.value.trim();
      if (query === '') {
        clearResults();
        return;
      }

      abortController?.abort();
      abortController = new AbortController();

      try {
        const response = await fetch(`${endpoint}?q=${encodeURIComponent(query)}`, {
          headers: { 'X-Requested-With': 'XMLHttpRequest' },
          signal: abortController.signal,
        });

        if (!response.ok) {
          throw new Error(`HTTP ${response.status}`);
        }

        const payload = await response.json();
        if (!Array.isArray(payload)) {
          clearResults();
          return;
        }

        renderResults(payload);
      } catch (error) {
        if (error.name !== 'AbortError') {
          console.error('Recherche header', error);
        }
      }
    };

    const debouncedFetch = debounce(fetchResults, 200);

    input?.addEventListener('input', debouncedFetch);
    input?.addEventListener('focus', () => {
      if (input.value.trim() === '') {
        clearResults();
      }
    });
  };

  document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.js-header-search').forEach(initHeaderSearch);
  });
})();





