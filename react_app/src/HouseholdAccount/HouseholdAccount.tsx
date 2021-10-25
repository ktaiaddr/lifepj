// Hello コンポーネントを定義
import * as React from "react";
import UserHeader from "../user_header";
import {useEffect,useState} from "react";
import axios, {AxiosResponse} from "axios";
import HouseholdAccountSearchCondition from "./HouseholdAccountSearchCondition"

interface AccountBalance{
    accountId:number
    balance:number
    name:string
    increase_decrease_type:number
}

interface TransactionView{
    transactionId:number
    date:string
    amount:number
    contents:string
    typeLabel:string
    balances:AccountBalance[]
}

interface balanceAggregateViewModel{
    account_id: number
    account_name: string
    balance: number
    aggregate_balance?: number
    latest_closing_balance?: number
    closing_next_month_day_of_first?: string
}

const rowColor = (index: number)=> ( index%2==1 ? {background:"lightblue"} : {} )

export default ()=>{
    const [accountBalanceDataList,setAccountBalanceDataList] = useState<TransactionView[]>([])

    const [transactionTypeDefinitionsList,setTransactionTypeDefinitionsList] = useState<{typeValue:number,typeLabel:string}[]>([])
    const [accountList,setAccountList] = useState<{accountId:number,accountTypeLabel:string,accountTypeValue:number,name:string}[]>([])
    const [transactionTypeSearchValue,setTransactionTypeSearchValue] = useState<number|null>(0)
    const [accountIdSearchValue,setAccountIdSearchValue] = useState<number|null>(0)
    const [viewMonth,setViewMonth] = useState<string>((new Date()).getFullYear()+'-'+((new Date()).getMonth()+1))
    const [balanceAggregateViewModel,setBalanceAggregateViewModel] = useState<balanceAggregateViewModel[]>([])
    const [transactionSearchRange,setTransactionSearchRange] = useState<{maxMonth:string,minMonth:string}>({maxMonth:'2021-10',minMonth:'2021-10'})


    const [enableSearch,setEnableSearch] = useState<boolean>(true)

    const _setTransactionTypeSearchValue = (e:React.ChangeEvent<HTMLSelectElement>)=>{
        setTransactionTypeSearchValue(Number(e.target.value))
        setEnableSearch(true)
    }

    const _setAccountIdSearchValue = (e:React.ChangeEvent<HTMLSelectElement>)=>{
        setAccountIdSearchValue(Number(e.target.value))
        setEnableSearch(true)
    }

    const _setViewMonth = (e:React.ChangeEvent<HTMLSelectElement>)=>{
        setViewMonth(e.target.value)
        setEnableSearch(true)
    }

    const f = async ()=>{
        // setReaded(false)
        const url = 'http://'+process.env.API_ENDPOINT+'/api/household_account'
        const instance = axios.create({ withCredentials: true })
        const result = await instance.get(url,{
            params:{
                transactionTypeVal : transactionTypeSearchValue,
                accountId          : accountIdSearchValue,
                viewMonth          : viewMonth,
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
                setAccountBalanceDataList(result.data.transactionViewModels);
                setTransactionTypeDefinitionsList(result.data.registerPageComponents.transactionTypeDefinitions)
                setAccountList(result.data.registerPageComponents.accounts)
                setBalanceAggregateViewModel(result.data.balanceAggregateViewModel)
                setTransactionSearchRange(result.data.transactionSearchRange)

            }
        }
        await f2();
        // setButtonDisabled(true)
    }
    useEffect(()=>{
        document.title = "家計簿管理"

        if(enableSearch){
            searchRequest()
            setEnableSearch(false)
        }

    },[enableSearch])

    return (
        <>
            <div>
                <UserHeader />
            </div>
            <div>
                家計簿のページ
                <div>
                    <HouseholdAccountSearchCondition
                        transactionTypeDefinitionsList={transactionTypeDefinitionsList}
                        accountList={accountList}
                        _setTransactionTypeSearchValue={_setTransactionTypeSearchValue}
                        _setAccountIdSearchValue={_setAccountIdSearchValue}
                        transactionTypeSearchValue={transactionTypeSearchValue}
                        accountIdSearchValue={accountIdSearchValue}
                        viewMonth={viewMonth}
                        _setViewMonth={_setViewMonth}
                        balanceAggregateViewModel={balanceAggregateViewModel}
                        transactionSearchRange={transactionSearchRange}
                    />
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
                        {accountBalanceDataList && accountBalanceDataList.length > 0 && accountBalanceDataList.map((row,index)=> {
                            const rowlength = row.balances.length;

                            const row_clone = [...row.balances]
                            const first = row_clone.shift();
                            const color = first? (first.increase_decrease_type===2?"blue":"red"):"";

                            return (< >
                                    <tr style={rowColor(index)} key={index} >
                                        <td rowSpan={rowlength}>{row.date}</td>
                                        <td rowSpan={rowlength}>{row.typeLabel}</td>
                                        <td rowSpan={rowlength} style={{textAlign:"right"}}>{(row.amount).toLocaleString()}円</td>
                                        <td rowSpan={rowlength}>{row.contents}</td>
                                        <td>{first?first.name:""}</td>
                                        <td style={{color,textAlign:"right"}}>{first?(first.balance).toLocaleString()+"円":""}</td>
                                    </tr>
                                    {row_clone.length ?

                                        row_clone.map((row2)=>{
                                            const color = row2? (row2.increase_decrease_type===2?"blue":"red"):"";
                                            return (
                                                <tr style={rowColor(index)} key={1000+index} >
                                                    <td>{row2?row2.name:""}</td>
                                                    <td style={{color,textAlign:"right"}}>{row2?(row2.balance).toLocaleString()+"円":""}</td>
                                                </tr>
                                            )}) :
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
