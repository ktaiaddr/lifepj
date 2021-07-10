import * as React from "react";
import {Link, useLocation} from "react-router-dom";

export default ()=>{
    const _location = useLocation();
    console.log(_location)

    function toggle_active(){
        const target=document.getElementById('navbarBasicExample2');
        if(target)target.classList.toggle('is-active')
    }
    return (
        <nav className="navbar is-primary" role="navigation" aria-label="main navigation">
            <div className="navbar-brand">
                <a role="button" className="navbar-burger" aria-label="menu" aria-expanded="false"
                   data-target="navbarBasicExample2" onClick={toggle_active}>
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
                </a>
            </div>
            <div id="navbarBasicExample2" className="navbar-menu  is-active">
                <div className="navbar-start">
                <Link className="navbar-item" to="/refueling">一覧</Link>
                <Link className="navbar-item" to="/refueling/regist">登録</Link>
                </div>
            </div>
        </nav>
    );
}