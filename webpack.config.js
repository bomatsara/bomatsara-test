'use strict';

const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const CssMinimizerPlugin = require("css-minimizer-webpack-plugin");
const TerserPlugin = require("terser-webpack-plugin");
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const path = require('path');
const glob = require('glob');

const entryCSS = {
    shortcode_post: './assets/scss/shortcode/_shortcode-posts.scss',
};

const entryJS = {
    main: './assets/js/main.js',
};

const entry = Object.assign({}, entryCSS, entryJS);

module.exports = {
    mode: process.env.NODE_ENV === 'production' ? 'production' : 'development',
    watch: true,

    entry: entry,

    output: {
        path: path.resolve(__dirname, 'assets/dist'),
        filename: '[name].min.js'
    },

    module: {
        rules: [
            {
                test: /\.js$/,
                exclude: /node_modules/,
                use: {
                    loader: 'babel-loader',
                    options: {
                        presets: ['@babel/preset-env']
                    }
                }
            },
            {
                test: /\.scss$/,
                use: [
                    MiniCssExtractPlugin.loader, 'css-loader', 'postcss-loader', 'sass-loader',
                ]
            }
        ]
    },

    plugins: [
        new MiniCssExtractPlugin({
            filename: '[name].min.css',
            runtime: false
        }),
        {
            apply: (compiler) => {
                compiler.hooks.emit.tap('RemoveUnusedJSFiles', (compilation) => {
                    Object.keys(entryCSS).forEach((name) => {
                        const fileName = `${name}.min.js`;
                        if (compilation.assets[fileName]) {
                            delete compilation.assets[fileName];
                        }
                    });
                });
            },
        }
    ],

    stats: {
        preset: 'minimal',
        moduleTrace: true,
        version: false,
    },

    externals: {
        jquery: 'jQuery'
    },

    optimization: {
        minimize: true,
        usedExports: true,
        minimizer: [
            new CssMinimizerPlugin(),
            new TerserPlugin({
                parallel: true,
                terserOptions: {
                    compress: {
                        drop_console: false,
                    },
                },
            }),
        ],
    },

    devtool: false
};