// Hello コンポーネントを定義
import React from "react";
import {useEffect, useState} from "react";
import {BrowserRouter as Router, Redirect, Route, Link, Switch, useHistory, useLocation} from 'react-router-dom';

import axios from "axios";

import RefuelingPage from "./RefuelingPage";
import RefuelingRegistPage from "./RefuelingRegistPage";
import Login from "./login"
import HouseholdAccount from "./HouseholdAccount";

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
    const [login, setLogin]:any = useState(null)
    const history = useHistory();

    useEffect(()=>{
        let cleanUp = false;
        (async ()=>{
            if( !cleanUp)
                setLogin( await getLogin() )
        })()
        const cleanup = () => { cleanUp = true; };
        return cleanup;
    },[])

    return (login===null?(<>ローディング中...</>):
            <div className="container is-max-desktop">

            <Router>
                <Switch>
                    <Route exact path='/refueling'  render={ ()=> (login!==null&&login!==false?<RefuelingPage /> :<Redirect to={"/mylogin"} />) }/>
                    <Route exact path='/refueling/regist'  render={ ()=> (login!==null?<RefuelingRegistPage /> :<Redirect to={"/mylogin"} />) }/>
                    <Route exact path='/household_account' render={ ()=> (login!==null?<HouseholdAccount />:<Redirect to={"/mylogin"} />) }/>
                    <Route exact path='/mylogin' render={()=>
                        <Login setLogined={setLogin}/>
                    }/>
                </Switch>
            </Router>
            </div>
    )
}

