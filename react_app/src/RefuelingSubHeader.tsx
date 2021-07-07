import * as React from "react";
import {Link} from "react-router-dom";

export default ()=>{
    return (
        <>
            [<Link to="/refueling">一覧</Link>]
            [<Link to="/refueling/regist">登録</Link>]
        </>
    );
}