var Encore = require('@symfony/webpack-encore');

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .cleanupOutputBeforeBuild()
    .addStyleEntry('styles', './src/UI/assets/css/app.scss')
    .enableSassLoader()
    .enableSourceMaps(!Encore.isProduction())
    .enableBuildNotifications()
    .enableVersioning()
;

module.exports = Encore.getWebpackConfig();
