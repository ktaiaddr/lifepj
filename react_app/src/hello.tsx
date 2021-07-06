// Hello コンポーネントを定義
import * as React from "react";
import HelloChild  from "./hello_child";
import Login from "./login"

import axios from "axios";
import { BrowserRouter as Router, Redirect, Route, Link, Switch } from 'react-router-dom';
import HelloChild2 from "./hello_child2";

axios.defaults.headers['X-Requested-With'] = 'XMLHttpRequest';


// Hello コンポーネントの属性（プロパティ）を定義
interface HelloState {
    name?: string;  // オプショナルな name 属性
    age?: number|string;  // オプショナルな name 属性
    logined?:boolean
}
interface HelloProps {
    logind?: boolean;  // オプショナルな name 属性
}

export default class Hello extends React.Component<HelloProps,HelloState> {
    async componentDidMount() {

    }
    async componentWillMount() {
    }
    constructor(props:any) {

        super(props);

        this.state = {logined: props.logind}

    }
    setLogined(newValue:boolean){
        this.setState({
            logined: newValue
        })
    }
    render(): React.ReactNode {
        const name = this.state.name ?? 'Unknown';

        return (
            <Router>
                <div>
                    {this.state.logined &&
                    <>
                        <div>ログインしてます</div>
			[<Link to="/user_page">給油データ</Link>]
			[<Link to="/user_page2">給油データ2</Link>]
                    </>
                    }
                </div>
                <Switch>
                    <Route exact path='/mylogin' render={()=>
                        <Login setLogined={this.setLogined.bind(this)}/>
                    }/>
                    {this.state.logined?
                        <Route exact path='/user_page' render={()=>
                            <HelloChild />
                        }/>
                        :<Redirect to="/mylogin" />
                    }
                    {this.state.logined?
                        <Route exact path='/user_page2' render={()=>
                            <HelloChild2 />
                        }/>
                        :<Redirect to="/mylogin" />
                    }
                    <Route />
                </Switch>

            </Router>
        );
    }
}
