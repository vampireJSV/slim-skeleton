"use strict";

require("dotenv").config();

const
    path = require("path"),
    ExtractTextPlugin = require("extract-text-webpack-plugin"),
    ManifestPlugin = require("webpack-manifest-plugin"),
    file_name_pattern = process.env.DEBUG == 0 ? "[name]" : "[name].[chunkhash]",
    extract_sass = new ExtractTextPlugin(`${file_name_pattern}.css`),
    UglifyJsPlugin = require('uglifyjs-webpack-plugin');
var webpack = require("webpack");

module.exports = {
    entry: {
        main: path.join(__dirname, "resources/assets/js/main.js"),
    },
    output: {
        path: path.join(__dirname, "public/build"),
        filename: `${file_name_pattern}.js`
    },

    module: {
        rules: [
            {
                test: /\.scss$/,
                use: extract_sass.extract({
                    use: ["css-loader", "sass-loader?outputStyle=compressed"]
                })
            },
            {
                test: /\.(eot|ttf|woff|woff2)$/,
                loader: "file-loader?name=./assets/[name].[ext]"
            },
            {
                test: /\.(gif|png|jpe?g|svg)$/i,
                use: [
                    'file-loader',
                    {
                        loader: 'image-webpack-loader',
                        options: {
                            bypassOnDebug: true,
                            mozjpeg: {
                                progressive: true,
                                quality: 65
                            },
                            optipng: {
                                enabled: true,
                            },
                            pngquant: {
                                quality: '65-90',
                                speed: 4
                            },
                            gifsicle: {
                                interlaced: false,
                            },
                            webp: {
                                quality: 75
                            }
                        },
                    },
                ]
            }
        ]
    },
    plugins: [
        extract_sass,
        new ManifestPlugin(),
        // new UglifyJsPlugin({
        //     sourceMap: true
        // }),
        new webpack.ProvidePlugin({
            $: "jquery",
            jQuery: "jquery"
        })
    ],
    resolve: {
        alias: {
            jquery: "jquery/src/jquery"
        }
    }
};