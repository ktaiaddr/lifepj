[参考サイト](https://maku.blog/p/m4dmt3a/)

```
docker run -itd --rm -w /opt -u $(id -u) -v $(pwd)/:/opt/ --name lifepj_node node:14.16.1-alpine /bin/sh

/opt $ mkdir react_app && cd react_app
/opt/react_app $ npm init -y
Wrote to /opt/react_app/package.json:

{
"name": "react_app",
"version": "1.0.0",
"description": "",
"main": "index.js",
"scripts": {
"test": "echo \"Error: no test specified\" && exit 1"
},
"keywords": [],
"author": "",
"license": "ISC"
}


/opt/react_app $ npm install --save-dev typescript
npm notice created a lockfile as package-lock.json. You should commit this file.
npm WARN react_app@1.0.0 No description
npm WARN react_app@1.0.0 No repository field.

+ typescript@4.3.5
  added 1 package from 1 contributor and audited 1 package in 4.953s
  found 0 vulnerabilities

/opt/react_app $ npm install --save react react-dom
npm WARN react_app@1.0.0 No description
npm WARN react_app@1.0.0 No repository field.

+ react@17.0.2
+ react-dom@17.0.2
  added 6 packages from 3 contributors and audited 7 packages in 2.757s
  found 0 vulnerabilities

/opt/react_app $ npm install --save-dev @types/react @types/react-dom
npm WARN react_app@1.0.0 No description
npm WARN react_app@1.0.0 No repository field.

+ @types/react-dom@17.0.8
+ @types/react@17.0.13
  added 5 packages from 30 contributors and audited 13 packages in 2.493s
  found 0 vulnerabilities

/opt/react_app $ npm install --save-dev webpack webpack-cli ts-loader html-webpack-plugin
npm WARN react_app@1.0.0 No description
npm WARN react_app@1.0.0 No repository field.

+ html-webpack-plugin@5.3.2
+ webpack-cli@4.7.2
+ ts-loader@9.2.3
+ webpack@5.42.0
  added 169 packages from 189 contributors and audited 181 packages in 41.886s

29 packages are looking for funding
run `npm fund` for details

found 0 vulnerabilities


/opt/react_app $ touch webpack.config.js

const path = require('path');
const HtmlWebpackPlugin = require('html-webpack-plugin');

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
	})
    ]
};

/opt/react_app $ touch tsconfig.json

{
"compilerOptions": {
"target": "ES2015",
"module": "commonjs",
"lib": ["esnext", "dom"],
"jsx": "react",  // .tsx ファイル内の JSX 記述を認識
"strict": true,
"esModuleInterop": true,
"skipLibCheck": true,
"forceConsistentCasingInFileNames": true,

    // 出力先などは webpack 側で指定するので本質的には必要なし
    "sourceMap": true,
    "outDir": "./dist",
    "sourceRoot": "./src",
}
}
```