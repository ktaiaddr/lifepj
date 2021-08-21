import * as React from "react";
import UserHeader from "../user_header";
import RefuelingSubHeader from "./RefuelingSubHeader";
import {useEffect, useState} from "react";
import axios from "axios";
import {Redirect} from "react-router-dom";
import { useHistory } from 'react-router'

export default (props: any)=>{
    const history= useHistory()

    const [date,setDate] = useState('');
    const [refueling_amount,setRefuelingAmount] = useState('');
    const [refueling_distance,setRefuelingDistance] = useState('');
    const [gas_station,setGasStation] = useState('');
    const [memo,setMemo] = useState('');

    const [done,setDone] = useState(false);
    const [doneRefuelingId,setDoneRefuelingId]  = useState<number|null>(null);


    const {params} = props.match

console.log(params)

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
            data: { result:string ,id?:number}
        }

        const regsitparam = {refueling_id:params.refueling_id||null,date,refueling_amount,refueling_distance,gas_station,memo}

        const loginResult : resType = await instance.post('http://'+process.env.API_ENDPOINT+'/api/refuelings/regist'
            , regsitparam
            ,{ withCredentials: true }
        ).catch( e => {
            return {data:{result:'ng'}};
        });

        setDoneRefuelingId(loginResult.data.id as number)
        setDone(true)

        if( loginResult )
            if( loginResult.data.result =='ok' ){
                // props.setLogined(true)
                // setLogin( true);
            }
    }

    const f = async(refueling_id: number)=>{
        const instance = axios.create({withCredentials: true})
        const refueling : any = await instance.get('http://'+process.env.API_ENDPOINT+'/api/refuelings/regist/'+refueling_id
        ).catch( e => {
            return {data:{result:'ng'}};
        });
        setDate(refueling.data[0].date)
        setRefuelingAmount(refueling.data[0].refueling_amount)
        setRefuelingDistance(refueling.data[0].refueling_distance)
        setGasStation(refueling.data[0].gas_station)
        setMemo(refueling.data[0].memo)
    }

    useEffect(()=>{
        document.title = "給油データ登録"

        if(params.refueling_id != undefined)
            f(params.refueling_id)

    },[])


    if(done){
        location.href = "/refueling/regist/"+doneRefuelingId
    }

    return (
        <>
            <div><UserHeader /></div>

            <div className="row justify-content-md-center">
                <div className="col-sm-10 col-md-5">
                    <form>
                        <div className="mb-3">

                            <input type="date" className="form-control" name="date" placeholder={'日付'} value={date} onInput={inputHandle} />
                            <input type="text" className="form-control" min={1} placeholder={'給油量（リットル）'} name="refueling_amount" value={refueling_amount} onInput={inputHandle} />
                            <input type="text" className="form-control" placeholder={'走行距離'} name="refueling_distance" value={refueling_distance} onInput={inputHandle}/>
                            <input type="text" className="form-control" placeholder={'給油ステーション'} name="gas_station" value={gas_station} onInput={inputHandle}/>
                            <input type="text" className="form-control" placeholder={'メモ'} name="memo" value={memo} onInput={inputHandle}/>
                            <button type="button" className="btn btn-outline-success" onClick={submit}>登録する</button>
                        </div>
                    </form>
                </div>
            </div>

        </>
    );
}