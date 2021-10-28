module.exports = {
  purge: [],
  darkMode: false, // or 'media' or 'class'
  theme: {
    extend: {
        width: {
            '96': '24rem'
        }
    },
      spinner: (theme) => ({
         default: {
             color: '#dae1e7',
             size: '1em',
             border: '2px',
             speed: '500ms',
         },
      }),
  },
  variants: {
    extend: {},
  },
  plugins: [
      require('tailwindcss-spinner')(),
  ],
}
