import { useEffect, useState } from "react";

export const STATUS = {
  idle: "idle",
  loading: "loading",
  ready: "ready",
  error: "error"
};

export default function useProducts(endpoint) {
  const [status, setStatus] = useState(STATUS.idle);
  const [products, setProducts] = useState([]);
  const [errorMessage, setErrorMessage] = useState("");

  useEffect(() => {
    if (!endpoint) {
      setErrorMessage("Endpoint manquant pour charger les produits.");
      setStatus(STATUS.error);
      return;
    }

    let isMounted = true;

    setStatus(STATUS.loading);
    fetch(endpoint, { headers: { Accept: "application/json" } })
      .then((response) => {
        if (!response.ok) {
          throw new Error("Réponse inattendue du serveur");
        }
        return response.json();
      })
      .then((payload) => {
        if (!isMounted) {
          return;
        }
        setProducts(Array.isArray(payload) ? payload : []);
        setStatus(STATUS.ready);
      })
      .catch((error) => {
        if (!isMounted) {
          return;
        }
        setErrorMessage(error.message || "Impossible de charger les produits.");
        setStatus(STATUS.error);
      });

    return () => {
      isMounted = false;
    };
  }, [endpoint]);

  return { status, products, errorMessage };
}