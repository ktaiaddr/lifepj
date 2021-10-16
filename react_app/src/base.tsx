// Hello コンポーネントを定義
import React from "react";
import {useEffect, useState} from "react";
import {BrowserRouter as Router, Redirect, Route, Link, Switch, useHistory, useLocation} from 'react-router-dom';

import axios from "axios";

import RefuelingPage from "./Refueling/RefuelingPage";
import RefuelingRegistPage from "./Refueling/RefuelingRegistPage";
import Login from "./Login/login"
import HouseholdAccount from "./HouseholdAccount/HouseholdAccount";
import PasswordForget from "./Login/password_forget";
import ResetPassword from "./Login/reset_password";
import RegisterUser from "./Login/register_user";
import Verifyemail from "./Login/verify_email";
import HouseholdAccountRegist from "./HouseholdAccount/HouseholdAccountRegist";

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

    const loginCheckResult = await instance.get('http://'+process.env.API_ENDPOINT+'/api/mylogincheck').catch(e=> {
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

                    {/*:パラメーター?でオプションパラメーターとなる*/}
                    <Route exact path='/refueling/regist/:refueling_id?'
                           render={ ({match})=> (
                               login!==null&&login!==false
                                   ?<RefuelingRegistPage match={match} />
                                   :<Redirect to={"/mylogin"} />
                           ) }
                    />

                    <Route exact path='/household_account' render={ ()=> (login!==null&&login!==false?<HouseholdAccount />:<Redirect to={"/mylogin"} />) }/>

                    <Route exact path='/household_account/regist'
                           render={ ({match})=> (
                               login!==null&&login!==false
                                   ?<HouseholdAccountRegist match={match} />
                                   :<Redirect to={"/mylogin"} />
                           ) }
                    />

                    <Route exact path='/mylogin' render={()=>
                        <Login setLogined={setLogin}/>
                    }/>

                    <Route exact path='/myforgetpassword' render={()=>
                        <PasswordForget />
                    }/>

                    <Route path='/myresetpassword/:token' render={({match})=>
                        <ResetPassword match={match} />
                    }/>
                    <Route exact path='/myregisteruser' render={()=>
                        <RegisterUser />
                    }/>
                    <Route exact path='/myverifyemail/:id/:hash'  render={({match})=>
                        <Verifyemail  match={match} />
                    }/>
                </Switch>
            </Router>
            </div>
    )
}

