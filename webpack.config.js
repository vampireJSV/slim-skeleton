"use strict";

require("dotenv").config();

const
    path = require("path"),
    ExtractTextPlugin = require("extract-text-webpack-plugin"),
    ManifestPlugin = require("webpack-manifest-plugin"),
    file_name_pattern = process.env.DEBUG == 0 ? "[name]" : "[name].[chunkhash]",
    sass_loader = process.env.DEBUG == 0 ? "sass-loader?outputStyle=compressed" : "sass-loader",
    extract_sass = new ExtractTextPlugin(`${file_name_pattern}.css`),
    UglifyJsPlugin = require('uglifyjs-webpack-plugin'),
    CleanWebpackPlugin = require('clean-webpack-plugin'),
    CopyWebpackPlugin = require('copy-webpack-plugin');
var webpack = require("webpack");
// var uglifyJ =  ? : null;

module.exports = {
    entry: {
        main: path.join(__dirname, "resources/assets/js/bootstrap.js"),
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
                    use: ["css-loader", sass_loader]
                })
            },
            {
                test: /\.font\.js/,
                loader: ExtractTextPlugin.extract({
                    use: [
                        'css-loader',
                        {
                            loader: 'webfonts-loader',
                            options: {publicPath: '/build/'}
                        }
                    ]
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
    plugins: getPlugins(),
    resolve: {
        alias: {
            jquery: "jquery/src/jquery"
        }
    }
};

function getPlugins() {
    var plugins = [
        extract_sass,
        new ManifestPlugin(),
        new webpack.ProvidePlugin({
            $: "jquery",
            jQuery: "jquery"
        }),
        new CleanWebpackPlugin(["public"], {exclude: ['index.php', '.htaccess']}),
        new CopyWebpackPlugin([{
            from: 'resources/assets/favicon/site.webmanifest',
            to: '../site.webmanifest'
        }, {
            from: 'resources/assets/favicon/browserconfig.xml',
            to: '../browserconfig.xml'
        }, {
            from: 'resources/assets/favicon/*',
            to: 'favicon',
            flatten: true,
            ignore: ['site.webmanifest', 'browserconfig.xml', 'html_code.html']
        }, {
            from: '**/*',
            to: '',
            flatten: false,
            context: "resources/assets/copy"
        }
        // , {
        //     from: 'skins/**/*',
        //     to: '',
        //     flatten: false,
        //     context: "resources/assets/vendor/layerslider"
        // }
        ])];
    if (process.env.DEBUG == 0) {
        plugins.push(new UglifyJsPlugin({
            sourceMap: true
        }));
    }

    return plugins;
}