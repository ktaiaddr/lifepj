import * as React from "react";
import axios from "axios";
import {Redirect, useHistory } from "react-router-dom";

// Hello コンポーネントの属性（プロパティ）を定義
interface HelloProps {
    alert?: ()=>void;
    alert2?: ()=>void;
    name?: string;  // オプショナルな name 属性
    age?: number|string;  // オプショナルな name 属性
}
interface StateProps {
    name?: string;  // オプショナルな name 属性
    age?: number|string;  // オプショナルな name 属性
    password?:string
    login:boolean
}


export default class Login extends React.Component<HelloProps,StateProps> {

    constructor(props:any) {
        super(props);
        this.state = {name : "test@test444444.local",age:"hoge",password:"", login: false }
    }
    setEmail(event: React.ChangeEvent<HTMLInputElement>){
        this.setState({
            name: event.target.value
        });
    }
    setPassword(event: React.ChangeEvent<HTMLInputElement>){
        this.setState({
            password: event.target.value
        });
    }

    async login(){

        const instance = axios.create({
            withCredentials: true
        })
        const res3 = await instance.post('http://localhost:9000/api/mylogin', {
                email:this.state.name,
                password:this.state.password,
            },
            { withCredentials: true }
        ).catch((e)=>{
            console.log(e)
            return <Redirect to="/other" />
        });
        this.setState({
            login: true
        });

    }
    render(): React.ReactNode {
        const name = this.props.name ?? 'Unknown';
        // const history = useHistory()

        if(this.state.login){
            return <Redirect to="/test2" />
        }

        return (
            <div>
                <form>
                    <label>
                        mail:
                        <input type="text" name="mail" value={this.state.name} onInput={this.setEmail.bind(this)} />
                    </label>
                    <label>
                        password:
                        <input type="password" name="password" value={this.state.password} onInput={this.setPassword.bind(this)} />
                    </label>
                    <input type="button" value="Submit" onClick={this.login.bind(this)} />
                </form>
            </div>
        );
    }
}
