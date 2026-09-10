/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ['"Open Sans"', 'system-ui', 'sans-serif'],
        headline: ['Nunito', 'sans-serif'],
        body: ['"Open Sans"', 'sans-serif'],
        mono: ['"Roboto Mono"', 'monospace'],
      },
      colors: {
        teal: {
          50: '#ecfdf5',
          100: '#d1fae5',
          200: '#a7f3d0',
          300: '#6ee7b7',
          400: '#34d399',
          500: '#10b981',
          600: '#059669',
          700: '#047857',
          800: '#065f46',
          900: '#064e3b',
        },
      },
    },
  },
  plugins: [],
  safelist: [
    {
      pattern: /^btn/,
    },
    'form-input',
  ],
};
