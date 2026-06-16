export default defineNuxtConfig({
  modules: ['@nuxt/ui'],

  // Явно вказуємо Nuxt підвантажувати наш файл зі стилями Tailwind v4
  css: ['~/assets/css/main.css'],

  vite: {
    optimizeDeps: {
      include: [
        '@vue/devtools-core',
        '@vue/devtools-kit'
      ]
    }
  }
})
