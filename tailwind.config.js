module.exports = {
  important: '.sitepilot',
  future: {
    purgeLayersByDefault: true,
    removeDeprecatedGapUtilities: true
  },
  purge: {
    content: [
      'src/**/*.php',
      'views/**/*.php',
      'views/**/*.blade.php',
      'includes/**/*.php',
      'assets/**/*.js',
      'assets/**/*.jsx'
    ],
    options: {
      safelist: ['sitepilot']
    }
  }
}
