const Encore = require('@symfony/webpack-encore');
const webpack = require('webpack');

Encore
    .setOutputPath('../backend/public/build/')
    .setPublicPath('/build')
    .addEntry('main', './src/main.js')
    .enableVueLoader()
    .enableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    .addPlugin(new webpack.DefinePlugin({
        __VUE_OPTIONS_API__: true,
        __VUE_PROD_DEVTOOLS__: false,
        __VUE_PROD_HYDRATION_MISMATCH_DETAILS__: false
    }));

module.exports = Encore.getWebpackConfig();