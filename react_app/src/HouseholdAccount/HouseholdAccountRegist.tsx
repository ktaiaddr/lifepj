// Hello コンポーネントを定義
import * as React from "react";
import UserHeader from "../user_header";
import {useEffect, useState} from "react";
import axios from "axios";


export default (props: any)=>{


    const [transactionTypeDefinitionsList,setTransactionTypeDefinitionsList] = useState<{typeValue:number,typeLabel:string}[]>([])
    const [accountList,setAccountList] = useState<{accountId:number,accountTypeLabel:string,accountTypeValue:number,name:string}[]>([])
    const [withdrawAccountList,setWithdrawAccountList] = useState<{accountId:number,accountTypeLabel:string,accountTypeValue:number,name:string}[]>([])
    const [increaseAccountList,setIncreaseAccountList] = useState<{accountId:number,accountTypeLabel:string,accountTypeValue:number,name:string}[]>([])

    const [date,setDate] = useState<string>("")
    const [transactionTypeValue,setTransactionTypeValue] = useState<string>("")
    const [amount,setAmount] = useState<number|string>("")
    const [contents,setContents] = useState<string>("")
    const [reduceAccountId,setReduceAccountId] = useState<string>("")
    const [increaseAccountId,setIncreaseAccountId] = useState<string>("")



    function _setTransactionType(e:React.ChangeEvent<any>){

        setTransactionTypeValue(e.target.value)

        setWithdrawAccountList(accountList.filter((account)=> {

            if(e.target.value == 1 || e.target.value == 4 || e.target.value == 6)
                return account.accountTypeValue == 1

            if(e.target.value == 3 )
                return account.accountTypeValue == 2

            return false

        }))

        setIncreaseAccountList(accountList.filter((account)=> {

            if(e.target.value == 1 || e.target.value == 5)
                return account.accountTypeValue == 1

            if(e.target.value == 2 || e.target.value == 6)
                return account.accountTypeValue == 2

            return false

        }))
    }

    function _setReduceAccountId(e:React.ChangeEvent<any>){
        setReduceAccountId(e.target.value)
        console.log(e.target.value);
    }
    function _setIncreaseAccountId(e:React.ChangeEvent<any>){
        setIncreaseAccountId(e.target.value)
        console.log(e.target.value);
    }

    function _setAmount(e:React.ChangeEvent<any>){
        setAmount(e.target.value);
    }
    function _setContents(e:React.ChangeEvent<any>){
        setContents(e.target.value);
    }
    function _setDate(e:React.ChangeEvent<any>){
        setDate(e.target.value);
    }

    async function regist(){
        // console.log(date)
        // console.log(transactionType)
        // console.log(amount)
        // console.log(contents)
        // console.log(withdrawAccountId)
        // console.log(increaseAccountId)
        if(date.length == 0){
            alert('日付を入力してください')
            return
        }
        if(transactionTypeValue == "0"){
            alert('取引種別を選択してください')
            return
        }
        if((typeof amount == 'string' && amount.length == 0 ) || (typeof amount == 'number' && amount <= 0)){
            alert('金額を入力してください')
            return
        }
        if(contents.length == 0){
            alert('取引内容を入力してください')
            return
        }
        console.log(transactionTypeValue)
        console.log(reduceAccountId)
        console.log(increaseAccountId)

        switch(transactionTypeValue){
            case "1":
            case "6":
                if(reduceAccountId === ""){
                    alert('出金元を選択してください')
                    return;
                } else if (increaseAccountId === ""){
                    alert('入金先を選択してください')
                    return;
                }
                break;
            case "2":
            case "5":
                if ( increaseAccountId == ""){
                    alert('入金先を選択してください')
                    return;
                }
                break;
            case "3":
            case "4":
                if (reduceAccountId == ""){
                    alert('出金元を選択してください')
                    return;
                }
                break;
        }

        if(! confirm("データを登録しますか？"))
            return

        const instance = axios.create({withCredentials: true})

        interface resType {
            data: { result:string ,id?:number}
        }

        const regsitparam = {amount,date, transactionTypeValue,reduceAccountId,increaseAccountId,contents,_method:'POST'}

        const loginResult : resType = await instance.post('http://'+process.env.API_ENDPOINT+'/api/household_account/regist'
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
        document.title = "家計簿管理"
    },[])

    const f = async ()=>{
        const url = 'http://'+process.env.API_ENDPOINT+'/api/household_account/regist'
        const instance = axios.create({ withCredentials: true })

        return await instance.get(url,{params:{},}).catch(e=> {
            return {data:{result:'fail'}}
        })
    };

    async function pageRequest(){
        const f2 = async()=>{
            const result = await f();
            console.log(result.data.registerPageComponents)
            if(result){
                setTransactionTypeDefinitionsList(result.data.registerPageComponents.transactionTypeDefinitions)
                setAccountList(result.data.registerPageComponents.accounts)
            }
        }
        await f2();
        // setButtonDisabled(true)
    }
    useEffect(()=>{
        pageRequest()
    },[])


    return (
        <>
            <div>
                <UserHeader />
            </div>
            <div className="row justify-content-md-center">
                <div className="col-sm-10 col-md-5">
                    <form>
                        <div className="mb-3">

                            <input type="date" className="form-control" name="date" placeholder={'日付'} value={date} onInput={_setDate} />
                            <select className="form-control"  name="refueling_amount" onChange={_setTransactionType}>
                                <option value="0">※取引種別を選択してください</option>
                                {transactionTypeDefinitionsList.map(transactionTypeDefinition=>(
                                    <option value={transactionTypeDefinition.typeValue}
                                            key={transactionTypeDefinition.typeValue}>
                                        {transactionTypeDefinition.typeLabel}
                                    </option>
                                ))}
                            </select>
                            <input type="number" className="form-control" min={1} placeholder={'金額'} name="amount" value={amount} onInput={_setAmount} />
                            <input type="text" className="form-control" placeholder={'取引内容'} name="contents" value={contents} onInput={_setContents}/>

                            {withdrawAccountList.length >0?"出金元:":""}
                            {withdrawAccountList.map((withdrawAccount)=>{
                                return (<div key={withdrawAccount.accountId}>
                                    <label>
                                        <input onClick={_setReduceAccountId} type="radio" name="withdraw_account" value={withdrawAccount.accountId}/>
                                        {withdrawAccount.name}
                                    </label>
                                </div>)
                            })}

                            {increaseAccountList.length >0?"入金先:":""}
                            {increaseAccountList.map((increaseAccount)=>{
                                return (<div key={increaseAccount.accountId}>
                                    <label>
                                        <input onClick={_setIncreaseAccountId} type="radio" name="increase_account" value={increaseAccount.accountId}/>
                                        {increaseAccount.name}
                                    </label>
                                </div>)
                            })}

                            <button type="button" className="btn btn-outline-success" onClick={regist}>登録する</button>

                        </div>
                    </form>
                </div>
            </div>
        </>
    );
}
