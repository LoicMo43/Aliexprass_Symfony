import React, { useEffect, useMemo, useState } from "react";
import useProducts, { STATUS } from "./hooks/useProducts";
import FiltersBar from "./components/FiltersBar";
import ProductGrid from "./components/ProductGrid";

const filterConfig = [
  { id: "all", label: "Tout" },
  { id: "new", label: "Nouveautes", predicate: (product) => product.isNewArrival },
  { id: "best", label: "Best Sellers", predicate: (product) => product.isBestSeller },
  { id: "featured", label: "En vedette", predicate: (product) => product.isFeatured },
  { id: "offer", label: "Offres speciales", predicate: (product) => product.isSpecialOffer }
];

const toCents = (value) => {
  const parsed = Number.parseFloat(value);
  if (Number.isNaN(parsed)) {
    return null;
  }
  return Math.max(0, Math.round(parsed * 100));
};

const normaliseTag = (tag) => tag.trim().toLowerCase();

export default function ShopApp({ endpoint, currency }) {
  const { status, products, errorMessage } = useProducts(endpoint);
  const [search, setSearch] = useState("");
  const [filter, setFilter] = useState(filterConfig[0].id);
  const [category, setCategory] = useState("all");
  const [tag, setTag] = useState("all");
  const [minPrice, setMinPrice] = useState("");
  const [maxPrice, setMaxPrice] = useState("");

  const priceBounds = useMemo(() => {
    if (!products.length) {
      return { min: 0, max: 0 };
    }
    const values = products.map((product) => product.price ?? 0);
    return {
      min: Math.min(...values),
      max: Math.max(...values)
    };
  }, [products]);

  useEffect(() => {
    if (status === STATUS.ready && products.length && minPrice === "" && maxPrice === "") {
      setMinPrice((priceBounds.min / 100).toFixed(2));
      setMaxPrice((priceBounds.max / 100).toFixed(2));
    }
  }, [status, products, priceBounds, minPrice, maxPrice]);

  const categories = useMemo(() => {
    const accumulator = new Set();
    products.forEach((product) => {
      (product.categories || []).forEach((name) => accumulator.add(name));
    });
    return ["all", ...Array.from(accumulator).sort((a, b) => a.localeCompare(b))];
  }, [products]);

  const tags = useMemo(() => {
    const accumulator = new Set();
    products.forEach((product) => {
      if (!product.tags) {
        return;
      }
      product.tags
        .split(",")
        .map((value) => value.trim())
        .filter(Boolean)
        .forEach((value) => accumulator.add(value));
    });
    return ["all", ...Array.from(accumulator).sort((a, b) => a.localeCompare(b))];
  }, [products]);

  const filteredProducts = useMemo(() => {
    const activeFilter = filterConfig.find((entry) => entry.id === filter);
    const term = search.trim().toLowerCase();
    const minPriceCents = toCents(minPrice);
    const maxPriceCents = toCents(maxPrice);
    const activeTag = tag === "all" ? null : normaliseTag(tag);

    return products.filter((product) => {
      const matchesFilter = activeFilter?.predicate ? activeFilter.predicate(product) : true;
      const matchesCategory = category === "all" || (product.categories || []).includes(category);
      const matchesTerm =
        term.length === 0 ||
        product.name.toLowerCase().includes(term) ||
        (product.tags || "").toLowerCase().includes(term);
      const matchesMin = minPriceCents === null || (product.price ?? 0) >= minPriceCents;
      const matchesMax = maxPriceCents === null || (product.price ?? 0) <= maxPriceCents;
      const normalisedTags = (product.tags || "")
        .split(",")
        .map(normaliseTag)
        .filter(Boolean);
      const matchesTag = !activeTag || normalisedTags.includes(activeTag);

      return matchesFilter && matchesCategory && matchesTerm && matchesMin && matchesMax && matchesTag;
    });
  }, [products, filter, category, search, minPrice, maxPrice, tag]);

  const highlightCounter = useMemo(() => {
    return filterConfig.reduce((acc, entry) => {
      acc[entry.id] = entry.predicate ? products.filter(entry.predicate).length : products.length;
      return acc;
    }, {});
  }, [products]);

  const priceHint = useMemo(() => {
    if (!products.length) {
      return "";
    }
    return `Fourchette disponible: ${(priceBounds.min / 100).toFixed(2)} - ${(priceBounds.max / 100).toFixed(2)}`;
  }, [products, priceBounds]);

  return (
    <div className="react-home">
      <div className="react-home__surface">
        <header className="react-home__header">
          <h2 className="react-home__title">Parcourir la boutique</h2>
          <p className="react-home__subtitle">
            Combinez filtres, tags et fourchette de prix pour trouver les produits parfaits.
          </p>
        </header>
        <FiltersBar
          filter={filter}
          onFilterChange={setFilter}
          filters={filterConfig}
          filterCounters={highlightCounter}
          search={search}
          onSearchChange={setSearch}
          category={category}
          onCategoryChange={setCategory}
          categories={categories}
        >
          <>
            <div className="react-home__range" role="group" aria-label="Filtre par prix">
              <div className="react-home__field">
                <label htmlFor="react-price-min">Prix min</label>
                <input
                  id="react-price-min"
                  type="number"
                  min="0"
                  step="0.5"
                  value={minPrice}
                  onChange={(event) => setMinPrice(event.target.value)}
                />
              </div>
              <div className="react-home__field">
                <label htmlFor="react-price-max">Prix max</label>
                <input
                  id="react-price-max"
                  type="number"
                  min="0"
                  step="0.5"
                  value={maxPrice}
                  onChange={(event) => setMaxPrice(event.target.value)}
                />
              </div>
              {priceHint ? <p className="react-home__helper">{priceHint}</p> : null}
            </div>
            <div className="react-home__select">
              <label htmlFor="react-tag" className="sr-only">
                Filtrer par tag
              </label>
              <select id="react-tag" value={tag} onChange={(event) => setTag(event.target.value)}>
                {tags.map((value) => (
                  <option key={value} value={value}>
                    {value === "all" ? "Tous les tags" : value}
                  </option>
                ))}
              </select>
            </div>
          </>
        </FiltersBar>
        {status === STATUS.loading && (
          <div className="react-home__state">
            <div className="react-home__loader" aria-hidden="true" />
            <p>Chargement des produits...</p>
          </div>
        )}
        {status === STATUS.error && (
          <div className="react-home__state react-home__state--error" role="alert">
            <p>Une erreur est survenue: {errorMessage}</p>
            <button type="button" className="react-home__retry" onClick={() => window.location.reload()}>
              Reessayer
            </button>
          </div>
        )}
        {status === STATUS.ready && <ProductGrid products={filteredProducts} currency={currency} />}
      </div>
    </div>
  );
}