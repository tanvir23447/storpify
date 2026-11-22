/** @type {import('tailwindcss').Config} */
module.exports = {
  darkMode: ["class"],
  content: [
    "./src/pages/**/*.{js,ts,jsx,tsx,mdx}",
    "./src/components/**/*.{js,ts,jsx,tsx,mdx}",
    "./src/app/**/*.{js,ts,jsx,tsx,mdx}",
  ],
  theme: {
    extend: {
      colors: {
        maison: {
          blush: "#F6D7D9",
          "blush-light": "#FDF5F6",
          ivory: "#FFF9F2",
          gold: "#C9A45C",
          "gold-light": "#E9D29A",
          rose: "#C45E70",
          "rose-dark": "#B84A62",
          "rose-muted": "#B9787D",
          charcoal: "#292426",
          border: "#F0DFDF",
          white: "#FFFFFF",
        },
        elora: {
          blush: "#F6D7D9",
          "blush-light": "#FDF2F3",
          ivory: "#FFF9F2",
          gold: "#C9A45C",
          "gold-light": "#E9D29A",
          "gold-dark": "#A68238",
          rose: "#C68B91",
          berry: "#C45E70",
          "berry-dark": "#B84A62",
          charcoal: "#292426",
          border: "#F0DFDF",
          muted: "#7A7072",
        },
        background: "#FFF9F2",
        foreground: "#292426",
      },
      fontFamily: {
        serif: ["Playfair Display", "Georgia", "serif"],
        sans: ["Plus Jakarta Sans", "Inter", "-apple-system", "BlinkMacSystemFont", "Segoe UI", "sans-serif"],
      },
      borderRadius: {
        lg: "16px",
        md: "12px",
        sm: "8px",
        pill: "9999px",
      },
      boxShadow: {
        luxury: "0 4px 20px -2px rgba(196, 94, 112, 0.08)",
        card: "0 2px 10px 0 rgba(41, 36, 38, 0.04)",
      }
    },
  },
  plugins: [],
};
