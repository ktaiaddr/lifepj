import * as React from "react";
import UserHeader from "../user_header";
import RefuelingSubHeader from "./RefuelingSubHeader";
import {useEffect, useState} from "react";
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

        const loginResult : resType = await instance.post('http://'+process.env.API_ENDPOINT+'/api/refuelings/regist'
            , regsitparam
            ,{ withCredentials: true }
        ).catch( e => {
            return {data:{result:'ng'}};
        });

        if( loginResult )
            if( loginResult.data.result =='ok' ){
                // props.setLogined(true)
                // setLogin( true);
            }
    }

    useEffect(()=>{
        document.title = "給油データ登録"
    },[])

    return (
        <>
            <div><UserHeader /></div>

            <div className="row justify-content-md-center">
                <div className="col-sm-10 col-md-5">
                    <form>
                        <div className="mb-3">

                        <input type="date" className="form-control" name="date" placeholder={'日付'} onInput={inputHandle} />
                    <input type="text" className="form-control" min={1} placeholder={'給油量（リットル）'} name="refueling_amount" value={refueling_amount} onInput={inputHandle} />
                    <input type="text" className="form-control" placeholder={'走行距離'} name="refueling_distance" value={refueling_distance} onInput={inputHandle}/>
                    <input type="text" className="form-control" placeholder={'給油ステーション'} name="gas_station" value={gas_station} onInput={inputHandle}/>
                    <input type="text" className="form-control" placeholder={'メモ'} name="memo" value={memo} onInput={inputHandle}/>
                    <button className="btn btn-outline-success" onClick={submit}>登録する</button>
                        </div>
                    </form>
                </div>
            </div>

        </>
    );
}

// 'date'                => '2021-07-05',
//     'refueling_amount'    => 1,
//     'refueling_distance'  => 500,
//     'gas_station'         => "g",
//     'memo'                => "m",