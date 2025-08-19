import js from "@eslint/js";
import globals from "globals";
import pluginVue from "eslint-plugin-vue";
import { defineConfig } from "eslint/config";

export default defineConfig([
  // Ignore third-party and build artifacts
  { ignores: ["vendor/**", "node_modules/**", "public/**", "storage/**", "bootstrap/**", "dist/**", "build/**"] },
  // Base Vue rules
  pluginVue.configs["flat/essential"],
  // Our project rules and globals (applied after Vue config)
  {
    files: ["resources/js/**/*.{js,ts,vue}", "resources/**/*.vue"],
    plugins: { js },
    extends: ["js/recommended"],
    languageOptions: {
      globals: { ...globals.browser, route: "readonly" },
      ecmaVersion: "latest",
      sourceType: "module",
    },
    rules: {
      // In Inertia apps, single-word component names (e.g., Login, Modal) are common
      "vue/multi-word-component-names": "off",
    },
  },
]);
