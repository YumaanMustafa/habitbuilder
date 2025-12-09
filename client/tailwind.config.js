/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./src/**/*.{js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {
      colors: {
        primary: "#6366f1", // Indigo 500
        secondary: "#10b981", // Emerald 500
        background: "#0f172a", // Slate 900
        surface: "#1e293b", // Slate 800
        text: "#f8fafc", // Slate 50
        muted: "#94a3b8", // Slate 400
      }
    },
  },
  plugins: [],
}
