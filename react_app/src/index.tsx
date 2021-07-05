import * as React from 'react';
import * as ReactDom from 'react-dom';
import Hello from "./hello";

// Hello コンポーネントの属性（プロパティ）を定義
interface HelloProps {
    name?: string;  // オプショナルな name 属性
}

const root = document.getElementById('root')

// Hello コンポーネントを <div id="root"> に表示
ReactDom.render(
	<Hello />,root
);