export default defineNuxtPlugin(() => {
  const fetchApi = $fetch.create({
    baseURL: "http://127.0.0.1:8000/api",
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
  });

  const api = {
    get(url: string, options: Record<string, any> = {}) {
      return fetchApi(url, {
        ...options,
        method: "GET",
      });
    },

    post(url: string, options: Record<string, any> = {}) {
      return fetchApi(url, {
        ...options,
        method: "POST",
      });
    },

    put(url: string, options: Record<string, any> = {}) {
      return fetchApi(url, {
        ...options,
        method: "PUT",
      });
    },

    patch(url: string, options: Record<string, any> = {}) {
      return fetchApi(url, {
        ...options,
        method: "PATCH",
      });
    },

    delete(url: string, options: Record<string, any> = {}) {
      return fetchApi(url, {
        ...options,
        method: "DELETE",
      });
    },
  };

  return {
    provide: {
      api,
    },
  };
});
