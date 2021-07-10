import {Link, Redirect, useLocation} from "react-router-dom";
import * as React from "react";
import axios from "axios";
import {useState} from "react";

export default ()=>{
    const [logout,setLogout] : any = useState(false);
    const refueling_route = "/refueling"
    const household_account = "/household_account"
    const _location = useLocation();

    function toggle_active(){
        const target=document.getElementById('navbarBasicExample');
        if(target)target.classList.toggle('is-active')
    }

    async function _logout(): Promise<void> {

        const instance = axios.create({withCredentials: true})

        interface resType {
            data: { result: string }
        }

        const logoutResult: resType = await instance.post('http://localhost:9000/api/mylogout'
            , {}
            , {withCredentials: true}
        ).catch(e => {
            return {data: {result: 'ng'}};
        });

        if (logoutResult)
            if (logoutResult.data)
                setLogout(true)
    }

    if(logout) return <Redirect to="/mylogin" />

    return (
        <nav className="navbar is-info" role="navigation" aria-label="main navigation">
            <div className="navbar-brand">
                <a role="button" className="navbar-burger" aria-label="menu" aria-expanded="false"
                   data-target="navbarBasicExample" onClick={toggle_active}>
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
                </a>
            </div>
            <div id="navbarBasicExample" className="navbar-menu is-active">
                <div className="navbar-start">
                    <Link className="navbar-item" to={refueling_route}>給油データ</Link>
                    <Link className="navbar-item" to={household_account}>家計簿</Link>
                    <button onClick={_logout}>ログアウト</button>
                </div>
            </div>
        </nav>
    );

}