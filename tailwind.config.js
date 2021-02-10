module.exports = {
  important: '.sp-block',
  future: {
    purgeLayersByDefault: true,
    removeDeprecatedGapUtilities: true
  },
  purge: {
    content: [
      '**/*.php',
      '**/*.blade.php',
      './assets/js/*.js',
    ],
    options: {
      safelist: ['sp-block']
    }
  },
  corePlugins: {
    container: false
  },
  theme: {
    extend: {
      colors: {
        primary: 'var(--sp-color-primary)',
        secondary: 'var(--sp-color-secondary)'
      },
      spacing: {
        '3/4': '75%',
        '9/16': '56.25%',
        '9/21': '42.8571%'
      }
    }
  }
}
