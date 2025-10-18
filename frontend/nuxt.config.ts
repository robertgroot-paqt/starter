// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
    compatibilityDate: "2025-07-15",
    devtools: { enabled: true },

    vite: {
        server: {
            allowedHosts: true,
        },
    },

    modules: [
        "nuxt-auth-sanctum",
        '@nuxt/ui',
    ],

    // https://manchenkoff.gitbook.io/nuxt-auth-sanctum
    sanctum: {
        baseUrl: "https://api.starter.paqt.dev/api/v1",
        endpoints: {
            user: 'session',
            csrf: 'session/csrf-cookie',
            login: 'session',
            logout: 'session/logout',
        },
        globalMiddleware: {
            enabled: true,
        },
    },
});
