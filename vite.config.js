import { defineConfig } from "vite";
import react from "@vitejs/plugin-react";
import { resolve } from "path";

export default defineConfig({
  plugins: [react()],
  base: "/build/",
  build: {
    outDir: "public/build",
    emptyOutDir: true,
    rollupOptions: {
      input: {
        home: resolve(__dirname, "assets/react/home.jsx"),
        shop: resolve(__dirname, "assets/react/shop.jsx"),
        product: resolve(__dirname, "assets/react/product.jsx"),
        header: resolve(__dirname, "assets/react/header.jsx"),
        login: resolve(__dirname, "assets/react/login.jsx"),
        register: resolve(__dirname, "assets/react/register.jsx")
      },
      output: {
        entryFileNames: "assets/[name].js",
        chunkFileNames: "assets/[name].js",
        assetFileNames: "assets/[name][extname]"
      }
    }
  }
});
