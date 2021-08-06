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

        const logoutResult: resType = await instance.post('http://'+process.env.API_ENDPOINT+'/api/mylogout'
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
        <nav className="navbar navbar-expand-lg navbar-light bg-light mb-5" >
            <div className="container-fluid">
                <a className="navbar-brand" href="#">Navbar</a>
                <button className="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                    <span className="navbar-toggler-icon"></span>
                </button>
                <div className="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul className="navbar-nav me-auto mb-2 mb-lg-0">
                        <li className="nav-item">
                            {/*<a className="nav-link active" aria-current="page" href="#">Home</a>*/}
                            <Link className="nav-link active" to={refueling_route}>給油データ</Link>
                        </li>
                        <li className="nav-item dropdown">
                            <a className="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                               data-bs-toggle="dropdown" aria-expanded="false">
                            </a>
                            <ul className="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><Link className="dropdown-item" to="/refueling">一覧</Link></li>
                                <li> <Link className="dropdown-item" to="/refueling/regist">登録</Link></li>
                            </ul>
                        </li>

                        <li className="nav-item">
                            {/*<a className="nav-link" href="#">Link</a>*/}
                            <Link className="nav-link" to={household_account}>家計簿</Link>

                        </li>
                    </ul>
                    <form className="d-flex">
                        {/*<button className="btn btn-outline-success" type="submit">Search</button>*/}
                        <button className="btn btn-outline-success" onClick={_logout}>ログアウト</button>
                    </form>
                </div>
            </div>
        </nav>
    );

}