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
                result.data.data.map((row:TransactionView)=>{
                    console.log(row.transactionId)
                    console.log(row.date)
                    console.log(row.amount)
                    console.log(row.contents)
                    console.log(row.typeLabel)
                    row.balances.map(row2=>{
                        console.log(row2.accountId)
                        console.log(row2.balance)
                        console.log(row2.name)
                    })
                })
                // setRefuelings_data_list(result.data.list)
                // setRefuelingsCount(result.data.count)
                // setTotalAmount(result.data.total_refueling_amount)
                // setTotalDistance(result.data.total_refueling_distance)
                // setTotalFuelEconomy(result.data.total_fuel_economy)
                // setPagingSelectable([...Array(Math.ceil(result.data.count/pageLimitSelect)).keys()].map(i => ++i))
                // setReaded(true)
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
                    <table className="table" style={{color:"red"}}>
                    {accountBalanceDataList.map(row=>(
                        <tr>
                            <td>{row.date}</td>
                            <td>{row.transactionId}</td>
                            <td>{row.typeLabel}</td>
                            <td>{row.amount}円</td>
                            <td>
                            {row.balances.map(row2=>(
                                <>
                                    口座：{row2.name} / {row2.balance}円 / {row2.accountId}円<br />
                                </>
                            ))}
                            </td>
                        </tr>
                    ))}
                    </table>
                </div>
            </div>
        </>
    );
}
