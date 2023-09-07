const mix = require('laravel-mix')
const webpack = require('webpack');
const CompressionPlugin = require("compression-webpack-plugin");

module.exports = {
    plugins: [
        new CompressionPlugin({
            algorithm: 'gzip',
            test: /\.(.js|css|svg)$/,
        }),
        new webpack.ProvidePlugin({
            $: 'jquery',
            jQuery: 'jquery'
        })
    ]
};

mix.options({
    processCssUrls: false
});

mix.sass('./assets/sass/main.scss', './dist/css/main.css');
mix.js('./assets/scripts/main.js', './dist/js/bundle.js');