import * as React from "react";
import axios from "axios";
import {useEffect, useState} from "react";
import {useLocation} from "react-router-dom";
import {RouteComponentProps} from "react-router";

// type Props = RouteComponentProps<{
//     match: {
//         token: string
//     }
// } >;

export default (props: any)=>{
    const location = useLocation();
    console.log(location.search)
    const {params} = props.match
    const id = params.id
    const hash = params.hash

    async function verify(){
        // setForgotStatus(false)
        const instance = axios.create({withCredentials: true})
        interface resType {data: boolean}
        const resetResult : resType = await instance.get(
            'http://localhost:9000/api/myverifyemail'+'/'+id+'/'+hash+location.search
        ).catch( e => {
            return {data:false};
        });

        if( resetResult ){
            // if( forgotResult.data == true ) setForgotStatus(true)
        }

    }

    useEffect(()=>{
        const func = async()=>{
            await verify()
        }
        func()
    },[])
    return (<><div>{id}</div><div>{hash}</div></>)
}
