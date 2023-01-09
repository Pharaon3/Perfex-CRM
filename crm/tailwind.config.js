/** @type {import('tailwindcss').Config} */

const colors = require("tailwindcss/colors");

module.exports = {
  content: [
    "./application/views/admin/*.php",
    "./application/views/admin/**/*.php",
    "./application/views/authentication/*.php",
    "./application/views/authentication/**/*.php",
    "./application/views/themes/**/*.php",
    "./application/views/themes/**/**/*.php",
    "./application/views/**/*.php",
    "./modules/**/views/*.php",
    "./modules/**/views/**/*.php",
    "./assets/js/main.js",
    "./assets/js/projects.js",
    "./assets/js/tickets.js",
    "./assets/js/app.js",
    "./assets/js/map.js",
    "./install/*.php",
  ],
  safelist: [
    {
      pattern:
        /^panel|btn-|bg-|text-|label-|badge-|bg-|dropdown|nav-|nav-tabs|pagination-|fc-|alert-.*/,
    },
  ],
  prefix: "tw-",
  theme: {
    extend: {
      fontSize: {
        sm: "0.8rem",
        base: "0.9rem",
        normal: "0.84375rem",
      },
      animation: {
        "spin-slow": "spin 3s linear infinite",
      },
    },
    colors: {
      transparent: "transparent",
      inherit: colors.inherit,
      current: "currentColor",

      black: colors.black,
      white: colors.white,

      neutral: colors.slate,
      danger: colors.red,
      warning: colors.yellow,
      success: colors.green,
      info: colors.sky,
      primary: colors.blue,
    },
  },
  plugins: [],
  corePlugins: {
    preflight: false,
  },
};
