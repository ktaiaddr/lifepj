import * as React from "react";
import axios, {AxiosResponse} from "axios";
import {Redirect, useHistory } from "react-router-dom";

// Hello コンポーネントの属性（プロパティ）を定義
interface HelloProps {
    name?: string;  // オプショナルな name 属性
    age?: number|string;  // オプショナルな name 属性
    setLogined:(newValue:boolean)=>void
}
interface StateProps {
    mail?: string;  // オプショナルな name 属性
    password?:string
    login:boolean
    loginerror:boolean
}


export default class Login extends React.Component<HelloProps,StateProps> {

    constructor(props:any) {
        super(props);
        this.state = {mail : "test@test444444.local",password:"", login: false, loginerror: false }
    }
    setEmail(event: React.ChangeEvent<HTMLInputElement>){
        this.setState({
            mail: event.target.value
        });
    }
    setPassword(event: React.ChangeEvent<HTMLInputElement>){
        this.setState({
            password: event.target.value
        });
    }
    async login(){

        this.setState({loginerror: false});

        const instance = axios.create({withCredentials: true})

        interface resType {
            data: { result:string }
        }

        const loginParam = {
            email:this.state.mail,
            password:this.state.password,
        }

        const loginResult : resType = await instance.post('http://localhost:9000/api/mylogin'
            , loginParam
            ,{ withCredentials: true }
        ).catch( e => {
            this.setState({loginerror: true});
            return {data:{result:'ng'}};
        });

        if( loginResult )
            if( loginResult.data.result =='ok' ){
                this.props.setLogined(true)
                this.setState({login: true});
            }

    }
    render(): React.ReactNode {
        // const history = useHistory()

        if(this.state.login)
            return <Redirect to="/user_page" />

        return (
            <div>
                <form>
                    {this.state.loginerror && (<h1>ログインエラー</h1>)}
                    <label>
                        mail:
                        <input type="text" name="mail" value={this.state.mail} onInput={this.setEmail.bind(this)} />
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
