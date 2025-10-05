import React, { useMemo, useState } from "react";
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

export default function HomeApp({ endpoint, currency }) {
  const { status, products, errorMessage } = useProducts(endpoint);
  const [search, setSearch] = useState("");
  const [filter, setFilter] = useState(filterConfig[0].id);
  const [category, setCategory] = useState("all");

  const categories = useMemo(() => {
    const accumulator = new Set();
    products.forEach((product) => {
      (product.categories || []).forEach((name) => accumulator.add(name));
    });
    return ["all", ...Array.from(accumulator).sort((a, b) => a.localeCompare(b))];
  }, [products]);

  const filteredProducts = useMemo(() => {
    const activeFilter = filterConfig.find((entry) => entry.id === filter);
    const term = search.trim().toLowerCase();
    return products.filter((product) => {
      const matchesFilter = activeFilter?.predicate ? activeFilter.predicate(product) : true;
      const matchesCategory = category === "all" || (product.categories || []).includes(category);
      const matchesTerm =
        term.length === 0 ||
        product.name.toLowerCase().includes(term) ||
        (product.tags || "").toLowerCase().includes(term);
      return matchesFilter && matchesCategory && matchesTerm;
    });
  }, [products, filter, category, search]);

  const highlightCounter = useMemo(() => {
    return filterConfig.reduce((acc, entry) => {
      acc[entry.id] = entry.predicate ? products.filter(entry.predicate).length : products.length;
      return acc;
    }, {});
  }, [products]);

  return (
    <div className="react-home">
      <div className="react-home__surface">
        <header className="react-home__header">
          <h2 className="react-home__title">Decouvrez notre selection</h2>
          <p className="react-home__subtitle">
            Filtrez et explorez les meilleures offres en direct grace a notre interface interactive.
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
        />
        {status === STATUS.loading && (
          <div className="react-home__state">
            <div className="react-home__loader" aria-hidden="true" />
            <p>Chargement des produitsâ€¦</p>
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
        {status === STATUS.ready && (
          <ProductGrid products={filteredProducts} currency={currency} />
        )}
      </div>
    </div>
  );
}