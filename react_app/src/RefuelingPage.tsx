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
    fuel_economy:number
}

export default (props:any)=>{
    const [refuelings_data_list,setRefuelings_data_list]:[Array<refuelings>,any] = useState([])
    const [readed,setReaded]:any = useState(false)
    const [pageNumSelect,setPageNumSelect]:[number,any] = useState(5)

    const pageNumSelectable = [5,10,20,50,100]
    function changePageNumSelect(e:React.ChangeEvent<HTMLSelectElement>){
        setPageNumSelect(e.target.value)
    }
    useEffect(()=>{
        document.title = "給油データ一覧"
    }, []);

    const f = async ()=>{
        const url = 'http://localhost:9000/api/refuelings'
        const instance = axios.create({ withCredentials: true })
        const result = await instance.get(url,{
            params:{limit:pageNumSelect}
        }).catch(e=> {
            return {data:{result:'fail'}}
        })
        return result;
    };
    useEffect(()=>{
        let cleanedUp = false;
        const f2 = async()=>{
            const result = await f();
            console.log(result)
            if(result && !cleanedUp){
                setRefuelings_data_list(result.data)
                setReaded(true)
            }
        }
        f2();
        const cleanup = () => { cleanedUp = true; };
        return cleanup;
    }, [pageNumSelect]);

    return (
        <>
            {readed ===true?
                (<>
                    <div><UserHeader /></div>
                    <div><RefuelingSubHeader /></div>
                </>):
                <></>
            }
            {readed ===true?
                <div>
                    <span>表示数</span>
                    <div className="select">
                        <select defaultValue={pageNumSelect} onChange={changePageNumSelect}>
                            {pageNumSelectable.map(num=>
                                <option value={num} key={num}>{num}</option>
                            )}
                        </select>
                    </div>
                <table className="table">
                    <thead>
                    <tr>
                        <th>UserId</th>
                        <th>日付</th>
                        <th>数量</th>
                        <th>距離</th>
                        <th>ガスステーション</th>
                        <th>メモ</th>
                        <th>燃費</th>
                    </tr>
                    </thead><tbody>
                {refuelings_data_list.map((value)=>(
                    <tr key={value.refueling_id}>
                        <td>{value.user_id}</td>
                        <td>{value.date}</td>
                        <td>{value.refueling_amount}</td>
                        <td>{value.refueling_distance}</td>
                        <td>{value.gas_station}</td>
                        <td>{value.memo}</td>
                        <td>{value.fuel_economy}</td>
                    </tr>
                   ))}
                </tbody></table></div>:
                (<></>)
            }
        </>
    )
}
