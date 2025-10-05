export function resolveCurrency(value) {
  if (!value) {
    return "EUR";
  }
  const normalised = value.toString().trim().toUpperCase();
  if (normalised === "€" || normalised === "EUR") {
    return "EUR";
  }
  if (normalised === "$" || normalised === "USD") {
    return "USD";
  }
  return normalised.length === 3 ? normalised : "EUR";
}

export function formatPrice(amount, currency = "EUR", locale = "fr-FR") {
  const value = typeof amount === "number" ? amount / 100 : 0;
  const currencyCode = resolveCurrency(currency);
  return new Intl.NumberFormat(locale, {
    style: "currency",
    currency: currencyCode,
    currencyDisplay: "symbol",
    minimumFractionDigits: 2
  }).format(value);
}