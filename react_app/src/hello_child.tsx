// Hello コンポーネントを定義
import * as React from "react";

// Hello コンポーネントの属性（プロパティ）を定義
interface HelloProps {
    alert?: ()=>void;
    alert2?: ()=>void;
    name?: string;  // オプショナルな name 属性
    age?: number|string;  // オプショナルな name 属性
}


export default class HelloChild extends React.Component<HelloProps> {

    constructor(props:any) {
        super(props);
        this.state = {name : "fug",age:"hoge"}
    }
    render(): React.ReactNode {
        const name = this.props.name ?? 'Unknown';

        return (
            <div>
                <b onClick={this.props.alert}>1, {this.props.name}!</b>
                <b onClick={this.props.alert2}>{this.props.age}歳</b>
            </div>
        );
    }
}
