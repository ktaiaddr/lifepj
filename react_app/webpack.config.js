const path = require('path');
const HtmlWebpackPlugin = require('html-webpack-plugin');
const webpack = require('webpack');
const dotenv = require('dotenv');
const env = dotenv.config().parsed;

module.exports = {
    // 開発用の設定
    mode: 'development',

    // エントリポイントとなるコード
    entry: './src/index.tsx',

    // バンドル後の js ファイルの出力先
    output: {
	path: path.resolve(__dirname, '../laravel_app/public/react'),
	filename: 'index.js'
    },

    // import 時に読み込むファイルの拡張子
    resolve: {
	extensions: ['.js', '.json', '.ts', '.tsx']
    },

    // ソースマップファイルの出力設定
    devtool: 'source-map',

    module: {
	rules: [
	    // TypeScript ファイル (.ts/.tsx) を変換できるようにする
	    {
		test: /\.tsx?$/,
		use: "ts-loader",
		include: path.resolve(__dirname, 'src'),
		exclude: /node_modules/
	    }
	]
    },

    plugins: [
	// HTML ファイルの出力設定
	new HtmlWebpackPlugin({
	    template: './src/index.html'
	}),
	new webpack.DefinePlugin({
	    'process.env': JSON.stringify(env)
	})
    ]
};