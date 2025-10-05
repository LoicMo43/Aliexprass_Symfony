import React from "react";
import { createRoot } from "react-dom/client";
import ProductApp from "./ProductApp";
import "./styles/home.css";
import "./styles/product.css";

const mount = document.getElementById("react-product-root");

if (mount) {
  const rawProps = mount.dataset.props || "{}";
  let props = {};

  try {
    props = JSON.parse(rawProps);
  } catch (error) {
    console.error("Impossible de parser les donnees produit", error);
  }

  const root = createRoot(mount);
  root.render(
    <React.StrictMode>
      <ProductApp {...props} />
    </React.StrictMode>
  );
}