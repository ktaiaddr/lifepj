import * as React from "react";
import UserHeader from "./user_header";
import RefuelingSubHeader from "./RefuelingSubHeader";
import {useState} from "react";
import axios from "axios";

export default ()=>{
    const [date,setDate] = useState('');
    const [refueling_amount,setRefuelingAmount] = useState('');
    const [refueling_distance,setRefuelingDistance] = useState('');
    const [gas_station,setGasStation] = useState('');
    const [memo,setMemo] = useState('');

    function inputHandle(e:React.ChangeEvent<HTMLInputElement>){
        if(e.target.name == 'date') {setDate(e.target.value);return;}
        if(e.target.name == 'refueling_amount') {setRefuelingAmount(e.target.value);return;}
        if(e.target.name == 'refueling_distance') {setRefuelingDistance(e.target.value);return;}
        if(e.target.name == 'gas_station') {setGasStation(e.target.value);return;}
        if(e.target.name == 'memo') {setMemo(e.target.value);return;}
    }

    async function submit(){

        const instance = axios.create({withCredentials: true})

        interface resType {
            data: { result:string }
        }

        const regsitparam = {date,refueling_amount,refueling_distance,gas_station,memo}

        const loginResult : resType = await instance.post('http://localhost:9000/api/refuelings/regist'
            , regsitparam
            ,{ withCredentials: true }
        ).catch( e => {
            return {data:{result:'ng'}};
        });

        if( loginResult )
            if( loginResult.data.result =='ok' ){
                props.setLogined(true)
                setLogin( true);
            }
    }

    return (
        <>
            <div><UserHeader /></div>
            <div><RefuelingSubHeader /></div>

            <div><input type="date" placeholder={'日付'} name="date" value={date} onInput={inputHandle}/></div>
            <div><input type="text" min={1} placeholder={'給油量（リットル）'} name="refueling_amount" value={refueling_amount} onInput={inputHandle} /></div>
            <div><input type="text" placeholder={'走行距離'} name="refueling_distance" value={refueling_distance} onInput={inputHandle}/></div>
            <div><input type="text" placeholder={'給油ステーション'} name="gas_station" value={gas_station} onInput={inputHandle}/></div>
            <div><input type="text" placeholder={'メモ'} name="memo" value={memo} onInput={inputHandle}/></div>
            <div><button onClick={submit}>登録する</button></div>

        </>
    );
}

// 'date'                => '2021-07-05',
//     'refueling_amount'    => 1,
//     'refueling_distance'  => 500,
//     'gas_station'         => "g",
//     'memo'                => "m",