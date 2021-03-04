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
        secondary: 'var(--sp-color-secondary)',
        third: 'var(--sp-color-third)',
        fourth: 'var(--sp-color-fourth)'
      }
    }
  }
}
