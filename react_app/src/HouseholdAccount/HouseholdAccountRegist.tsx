// Hello コンポーネントを定義
import * as React from "react";
import UserHeader from "../user_header";
import {useEffect, useState} from "react";
import axios from "axios";


export default (props: any)=>{


    const [transactionTypeDefinitionsList,setTransactionTypeDefinitionsList] = useState<{typeValue:number,typeLabel:string}[]>([])

    useEffect(()=>{
        document.title = "家計簿管理"
    },[])

    const f = async ()=>{
        const url = 'http://'+process.env.API_ENDPOINT+'/api/household_account'
        const instance = axios.create({ withCredentials: true })

        const result = await instance.get(url,{params:{},}).catch(e=> {
            return {data:{result:'fail'}}
        })
        return result;
    };

    async function pageRequest(){
        const f2 = async()=>{
            const result = await f();
            console.log(result.data.registerPageComponents.transactionTypeDefinitions)
            if(result){
                setTransactionTypeDefinitionsList(result.data.registerPageComponents.transactionTypeDefinitions)
                console.log(transactionTypeDefinitionsList)

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

                            <input type="date" className="form-control" name="date" placeholder={'日付'} value={''} onInput={()=>{}} />
                            <select className="form-control"  name="refueling_amount">
                                <option value="">--</option>
                                {transactionTypeDefinitionsList.map(transactionTypeDefinition=>{
                                    return (
                                        <option value={transactionTypeDefinition.typeValue}>{transactionTypeDefinition.typeLabel}</option>
                                    )
                                })}
                            </select>
                            <input type="text" className="form-control" min={1} placeholder={'金額'} name="refueling_amount" value={''} onInput={()=>{}} />
                            <input type="text" className="form-control" placeholder={'取引内容'} name="refueling_distance" value={''} onInput={()=>{}}/>
                            <button type="button" className="btn btn-outline-success" onClick={()=>{}}>登録する</button>

                        </div>
                    </form>
                </div>
            </div>
        </>
    );
}
