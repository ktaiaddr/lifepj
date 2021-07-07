import * as React from "react";
import axios, {AxiosResponse} from "axios";
import {BrowserRouter as Router, Redirect, Route, Switch, useHistory} from "react-router-dom";
import {useEffect, useState} from "react";

// Hello コンポーネントの属性（プロパティ）を定義
interface HelloProps {
    setLogined:(newValue:boolean)=>void
}
interface StateProps {
    mail?: string;  // オプショナルな name 属性
    password?:string
    login:boolean
    loginerror:boolean
}


export default (props:HelloProps)=>{

    const [mail,       setMail]:any = useState('test@test444444.local')
    const [password,   setPassword]:any = useState('')
    const [login,      setLogin]:any = useState(false)
    const [loginError, setLoginError]:any = useState(false)

    function setEmail(event: React.ChangeEvent<HTMLInputElement>){
        setMail( event.target.value);
    }
    function _setPassword(event: React.ChangeEvent<HTMLInputElement>){
        setPassword( event.target.value);
    }
    async function _login(){

        setLoginError(false);

        const instance = axios.create({withCredentials: true})

        interface resType {
            data: { result:string }
        }

        const loginParam = {
            email:mail,
            password:password,
        }

        const loginResult : resType = await instance.post('http://localhost:9000/api/mylogin'
            , loginParam
            ,{ withCredentials: true }
        ).catch( e => {
            setLoginError( true);
            return {data:{result:'ng'}};
        });

        if( loginResult )
            if( loginResult.data.result =='ok' ){
                props.setLogined(true)
                setLogin( true);
            }
    }

    if(login) return <Redirect to="/refueling" />
    else{
        return (
            <div>
                <form>
                    {loginError && <h1>ログインエラー</h1>}
                    <label>
                        mail:
                        <input type="text" name="mail" value={mail} onInput={setEmail} />
                    </label>
                    <label>
                        password:
                        <input type="password" name="password" value={password} onInput={_setPassword} />
                    </label>
                    <input type="button" value="Submit" onClick={_login} />
                </form>
            </div>
        )
    }
}
