const Encore = require('@symfony/webpack-encore');

Encore
    .setOutputPath('../backend/public/build/')
    .setPublicPath('/build')
    .addEntry('main', './src/main.js') // <-- Corrige ici
    .enableVueLoader()
    .enableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
;

module.exports = Encore.getWebpackConfig();