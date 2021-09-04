const path = require('path');
const mix = require('laravel-mix');
const TerserPlugin = require('terser-webpack-plugin');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.webpackConfig({
    devtool: mix.inProduction() ? false : 'source-map',
    resolve: {
        extensions: ['.js', '.jsx', '.vue'],
        alias: {
            'ziggy-js': path.join(__dirname, 'vendor/tightenco/ziggy/dist/vue.m.js'),
            vue$: path.join(__dirname, 'node_modules/vue/dist/vue.esm-bundler.js'),
            '@': path.join(__dirname, 'resources/assets/js'),
        },
    },
    optimization: {
        minimize: mix.inProduction(),
        minimizer: [
            new TerserPlugin ({
                extractComments: false,
                terserOptions: {
                    compress: mix.inProduction(),
                },
            }),
        ],
    },
})
    .css(
        'resources/assets/css/app.css',
        'css',
        [
            require('tailwindcss'),
            require('postcss-import'),
        ],
    )
    .js('resources/assets/js/app.js', 'js')
    .vue()
    .extract(['vue', 'moment', 'moment-timezone', 'axios'])
    .version()
    .setPublicPath('public_html');
