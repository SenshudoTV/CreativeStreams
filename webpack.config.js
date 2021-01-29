'use strict'

const path = require('path')
const { CleanWebpackPlugin } = require('clean-webpack-plugin')
const WebpackAssetsManifest = require('webpack-assets-manifest')
const MiniCssExtractPlugin = require('mini-css-extract-plugin')
const TerserPlugin = require('terser-webpack-plugin')
const { VueLoaderPlugin } = require('vue-loader')
const isProduction = (process.env.NODE_ENV === 'production')

module.exports = {
    mode: isProduction ? 'production' : 'development',
    devtool: isProduction ? '' : 'source-map',
    entry: {
        app: path.resolve(__dirname, 'resources/assets/js/app.js'),
    },
    output: {
        crossOriginLoading: 'anonymous',
        path: path.resolve(__dirname, 'public'),
        filename: 'js/[name].[hash:8].js',
        chunkFilename: 'js/[name].[hash:8].js',
    },
    resolve: {
        extensions: ['.js', '.jsx', '.vue'],
        alias: {
            ziggy: path.resolve(__dirname, 'vendor/tightenco/ziggy/dist'),
            vue$: 'vue/dist/vue.runtime.esm.js',
            '@': path.resolve(__dirname, 'resources/assets/js'),
        },
    },
    plugins: [
        new CleanWebpackPlugin({
            cleanOnceBeforeBuildPatterns: [
                path.resolve(__dirname, 'public/css'),
                path.resolve(__dirname, 'public/js'),
                path.resolve(__dirname, 'public/fonts'),
                path.resolve(__dirname, 'public/images'),
            ],
        }),
        new VueLoaderPlugin(),
        new MiniCssExtractPlugin({
            filename: 'css/[name].[hash:8].css',
            chunkFilename: 'css/[name].[hash:8].css',
        }),
        new WebpackAssetsManifest({
            output: 'mix-manifest.json',
            publicPath: true,
            transform: (assets) => {
                for (const property in assets) {
                    const filePath = path.dirname(assets[property])
                    const assetKey = (filePath != 'fonts' && filePath != 'images' && !property.includes('js/')) ? '/' + filePath + '/' + property : '/' + property

                    assets = {
                        ...assets,
                        [assetKey]: assets[property],
                    }

                    delete assets[property]
                }

                return assets
            },
        }),
    ],
    module: {
        rules: [
            {
                test: /\.vue$/,
                use: 'vue-loader',
                include: path.resolve(__dirname, 'resources/assets/js'),
            },
            {
                test: /\.(js|jsx)$/,
                exclude: /node_modules/,
                loader: 'babel-loader',
                include: path.resolve(__dirname, 'resources/assets/js'),
            },
            {
                test: /\.s[ac]ss$/i,
                use: [
                    {
                        loader: MiniCssExtractPlugin.loader,
                    },
                    {
                        loader: 'css-loader',
                        options: {
                            sourceMap: !isProduction,
                            importLoaders: 1,
                        },
                    },
                    {
                        loader: 'sass-loader',
                        options: {
                            sourceMap: !isProduction,
                            sassOptions: {
                                outputStyle: 'compressed',
                            },
                        },
                    },
                ],
                include: path.resolve(__dirname, 'resources/assets/sass'),
            },
            {
                test: /\.(woff(2)?|ttf|eot|svg)(\?v=\d+\.\d+\.\d+)?$/,
                use: [
                    {
                        loader: 'file-loader',
                        options: {
                            name: '[folder]/[name].[hash:8].[ext]',
                        },
                    },
                ],
            },
            {
                test: /\.(png|jpe?g|gif|webp)$/i,
                use: [
                    {
                        loader: 'file-loader',
                        options: {
                            name: '[folder]/[name].[hash:8].[ext]',
                            publicPath: '../',
                            esModule: false,
                            useRelativePaths: true,
                        },
                    },
                ],
            },
        ],
    },
    optimization: {
        moduleIds: 'hashed',
        runtimeChunk: 'single',
        splitChunks: {
            cacheGroups: {
                vendor: {
                    test: /[\\/]node_modules[\\/]/,
                    name: 'vendors',
                    chunks: 'all',
                },
            },
        },
        minimize: true,
        minimizer: [
            new TerserPlugin ({
                cache: true,
                parallel: false,
                extractComments: false,
                sourceMap: !isProduction,
                terserOptions: {
                    compress: isProduction,
                },
            }),
        ],
    },
}
