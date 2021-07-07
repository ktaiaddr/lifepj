import {Link } from "react-router-dom";
import * as React from "react";

export default ()=>{
    return (
        <>
            <div>ログインしてます</div>
            [<Link to="/refueling">給油データ</Link>]
            [<Link to="/household_account">家計簿</Link>]
        </>
    );

}