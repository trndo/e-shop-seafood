let Encore = require('@symfony/webpack-encore');
let webpack = require('webpack');
Encore
    // directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // public path used by the web server to access the output path
    .setPublicPath('/build')
    // only needed for CDN's or sub-directory deploy
    //.setManifestKeyPrefix('build/')

    /*
     * ENTRY CONFIG
     *
     * Add 1 entry for each "page" of your app
     * (including one that's included on every page - e.g. "app")
     *
     * Each entry will result in one JavaScript file (e.g. app.js)
     * and one CSS file (e.g. app.css) if you JavaScript imports CSS.
     *
     */
    .addEntry('app', './assets/js/app.js')
    .addEntry('editSupply', './assets/js/supply.js')
    .addEntry('search', './assets/js/search.js')
    .addEntry('photos', './assets/js/photos.js')
    .addEntry('autocomplete','./assets/js/autocomplete.js')
    .addEntry('addReceipt','./assets/js/addReceipt.js')
    .addEntry('rating','./assets/js/rating.js')
    .addEntry('main','./assets/js/front/raki-front.js')
    .addEntry('promotion','./assets/js/promotion.js')
    .addEntry('addSales', './assets/js/sales.js')
    .addEntry('common','./assets/js/front/common.js')
    .addEntry('order','./assets/js/front/order.js')
    .addEntry('cart','./assets/js/front/cart.js')
    .addEntry('video','./assets/js/front/video.js')
    .addEntry('slider','./assets/js/front/hard-slider.js')


    // When enabled, Webpack "splits" your files into smaller pieces for greater optimization.
    .splitEntryChunks()

    // will require an extra script tag for runtime.js
    // but, you probably want this, unless you're building a single-page app
    .enableSingleRuntimeChunk()

    /*
     * FEATURE CONFIG
     *
     * Enable & configure other features below. For a full
     * list of features, see:
     * https://symfony.com/doc/current/frontend.html#adding-more-features
     */
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    // enables hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())

    // enables @babel/preset-env polyfills
    .configureBabel(() => {}, {
        useBuiltIns: 'usage',
        corejs: 3
    })
    .addPlugin(new webpack.ProvidePlugin({
        $: 'jquery',
        jQuery: 'jquery'
    }))
    // enables Sass/SCSS support
    //.enableSassLoader()

    // uncomment if you use TypeScript
    //.enableTypeScriptLoader()

    // uncomment to get integrity="..." attributes on your script & link tags
    // requires WebpackEncoreBundle 1.4 or higher
    //.enableIntegrityHashes()

    // uncomment if you're having problems with a jQuery plugin
    //.autoProvidejQuery()

    // uncomment if you use API Platform Admin (composer req api-admin)
    //.enableReactPreset()
    //.addEntry('admin', './assets/js/admin.js')
;

module.exports = Encore.getWebpackConfig();
