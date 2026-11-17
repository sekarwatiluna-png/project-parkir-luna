export default defineNuxtConfig({

  compatibilityDate: "2025-01-01",

  devtools: {
    enabled: true
  },

  runtimeConfig: {

    public: {

      apiBase:
        "http://127.0.0.1:8000/api"

    }

  },

  css: [],

});