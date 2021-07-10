import { defineConfig } from "vite";
import reactRefresh from "@vitejs/plugin-react-refresh";

export default defineConfig({
  root: "frontend",
  publicDir: false,
  build: {
    target: "es2015",
  },
  plugins: [reactRefresh()],
});
