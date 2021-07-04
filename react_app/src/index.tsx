import * as React from 'react';
import * as ReactDom from 'react-dom';

// Hello コンポーネントの属性（プロパティ）を定義
interface HelloProps {
    name?: string;  // オプショナルな name 属性
}

// Hello コンポーネントを定義
class Hello extends React.Component<HelloProps> {
    public render(): React.ReactNode {
	const name = this.props.name ?? 'Unknown';
	return (
	    <b>Hello, {name}!</b>
	);
    }
}

// Hello コンポーネントを <div id="root"> に表示
ReactDom.render(
    <Hello name="sssssssssssss" />,
    document.getElementById('root')
);