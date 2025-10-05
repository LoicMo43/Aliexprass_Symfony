import React from "react";

function FilterButton({ id, label, isActive, onClick, count }) {
  return (
    <button
      type="button"
      className={`react-home__pill${isActive ? " react-home__pill--active" : ""}`}
      onClick={() => onClick(id)}
      aria-pressed={isActive}
    >
      <span>{label}</span>
      {typeof count === "number" && (
        <span className="react-home__pill-count">{count}</span>
      )}
    </button>
  );
}

export default function FiltersBar({
  filters,
  filter,
  onFilterChange,
  filterCounters,
  search,
  onSearchChange,
  category,
  onCategoryChange,
  categories,
  children
}) {
  return (
    <section className="react-home__filters" aria-label="Filtres produits">
      <div className="react-home__filters-row">
        <div className="react-home__pills" role="toolbar" aria-label="Categories mises en avant">
          {filters.map((item) => (
            <FilterButton
              key={item.id}
              id={item.id}
              label={item.label}
              isActive={filter === item.id}
              onClick={onFilterChange}
              count={filterCounters[item.id]}
            />
          ))}
        </div>
        <div className="react-home__search">
          <input
            type="search"
            value={search}
            onChange={(event) => onSearchChange(event.target.value)}
            placeholder="Rechercher un produit"
            aria-label="Rechercher un produit"
          />
        </div>
        <div className="react-home__select">
          <label htmlFor="react-category" className="sr-only">
            Filtrer par categorie
          </label>
          <select
            id="react-category"
            value={category}
            onChange={(event) => onCategoryChange(event.target.value)}
          >
            {categories.map((name) => (
              <option key={name} value={name}>
                {name === "all" ? "Toutes les categories" : name}
              </option>
            ))}
          </select>
        </div>
      </div>
      {children ? <div className="react-home__filters-extra">{children}</div> : null}
    </section>
  );
}