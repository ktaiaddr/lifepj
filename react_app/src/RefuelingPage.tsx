// Hello コンポーネントを定義
import * as React from "react";
import axios from "axios";
import UserHeader from "./user_header";
import RefuelingSubHeader from "./RefuelingSubHeader";
import {useEffect, useState} from "react";

// Hello コンポーネントの属性（プロパティ）を定義
interface HelloProps {}

interface refuelings{
    refueling_id :number
    user_id :number
    date :string
    refueling_amount :number
    refueling_distance:number
    gas_station:string
    memo:string
}

export default (props:any)=>{
    const [refuelings_data_list,setRefuelings_data_list]:[Array<refuelings>,any] = useState([])

    useEffect(()=>{
        const url = 'http://localhost:9000/api/refuelings'
        let cleanedUp = false;

        const f = async ()=>{
            const instance = axios.create({ withCredentials: true })
            const result = await instance.get(url).catch(e=> {
                return {data:{result:'fail'}}
            })
            if(result && !cleanedUp)
                setRefuelings_data_list(result.data)
        };
        f();

        const cleanup = () => { cleanedUp = true; };
        return cleanup;
    }, []);

    return (
        <>
            <div><UserHeader /></div>
            <div><RefuelingSubHeader /></div>
            {
                refuelings_data_list
                    ?
                    refuelings_data_list.map((value)=>
                        (<div key={value.refueling_id}>
                            <div>{value.user_id}</div>
                            <div>{value.date}</div>
                            <div>{value.refueling_amount}</div>
                            <div>{value.refueling_distance}</div>
                            <div>{value.gas_station}</div>
                            <div>{value.memo}</div>
                        </div>)
                    )
                    :(<></>)
            }
        </>
    )
}
