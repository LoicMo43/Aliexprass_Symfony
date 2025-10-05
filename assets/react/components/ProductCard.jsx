import React, { useMemo } from "react";
import { formatPrice } from "../utils/currency";

const computeBadges = (product) => {
  const badges = [];
  if (product.isNewArrival) badges.push({ label: "Nouveau", tone: "accent" });
  if (product.isBestSeller) badges.push({ label: "Best seller", tone: "success" });
  if (product.isFeatured) badges.push({ label: "En vedette", tone: "info" });
  if (product.isSpecialOffer) badges.push({ label: "Offre", tone: "warning" });
  return badges;
};

export default function ProductCard({ product, currency }) {
  const image = product.image || "";
  const href = `/product/${product.slug}`;

  const badges = useMemo(() => computeBadges(product), [product]);
  const formattedPrice = useMemo(
    () => formatPrice(product.price ?? 0, currency),
    [product.price, currency]
  );

  const categories = useMemo(() => product.categories || [], [product.categories]);

  return (
    <article className="react-home__card" role="listitem">
      <div className="react-home__media">
        <img src={image} alt={product.name} loading="lazy" />
        {!!badges.length && (
          <div className="react-home__badges">
            {badges.map((badge) => (
              <span key={badge.label} className={`react-home__badge react-home__badge--${badge.tone}`}>
                {badge.label}
              </span>
            ))}
          </div>
        )}
      </div>
      <div className="react-home__content">
        <h3 className="react-home__product">{product.name}</h3>
        {categories.length ? (
          <p className="react-home__category">{categories.join(" / ")}</p>
        ) : null}
        <p className="react-home__price">{formattedPrice}</p>
      </div>
      <div className="react-home__actions">
        <a className="react-home__cta" href={href}>
          Decouvrir
        </a>
      </div>
    </article>
  );
}