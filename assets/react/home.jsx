import React from "react";
import { createRoot } from "react-dom/client";
import HomeApp from "./HomeApp";
import "./styles/home.css";

const mount = document.getElementById("react-home-root");

if (mount) {
  const { endpoint, currency } = mount.dataset;
  const root = createRoot(mount);
  root.render(
    <React.StrictMode>
      <HomeApp endpoint={endpoint} currency={currency || "EUR"} />
    </React.StrictMode>
  );
}