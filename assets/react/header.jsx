import React, {
  useCallback,
  useEffect,
  useMemo,
  useRef,
  useState
} from "react";
import { createRoot } from "react-dom/client";

const formatPriceFromCents = (value, currencyCode = "EUR") => {
  const cents = Number(value);

  if (Number.isNaN(cents)) {
    return "";
  }

  const amount = cents / 100;

  try {
    return new Intl.NumberFormat("fr-FR", {
      style: "currency",
      currency: currencyCode,
      minimumFractionDigits: 2
    }).format(amount);
  } catch (error) {
    return `${amount.toFixed(2)} €`;
  }
};

const formatCurrency = (value, currencyCode = "EUR", fallbackSymbol = "€") => {
  const numeric = Number(value);

  if (Number.isNaN(numeric)) {
    return `${fallbackSymbol ?? ""}${value ?? ""}`.trim();
  }

  try {
    return new Intl.NumberFormat("fr-FR", {
      style: "currency",
      currency: currencyCode,
      minimumFractionDigits: 2
    }).format(numeric);
  } catch (error) {
    return `${fallbackSymbol ?? ""}${numeric.toFixed(2)}`.trim();
  }
};

const useDebouncedValue = (value, delay = 200) => {
  const [debounced, setDebounced] = useState(value);

  useEffect(() => {
    const timer = window.setTimeout(() => setDebounced(value), delay);
    return () => window.clearTimeout(timer);
  }, [value, delay]);

  return debounced;
};

const HeaderSearch = ({ endpoint, placeholder, action, inputName }) => {
  const [query, setQuery] = useState("");
  const [results, setResults] = useState([]);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState("");
  const [isPanelOpen, setIsPanelOpen] = useState(false);
  const inputRef = useRef(null);
  const rootRef = useRef(null);
  const abortRef = useRef(null);

  const debouncedQuery = useDebouncedValue(query, 250);

  const openPanel = useCallback(() => {
    setIsPanelOpen(true);
  }, []);

  const closePanel = useCallback(() => {
    setIsPanelOpen(false);
  }, []);

  useEffect(() => {
    if (!isPanelOpen) {
      return undefined;
    }

    const handleClickOutside = (event) => {
      if (rootRef.current && !rootRef.current.contains(event.target)) {
        closePanel();
      }
    };

    document.addEventListener("mousedown", handleClickOutside);
    return () => document.removeEventListener("mousedown", handleClickOutside);
  }, [closePanel, isPanelOpen]);

  useEffect(() => {
    if (!endpoint) {
      return undefined;
    }

    const trimmed = debouncedQuery.trim();

    if (trimmed === "") {
      setResults([]);
      setError("");
      setLoading(false);
      if (abortRef.current) {
        abortRef.current.abort();
      }
      return undefined;
    }

    setLoading(true);
    setError("");

    const controller = new AbortController();
    abortRef.current = controller;

    const fetchResults = async () => {
      try {
        const response = await fetch(
          `${endpoint}?q=${encodeURIComponent(trimmed)}`,
          {
            headers: { "X-Requested-With": "XMLHttpRequest" },
            signal: controller.signal
          }
        );

        if (!response.ok) {
          throw new Error(`HTTP ${response.status}`);
        }

        const payload = await response.json();
        if (!Array.isArray(payload)) {
          setResults([]);
        } else {
          setResults(payload);
        }
      } catch (fetchError) {
        if (fetchError.name !== "AbortError") {
          console.error("Header search", fetchError);
          setError("Impossible de rÃ©cupÃ©rer les rÃ©sultats pour le moment.");
        }
      } finally {
        setLoading(false);
      }
    };

    fetchResults();

    return () => controller.abort();
  }, [debouncedQuery, endpoint]);

  const handleSubmit = useCallback(
    (event) => {
      if (!action) {
        event.preventDefault();
        return;
      }

      if (query.trim() === "") {
        event.preventDefault();
        inputRef.current?.focus();
        return;
      }

      closePanel();
    },
    [action, closePanel, query]
  );

  const handleResultClick = useCallback(() => {
    closePanel();
  }, [closePanel]);

  const showPanel =
    isPanelOpen && (loading || error || results.length || query.trim() !== "");

  let panelContent = null;

  if (loading) {
    panelContent = (
      <p className="header-search__empty">Recherche en cours...</p>
    );
  } else if (error) {
    panelContent = <p className="header-search__empty">{error}</p>;
  } else if (query.trim() === "") {
    panelContent = (
      <p className="header-search__empty">
        Commencez Ã  taper pour dÃ©couvrir nos meilleures suggestions.
      </p>
    );
  } else if (!results.length) {
    panelContent = (
      <p className="header-search__empty">Aucun produit trouvÃ© pour cette recherche.</p>
    );
  } else {
    panelContent = (
      <ul className="search_suggestions__list">
        {results.map((item, index) => {
          const key = item.id ?? item.slug ?? item.url ?? index;
          const imageSrc = item.image
            ? `/assets/uploads/products/${item.image}`
            : "/assets/images/logo_dark.png";

          return (
            <li className="search_suggestions__item" key={key}>
              <a
                className="search_suggestions__link"
                href={item.url || "#"}
                onClick={handleResultClick}
              >
                <span className="search_suggestions__thumb">
                  <img src={imageSrc} alt={item.name || ""} loading="lazy" />
                </span>
                <span className="search_suggestions__content">
                  <span className="search_suggestions__title">
                    {item.name || ""}
                  </span>
                  {typeof item.price !== "undefined" && item.price !== null ? (
                    <span className="search_suggestions__price">
                      {formatPriceFromCents(item.price)}
                    </span>
                  ) : null}
                </span>
              </a>
            </li>
          );
        })}
      </ul>
    );
  }

  return (
    <div className="header-search" ref={rootRef}>
      <form
        className="header-search__form"
        action={action}
        method="get"
        onSubmit={handleSubmit}
      >
        <span className="header-search__icon">
          <i className="linearicons-magnifier" aria-hidden="true"></i>
        </span>
        <label htmlFor={inputName} className="sr-only">
          {placeholder}
        </label>
        <input
          ref={inputRef}
          id={inputName}
          className="header-search__input"
          type="search"
          name={inputName}
          value={query}
          placeholder={placeholder}
          autoComplete="off"
          spellCheck="false"
          enterKeyHint="search"
          inputMode="search"
          onFocus={openPanel}
          onChange={(event) => setQuery(event.target.value)}
        />
        <button type="submit" className="header-search__submit" aria-label="Rechercher">
          <i className="ion-ios-search-strong" aria-hidden="true"></i>
        </button>
      </form>
      <div
        className={`header-search__panel${showPanel ? " is-visible" : ""}`}
        role="listbox"
      >
        {panelContent}
      </div>
    </div>
  );
};

