module.exports = {
  important: '.sitepilot',
  future: {
    purgeLayersByDefault: true,
    removeDeprecatedGapUtilities: true
  },
  purge: {
    content: [
      'src/**/*.php',
      'includes/**/*.php',
      'assets/**/*.js',
      'assets/**/*.jsx'
    ]
  }
}
