import {Link, useLocation} from "react-router-dom";
import * as React from "react";

export default ()=>{

    const refueling_route = "/refueling"
    const household_account = "/household_account"
    const _location = useLocation();

    function toggle_active(){
        const target=document.getElementById('navbarBasicExample');
        if(target)target.classList.toggle('is-active')
    }

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
                </div>
            </div>
        </nav>
    );

}