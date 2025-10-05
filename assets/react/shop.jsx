import React from "react";
import { createRoot } from "react-dom/client";
import ShopApp from "./ShopApp";
import "./styles/home.css";

const mount = document.getElementById("react-shop-root");

if (mount) {
  const { endpoint, currency } = mount.dataset;
  const root = createRoot(mount);
  root.render(
    <React.StrictMode>
      <ShopApp endpoint={endpoint} currency={currency || "EUR"} />
    </React.StrictMode>
  );
}