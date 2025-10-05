import React, { useMemo } from "react";
import ProductGrid from "./components/ProductGrid";
import ReviewList from "./components/ReviewList";
import { formatPrice, resolveCurrency } from "./utils/currency";

const computeBadges = (product) => {
  const badges = [];
  if (product.isNewArrival) badges.push({ label: "Nouveau", tone: "accent" });
  if (product.isBestSeller) badges.push({ label: "Best seller", tone: "success" });
  if (product.isFeatured) badges.push({ label: "En vedette", tone: "info" });
  if (product.isSpecialOffer) badges.push({ label: "Offre", tone: "warning" });
  return badges;
};

const formatHtml = (value) => ({ __html: value || "" });

export default function ProductApp({ product, reviews = [], related = [], user = {} }) {
  if (!product) {
    return (
      <div className="product-page__placeholder" role="status">
        Produit introuvable.
      </div>
    );
  }

  const currency = resolveCurrency(product.currency || "EUR");
  const badges = computeBadges(product);
  const inStock = (product.quantity ?? 0) > 0;
  const stockLabel = inStock ? "En stock" : "Hors stock";
  const stockTone = inStock ? "success" : "danger";
  const categories = product.categories || [];
  const tags = useMemo(() => {
    if (!product.tags) {
      return [];
    }
    return product.tags
      .split(",")
      .map((value) => value.trim())
      .filter(Boolean);
  }, [product.tags]);

  const ratingAverage = useMemo(() => {
    if (!reviews.length) {
      return 0;
    }
    const total = reviews.reduce((acc, review) => acc + (review.note || 0), 0);
    return total / reviews.length;
  }, [reviews]);

  const ratingOnFive = (ratingAverage / 20).toFixed(1);
  const ratingPercent = Math.round(ratingAverage);
  const price = formatPrice(product.price ?? 0, currency);
  const compareAt = formatPrice(product.compareAtPrice ?? Math.round((product.price ?? 0) * 1.2), currency);

  const highlights = useMemo(() => {
    const base = [
      "1 an de garantie fabricant",
      "Retour sous 30 jours",
      "Paiement securise",
      inStock ? "Disponible immediatement" : "Disponible sur commande"
    ];
    return base;
  }, [inStock]);

  return (
    <div className="product-page">
      <section className="product-hero">
        <div className="product-hero__media">
          {!!badges.length && (
            <div className="product-hero__badges">
              {badges.map((badge) => (
                <span key={badge.label} className={`product-hero__badge product-hero__badge--${badge.tone}`}>
                  {badge.label}
                </span>
              ))}
            </div>
          )}
          <img src={product.image} alt={product.name} loading="lazy" />
        </div>
        <div className="product-hero__info">
          <h1 className="product-hero__title">{product.name}</h1>
          <div className="product-hero__pricing">
            <span className="product-hero__price">{price}</span>
            <span className="product-hero__compare">{compareAt}</span>
          </div>
          <div className="product-hero__rating" aria-label={`Note ${ratingOnFive} sur 5`}>
            <div className="product-hero__stars">
              <span className="product-hero__stars-fill" style={{ width: `${ratingPercent}%` }} />
            </div>
            <span className="product-hero__score">{reviews.length ? `${ratingOnFive}/5 (${reviews.length} avis)` : "Aucun avis"}</span>
          </div>
          <div className="product-hero__stock">
            <span className={`product-hero__stock-dot product-hero__stock-dot--${stockTone}`} />
            {stockLabel}
          </div>
          {product.description ? (
            <div className="product-hero__description" dangerouslySetInnerHTML={formatHtml(product.description)} />
          ) : null}
          <ul className="product-hero__highlights">
            {highlights.map((item) => (
              <li key={item}>{item}</li>
            ))}
          </ul>
          <div className="product-hero__actions">
            <a
              className={`product-hero__button${inStock ? "" : " product-hero__button--disabled"}`}
              href={product.links?.buy}
              aria-disabled={!inStock}
            >
              Acheter maintenant
            </a>
            <a
              className={`product-hero__button product-hero__button--ghost${inStock ? "" : " product-hero__button--disabled"}`}
              href={product.links?.add}
              aria-disabled={!inStock}
            >
              Ajouter au panier
            </a>
            <a className="product-hero__button product-hero__button--ghost" href="#review-form">
              Laisser un avis
            </a>
          </div>
          <div className="product-meta">
            {categories.length ? (
              <div className="product-meta__item">
                <span className="product-meta__label">Categories</span>
                <div className="product-meta__chips">
                  {categories.map((category) => (
                    <span key={category} className="product-meta__chip">
                      {category}
                    </span>
                  ))}
                </div>
              </div>
            ) : null}
            {tags.length ? (
              <div className="product-meta__item">
                <span className="product-meta__label">Tags</span>
                <div className="product-meta__chips">
                  {tags.map((tag) => (
                    <span key={tag} className="product-meta__chip product-meta__chip--ghost">
                      #{tag}
                    </span>
                  ))}
                </div>
              </div>
            ) : null}
          </div>
        </div>
      </section>

      {product.moreInformations ? (
        <section className="product-section">
          <h2 className="product-section__title">Details du produit</h2>
          <div className="product-section__body" dangerouslySetInnerHTML={formatHtml(product.moreInformations)} />
        </section>
      ) : null}

      <section className="product-section" id="reviews">
        <header className="product-section__header">
          <h2 className="product-section__title">Avis clients</h2>
          {reviews.length ? (
            <div className="product-section__badge">{ratingOnFive}/5</div>
          ) : null}
        </header>
        <ReviewList reviews={reviews} />
        {!user.logged && (
          <div className="product-section__cta">
            <p>Connectez-vous pour partager votre opinion.</p>
            <a className="product-hero__button product-hero__button--ghost" href={user.loginUrl}>
              Se connecter
            </a>
          </div>
        )}
      </section>

      {related.length ? (
        <section className="product-section">
          <h2 className="product-section__title">Vous aimerez aussi</h2>
          <ProductGrid products={related} currency={currency} />
        </section>
      ) : null}
    </div>
  );
}