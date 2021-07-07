// Hello コンポーネントを定義
import * as React from "react";
import RefuelingPage from "./RefuelingPage";
import RefuelingRegistPage from "./RefuelingRegistPage";
import Login from "./login"

import axios from "axios";
import {BrowserRouter as Router, Redirect, Route, Link, Switch, useLocation, useHistory} from 'react-router-dom';
import HouseholdAccount from "./HouseholdAccount";
import UserHeader from "./user_header";
import {useEffect, useState} from "react";

axios.defaults.headers['X-Requested-With'] = 'XMLHttpRequest';


// Hello コンポーネントの属性（プロパティ）を定義
interface HelloState {
    login:boolean|null
}
interface HelloProps {
    login?: boolean;  // オプショナルな name 属性
}

/**
 * ログイン状態を取得
 */
const getLogin = async ()=>{
    const instance = axios.create({ withCredentials: true })
    const loginCheckResult = await instance.get('http://localhost:9000/api/mylogincheck').catch(e=> {
        return {data:{result:'fail'}}
    })
    return ( loginCheckResult.data.result == 'ok' )
}

export default ()=>{
    const history = useHistory()
    const [login, setLogin]:any = useState(null)

    useEffect(()=>{
        let cleanUp = false;
        (async ()=>{
            if( !cleanUp)
                setLogin( await getLogin() )
        })()
        const cleanup = () => { cleanUp = true; };
        return cleanup;
    })

    return (login===null?(<>ローディング中...</>):
            <Router>
                <Switch>
                    <Route exact path='/refueling'  render={ ()=> (login?<RefuelingPage /> :<Redirect to={"/mylogin"} />) }/>
                    <Route exact path='/refueling/regist'  render={ ()=> (login?<RefuelingRegistPage /> :<Redirect to={"/mylogin"} />) }/>
                    <Route exact path='/household_account' render={ ()=> (login?<HouseholdAccount />:<Redirect to={"/mylogin"} />) }/>
                    <Route exact path='/mylogin' render={()=>
                        <Login setLogined={setLogin}/>
                    }/>
                </Switch>
            </Router>
    )
}

