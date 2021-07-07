// Hello コンポーネントを定義
import * as React from "react";
import RefuelingPage from "./RefuelingPage";
import RefuelingRegistPage from "./RefuelingRegistPage";
import Login from "./login"

import axios from "axios";
import { BrowserRouter as Router, Redirect, Route, Link, Switch, useLocation } from 'react-router-dom';
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

export default class Base extends React.Component<HelloProps,HelloState> {

    componentDidMount() {
        (async ()=>{
            this.setState( { login: await getLogin()} )
        })();
    }

    constructor(props:any) {
        super(props);
        this.state = { login: null }
    }

    setLogined(newValue:boolean){
        this.setState({
            login: newValue
        })
    }

    render(): React.ReactNode {
        // this.getLocation();
        if( this.state.login === null )
            return (<>ローディング中...</>)


        return (
            <Router>
                <Switch>
                    <Route exact path='/refueling'  render={ ()=> (this.state.login?<RefuelingPage /> :<Redirect to={"/mylogin"} />) }/>
                    <Route exact path='/refueling/regist'  render={ ()=> (this.state.login?<RefuelingRegistPage /> :<Redirect to={"/mylogin"} />) }/>
                    <Route exact path='/household_account' render={ ()=> (this.state.login?<HouseholdAccount />:<Redirect to={"/mylogin"} />) }/>
                    <Route exact path='/mylogin' render={()=>
                        <Login setLogined={this.setLogined.bind(this)}/>
                    }/>
                </Switch>
            </Router>

            )
    }
}
