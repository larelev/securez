/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./assets/**/*.js",
    "./assets/**/*.ts",
    "./templates/**/*.twig",
  ],
  theme: {
    extend: {
      colors: {
        primary: '#6366f1',   // Indigo-500
        secondary: '#8b5cf6', // Violet-500
      },
    },
  },
  plugins: [],
}
