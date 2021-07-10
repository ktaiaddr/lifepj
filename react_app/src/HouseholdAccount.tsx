// Hello コンポーネントを定義
import * as React from "react";
import UserHeader from "./user_header";
import {useEffect} from "react";

export default ()=>{

    useEffect(()=>{
        document.title = "家計簿管理"
    },[])
    return (
        <>
            <div>
                <UserHeader />
            </div>
            <div>
                家計簿のページ
            </div>
        </>
    );
}