const QuickAction = ({ href, icon, label }) => (
  <a className="neo-header__action" href={href || "#"} title={label}>
    <span className="neo-header__action-icon">
      <i className={icon} aria-hidden="true"></i>
    </span>
    <span className="neo-header__action-label">{label}</span>
  </a>
);

const Header = (props) => {
  const {
    brandUrl = "/",
    logos = {},
    languages = [],
    currencies = [],
    defaultLanguage,
    defaultCurrency,
    contactNumber,
    navLinks = [],
    user = {},
    search = {},
    cart = {}
  } = props;

  const [language, setLanguage] = useState(
    defaultLanguage || languages[0]?.value || ""
  );
  const [currency, setCurrency] = useState(
    defaultCurrency || currencies[0]?.value || ""
  );
  const [isNavOpen, setIsNavOpen] = useState(false);
  const [isCartOpen, setIsCartOpen] = useState(false);

  const cartPanelRef = useRef(null);

  const cartItems = cart.items || [];
  const cartCount = Number(cart.count || 0);
  const cartCurrency = cart.currencyCode || "EUR";
  const subtotalDisplay = formatCurrency(
    cart.subtotal || 0,
    cartCurrency,
    cart.currencySymbol || "€"
  );

  const quickActions = useMemo(() => {
    if (user.isAuthenticated) {
      const preferredLabels = ["Comparer", "Liste de souhaits"];
      const preferred =
        (user.authenticatedLinks || []).filter((link) =>
          preferredLabels.includes((link.label || "").trim())
        );

      if (preferred.length) {
        return preferred.slice(0, 2);
      }

      return (user.authenticatedLinks || []).slice(0, 2);
    }

    return (user.guestLinks || []).slice(0, 2);
  }, [user]);

  useEffect(() => {
    if (!isCartOpen) {
      return undefined;
    }

    const handleClickOutside = (event) => {
      if (cartPanelRef.current && !cartPanelRef.current.contains(event.target)) {
        setIsCartOpen(false);
      }
    };

    document.addEventListener("mousedown", handleClickOutside);
    return () => document.removeEventListener("mousedown", handleClickOutside);
  }, [isCartOpen]);

  useEffect(() => {
    const handleResize = () => {
      if (window.innerWidth > 991) {
        setIsNavOpen(false);
      }
    };

    window.addEventListener("resize", handleResize);
    return () => window.removeEventListener("resize", handleResize);
  }, []);

  const toggleCart = useCallback(() => {
    setIsCartOpen((prev) => !prev);
  }, []);

  const toggleNav = useCallback(() => {
    setIsNavOpen((prev) => !prev);
  }, []);

  const normalizedPhone =
    contactNumber?.replace(/[^\d+]/g, "").trim() || contactNumber || "";

  return (
    <>
      <div className="preloader">
        <div className="preloader-content">
          <span></span>
          <span></span>
          <span></span>
        </div>
      </div>
      <header className="neo-header">
        <div className="neo-header__top">
          <div className="neo-header__container">
            <div className="neo-header__top-left">
              <div className="neo-header__select">
                <label className="sr-only" htmlFor="neo-header-language">
                  Choisir la langue
                </label>
                <select
                  id="neo-header-language"
                  value={language}
                  onChange={(event) => setLanguage(event.target.value)}
                >
                  {languages.map((languageOption) => (
                    <option
                      key={languageOption.value}
                      value={languageOption.value}
                    >
                      {languageOption.label}
                    </option>
                  ))}
                </select>
              </div>
              <div className="neo-header__select">
                <label className="sr-only" htmlFor="neo-header-currency">
                  Choisir la devise
                </label>
                <select
                  id="neo-header-currency"
                  value={currency}
                  onChange={(event) => setCurrency(event.target.value)}
                >
                  {currencies.map((currencyOption) => (
                    <option
                      key={currencyOption.value}
                      value={currencyOption.value}
                    >
                      {currencyOption.label}
                    </option>
                  ))}
                </select>
              </div>
              <a className="neo-header__contact" href={normalizedPhone ? `tel:${normalizedPhone}` : "#"}>
                <i className="ti-mobile" aria-hidden="true"></i>
                <span>{contactNumber}</span>
              </a>
            </div>
          </div>
        </div>

        <div className="neo-header__main">
          <div className="neo-header__container">
            <a className="neo-header__brand" href={brandUrl}>
              {logos.light ? (
                <img
                  src={logos.light}
                  alt="AliExprass"
                  className="neo-header__logo neo-header__logo--light"
                />
              ) : null}
              {logos.dark ? (
                <img
                  src={logos.dark}
                  alt="AliExprass"
                  className="neo-header__logo neo-header__logo--dark"
                />
              ) : null}
            </a>

            <div className="neo-header__search">
              <HeaderSearch
                endpoint={search.endpoint}
                placeholder={search.placeholder}
                action={search.action}
                inputName={search.inputName || "q"}
              />
            </div>

            <div className="neo-header__actions">
              {quickActions.map((actionItem) => (
                <QuickAction
                  key={actionItem.label}
                  href={actionItem.href}
                  icon={actionItem.icon}
                  label={actionItem.label}
                />
              ))}

              <div
                className={`neo-header__cart${isCartOpen ? " is-open" : ""}`}
                ref={cartPanelRef}
              >
                <button
                  type="button"
                  className="neo-header__cart-toggle"
                  aria-haspopup="dialog"
                  aria-expanded={isCartOpen ? "true" : "false"}
                  onClick={toggleCart}
                >
                  <span className="neo-header__cart-icon">
                    <i className="linearicons-cart" aria-hidden="true"></i>
                    <span className="neo-header__cart-count">{cartCount}</span>
                  </span>
                  <span className="neo-header__cart-label">Panier</span>
                </button>
                <div
                  className={`neo-header__cart-panel${
                    isCartOpen ? " is-open" : ""
                  }`}
                  role="dialog"
                  aria-modal="true"
                >
                  {cartItems.length ? (
                    <>
                      <ul className="neo-header__cart-list">
                        {cartItems.map((item) => (
                          <li className="neo-header__cart-item" key={item.id ?? item.deleteUrl}>
                            <a
                              href={item.deleteUrl || "#"}
                              className="neo-header__cart-remove"
                              aria-label={`Retirer ${item.name} du panier`}
                            >
                              <i className="ion-close" aria-hidden="true"></i>
                            </a>
                            <a
                              href={item.url || "#"}
                              className="neo-header__cart-link"
                            >
                              {item.image ? (
                                <img
                                  src={`/assets/uploads/products/${item.image}`}
                                  alt={item.name}
                                />
                              ) : null}
                              <span className="neo-header__cart-info">
                                <span className="neo-header__cart-name">
                                  {item.name}
                                </span>
                                <span className="neo-header__cart-meta">
                                  {item.quantity} Ã—{" "}
                                  {formatPriceFromCents(item.price, cartCurrency)}
                                </span>
                              </span>
                            </a>
                          </li>
                        ))}
                      </ul>
                      <div className="neo-header__cart-footer">
                        <p className="neo-header__cart-total">
                          <span className="neo-header__cart-total-label">
                            Sous-total TTC
                          </span>
                          <span className="neo-header__cart-total-value">
                            {subtotalDisplay}
                          </span>
                        </p>
                        <div className="neo-header__cart-actions">
                          <a
                            href={cart.cartUrl || "#"}
                            className="neo-header__cart-button neo-header__cart-button--ghost"
                          >
                            Voir le panier
                          </a>
                          <a
                            href={cart.checkoutUrl || "#"}
                            className="neo-header__cart-button neo-header__cart-button--solid"
                          >
                            Commander
                          </a>
                        </div>
                      </div>
                    </>
                  ) : (
                    <p className="neo-header__cart-empty">
                      Votre panier est vide pour le moment.
                    </p>
                  )}
                </div>
              </div>

              <button
                type="button"
                className={`neo-header__burger${isNavOpen ? " is-active" : ""}`}
                aria-controls="neo-header-menu"
                aria-expanded={isNavOpen ? "true" : "false"}
                onClick={toggleNav}
              >
                <span className="neo-header__burger-line"></span>
                <span className="neo-header__burger-line"></span>
                <span className="neo-header__burger-line"></span>
                <span className="sr-only">
                  {isNavOpen ? "Fermer le menu" : "Ouvrir le menu"}
                </span>
              </button>
            </div>
          </div>
        </div>

        <div
          className={`neo-header__nav${isNavOpen ? " is-open" : ""}`}
          id="neo-header-menu"
        >
          <div className="neo-header__container">
            <nav className="neo-header__menu" aria-label="Navigation principale">
              <ul>
                {navLinks.map((link) => {
                  const itemClasses = ["neo-header__menu-item"];
                  if (link.itemClass) {
                    itemClasses.push(link.itemClass);
                  }

                  const linkClasses = ["neo-header__menu-link"];
                  if (link.linkClass) {
                    linkClasses.push(link.linkClass);
                  }

                  return (
                    <li className={itemClasses.join(" ")} key={link.label}>
                      <a className={linkClasses.join(" ")} href={link.href || "#"}>
                        {link.label}
                      </a>
                    </li>
                  );
                })}
              </ul>
            </nav>
          </div>
        </div>
      </header>
    </>
  );
};

const bootstrap = () => {
  const container = document.getElementById("react-header-root");

  if (!container) {
    return;
  }

  const rawProps = container.dataset.props || "{}";
  let parsedProps = {};

  try {
    parsedProps = JSON.parse(rawProps);
  } catch (error) {
    console.error("Impossible de parser les props du header React :", error);
  }

  const root = createRoot(container);
  root.render(<Header {...parsedProps} />);
};

if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", bootstrap);
} else {
  bootstrap();
}
