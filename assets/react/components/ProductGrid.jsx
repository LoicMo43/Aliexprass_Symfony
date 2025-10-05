import React from "react";
import ProductCard from "./ProductCard";

export default function ProductGrid({ products, currency }) {
  if (!products.length) {
    return (
      <div className="react-home__state">
        <p>Aucun produit ne correspond à vos critères pour le moment.</p>
      </div>
    );
  }

  return (
    <div className="react-home__grid" role="list">
      {products.map((product) => (
        <ProductCard key={product.id} product={product} currency={currency} />
      ))}
    </div>
  );
}