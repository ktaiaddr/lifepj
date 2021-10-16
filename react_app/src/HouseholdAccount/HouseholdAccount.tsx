// Hello コンポーネントを定義
import * as React from "react";
import UserHeader from "../user_header";
import {useEffect,useState} from "react";
import axios, {AxiosResponse} from "axios";

interface AccountBalance{
    accountId:number
    balance:number
    name:string
}

interface TransactionView{
    transactionId:number
    date:string
    amount:number
    contents:string
    typeLabel:string
    balances:AccountBalance[]
}

const rowColor = (index: number)=> ( index%2==1 ? {background:"lightblue"} : {} )

export default ()=>{
    const [accountBalanceDataList,setAccountBalanceDataList] = useState<TransactionView[]>([])

    useEffect(()=>{
        document.title = "家計簿管理"
    },[])

    const f = async ()=>{
        // setReaded(false)
        console.log("api request")
        const url = 'http://'+process.env.API_ENDPOINT+'/api/household_account'
        const instance = axios.create({ withCredentials: true })
        const result = await instance.get(url,{
            params:{
                // limit         : pageLimitSelect,
                // page          : pagingNumber,
                // date_start    : searchCondition.date_start     || null,
                // date_end      : searchCondition.date_end       || null,
                // amount_low    : searchCondition.amount_lower   ?  Number(searchCondition.amount_lower  ) : null,
                // amount_high   : searchCondition.amount_high    ?  Number(searchCondition.amount_high   ) : null,
                // distance_low  : searchCondition.distance_lower ?  Number(searchCondition.distance_lower) : null,
                // distance_high : searchCondition.distance_high  ?  Number(searchCondition.distance_high ) : null,
                // gas_station   : searchCondition.gas_station    || null,
                // memo          : searchCondition.memo           || null,
                // sort_key      : sortKey || null,
                // sort_order    : sortOrder || null,
            },
        }).catch(e=> {
            return {data:{result:'fail'}}
        })
        console.log(result)
        return result;
    };

    async function searchRequest(){
        const f2 = async()=>{
            const result = await f();
            if(result){
                console.log(result)
                setAccountBalanceDataList(result.data.data);
            }
        }
        await f2();
        // setButtonDisabled(true)
    }
    useEffect(()=>{
        searchRequest()
    },[])
    return (
        <>
            <div>
                <UserHeader />
            </div>
            <div>
                家計簿のページ
                <div>
                    <table className="table" style={{verticalAlign:"middle"}}>
                        <thead>
                        <tr>
                            <th>日付</th>
                            <th>取引種別</th>
                            <th>金額</th>
                            <th>取引内容</th>
                            <th>口座</th>
                            <th>残高</th>
                        </tr>
                        </thead>
                        <tbody>
                        {accountBalanceDataList.map((row,index)=> {
                                const rowlength = row.balances.length;
                                const first = row.balances.shift()
                                return (<>
                                    <tr style={rowColor(index)} >
                                        <td rowSpan={rowlength}>{row.date}</td>
                                        <td rowSpan={rowlength}>{row.typeLabel}</td>
                                        <td rowSpan={rowlength}>{row.amount}円</td>
                                        <td rowSpan={rowlength}>{row.contents}</td>
                                        <td>{first?first.name:""}</td>
                                        <td>{first?first.balance+"円":""}</td>
                                    </tr>
                                    {row.balances.length ?
                                        row.balances.map((row2)=>(
                                            <tr style={rowColor(index)}>
                                                <td>{row2?row2.name:""}</td>
                                                <td>{row2?row2.balance+"円":""}</td>
                                            </tr>
                                        )) :
                                        <></>
                                    }
                                </>)
                            }
                        )}
                        </tbody>

                    </table>
                </div>
            </div>
        </>
    );
}
