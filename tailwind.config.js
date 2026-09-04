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
        primary: {
          DEFAULT: '#059669',
          hover: '#047857',
          50: '#ecfdf5',
          100: '#d1fae5',
          200: '#a7f3d0',
          500: '#10b981',
          600: '#059669',
          700: '#047857',
          800: '#065f46',
          900: '#064e3b',
        },
        secondary: {
          DEFAULT: '#EA580C',
          hover: '#C2410C',
          50: '#fff7ed',
          100: '#ffedd5',
          500: '#f97316',
          600: '#ea580c',
          700: '#c2410c',
        },
        tertiary: '#1E3A5F',
        cause: {
          green: '#059669',
          'green-dark': '#047857',
          orange: '#EA580C',
          'orange-dark': '#C2410C',
          navy: '#1E3A5F',
          'navy-light': '#2A4D7A',
          surface: '#FFFFFF',
        },
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
      borderRadius: {
        'cause-sm': '4px',
        'cause-md': '8px',
        'cause-lg': '12px',
        'cause-full': '9999px',
      },
      boxShadow: {
        'cause-sm': '0 1px 3px rgba(0, 0, 0, 0.06)',
        'cause-md': '0 4px 8px rgba(0, 0, 0, 0.08)',
        'cause-lg': '0 8px 20px rgba(0, 0, 0, 0.10)',
        'green': '0 4px 12px rgba(5, 150, 105, 0.20)',
        'orange': '0 4px 12px rgba(234, 88, 12, 0.20)',
      }
    },
  },
  plugins: [],
}
