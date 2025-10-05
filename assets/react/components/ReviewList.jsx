import React from "react";

const toLocaleDate = (value) => {
  if (!value) {
    return "";
  }
  const date = new Date(value);
  if (Number.isNaN(date.getTime())) {
    return "";
  }
  return date.toLocaleDateString("fr-FR", { year: "numeric", month: "long", day: "numeric" });
};

const toStars = (note) => {
  const normalised = typeof note === "number" ? Math.max(0, Math.min(100, note)) : 0;
  const ratio = normalised / 20;
  return {
    ratio,
    percent: normalised
  };
};

export default function ReviewList({ reviews }) {
  if (!reviews.length) {
    return (
      <div className="product-reviews__empty">
        <p>Aucun avis pour le moment. Soyez le premier a partager votre experience.</p>
      </div>
    );
  }

  return (
    <div className="product-reviews__list">
      {reviews.map((review) => {
        const { ratio, percent } = toStars(review.note);
        return (
          <article key={review.id} className="product-review">
            <header className="product-review__header">
              <div>
                <strong className="product-review__author">{review.author}</strong>
                <span className="product-review__date">{toLocaleDate(review.createdAt)}</span>
              </div>
              <div className="product-review__rating" aria-label={`Note ${ratio.toFixed(1)} sur 5`}>
                <div className="product-review__stars">
                  <span className="product-review__stars-fill" style={{ width: `${percent}%` }} />
                </div>
                <span className="product-review__ratio">{ratio.toFixed(1)}/5</span>
              </div>
            </header>
            <p className="product-review__comment">{review.comment}</p>
            {review.canDelete && review.deleteUrl ? (
              <a className="product-review__delete" href={review.deleteUrl}>
                Supprimer l'avis
              </a>
            ) : null}
          </article>
        );
      })}
    </div>
  );
}