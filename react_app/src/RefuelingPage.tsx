// Hello コンポーネントを定義
import * as React from "react";
import axios from "axios";
import UserHeader from "./user_header";
import RefuelingSubHeader from "./RefuelingSubHeader";
import {cloneElement, useEffect, useState} from "react";
import RefuelingSearchCondition from "./RefuelingSearchCondition";

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
const pageNumSelectable = [10,20,50,100]

export default (props:any)=>{
    const [refuelings_data_list,setRefuelings_data_list]:[Array<refuelings>,any] = useState([])
    const [refuelingsCount,setRefuelingsCount]:any = useState(0)
    const [readed,setReaded]:any = useState(false)
    const [pageLimitSelect,setPageLimitSelect]:[number,any] = useState(10)
    const [pagingNumber,setPagingNumber]:[number,any] = useState(1)
    const [pagingSelectable,setPagingSelectable]:[number[],any] = useState([1])
    const [searchCondition, setSearchCondition] :any = useState({
        date_start:'',
        date_end:'',
        distance_lower:'',
        distance_high:'',
        amount_lower:'',
        amount_high:'',
        gas_station:'',
        memo:'',
    })
    const [buttonDisabled,setButtonDisabled]:any = useState(true)
    const [resetSubmit,setResetSubmit]:any = useState(false)
    const [enableSarch,setEnableSarch]:any = useState(false)

    function _setSearchCondition(e:React.ChangeEvent<HTMLInputElement>){

        console.log(e.target.name)
        console.log(e.target.value)
        const tmp = {...searchCondition}
        console.log(tmp)
        tmp[e.target.name] = e.target.value
        // setSearchCondition(null)
        setSearchCondition(tmp)
        console.log(searchCondition)
        setButtonDisabled(false)
    }
    function searchResult()
    {
        setButtonDisabled(true)
        setPagingNumber(1)
        setEnableSarch(true)
    }
    function resetSearch()
    {
        setSearchCondition({
            date_start:'',
            date_end:'',
            distance_lower:'',
            distance_high:'',
            amount_lower:'',
            amount_high:'',
            gas_station:'',
            memo:'',
        })
        setPagingNumber(1)
        setEnableSarch(true)
    }
    useEffect(()=>{
        if(enableSarch){
            resetSearchRequest()
            setEnableSarch(false)
        }
    },[enableSarch])

    function resetSearchRequest(){
        const f2 = async()=>{
            const result = await f();

            if(result ){
                setRefuelings_data_list(result.data.list)
                setRefuelingsCount(result.data.count)
                setPagingSelectable([...Array(Math.ceil(result.data.count/pageLimitSelect)).keys()].map(i => ++i))
                setReaded(true)
            }
        }
        f2();

        setButtonDisabled(true)
    }


    function changePageNumSelect(e:React.ChangeEvent<HTMLSelectElement>){
        setPageLimitSelect(e.target.value)
        setPagingNumber(1)
    }
    function changPagingNumber(e:React.ChangeEvent<HTMLSelectElement>){
        setPagingNumber(e.target.value)
    }

    function pagingPrevious(){
        if(pagingNumber <= 1 ){
            console.log('最初のページです')
            return
        }
        setPagingNumber(pagingNumber-1)
    }
    function pagingNext(){
        if(pagingNumber >=Math.max(...pagingSelectable) ){
            console.log('最後のページです')
            return
        }
        setPagingNumber(pagingNumber+1)
    }
    useEffect(()=>{
        document.title = "給油データ一覧" + "["+pagingNumber+"]"
    }, [pagingNumber]);

    const f = async ()=>{
        console.log("api request")
        const url = 'http://localhost:9000/api/refuelings'
        const instance = axios.create({ withCredentials: true })
        const result = await instance.get(url,{
            params:{
                limit:pageLimitSelect,
                page:pagingNumber,
                date_start:searchCondition.date_start||null,
                date_end:searchCondition.date_end||null,
                amount_low:searchCondition.amount_lower?parseInt(searchCondition.amount_lower):null,
                amount_high:searchCondition.amount_high?parseInt(searchCondition.amount_high):null,
                distance_low:searchCondition.distance_lower?parseInt(searchCondition.distance_lower):null,
                distance_high:searchCondition.distance_high?parseInt(searchCondition.distance_high):null,
                gas_station:searchCondition.gas_station || null,
                memo:searchCondition.memo || null,
            },
        }).catch(e=> {
            return {data:{result:'fail'}}
        })
        return result;
    };
    useEffect(()=>{
        let cleanedUp = false;
        const f2 = async()=>{
            const result = await f();

            if(result && !cleanedUp){
                setRefuelings_data_list(result.data.list)
                setRefuelingsCount(result.data.count)

                setPagingSelectable([...Array(Math.ceil(result.data.count/pageLimitSelect)).keys()].map(i => ++i))
                setReaded(true)
            }
        }
        f2();
        const cleanup = () => { cleanedUp = true; };
        return cleanup;
        console.log(33333)

    }, [pageLimitSelect,pagingNumber]);

    return (
        <>
            {readed ===true?
                (<>
                    <div><UserHeader /></div>
                    {/*<div><RefuelingSubHeader /></div>*/}
                </>):
                <></>
            }
            {readed ===true?
                <div>

                    <div className="row">
                        <div className="col-5 col-sm-2">
                            <div className="input-group">
                                <select className="form-select" defaultValue={pageLimitSelect} onChange={changePageNumSelect}>
                                    {pageNumSelectable.map(num=>
                                        <option value={num} key={num}>{num}件</option>
                                    )}
                                </select>
                            </div>
                        </div>
                        <div className="col-7 col-sm-10">
                            <nav aria-label="Page navigation example">
                                <ul className="pagination justify-content-end">
                                    <li className="page-item">
                                        <a className="page-link" href="#" onClick={pagingPrevious}>＜</a>
                                    </li>
                                    <li className="page-item">
                                        <select className="form-select" value={pagingNumber} onChange={changPagingNumber}>
                                            {pagingSelectable.map(num=>
                                                <option value={num} key={num}>{num}</option>
                                            )}
                                        </select>
                                    </li>
                                    <li className="page-item">
                                        <a className="page-link" href="#" onClick={pagingNext}>＞</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>


                    <RefuelingSearchCondition
                        searchCondition={searchCondition}
                        _setSearchCondition={_setSearchCondition}
                        searchResult={searchResult}
                        resetSearch={resetSearch}
                        buttonDisabled={buttonDisabled} />

                    <div>{refuelingsCount}件</div>
                    <table className="table">
                        <thead>
                        <tr>
                            <th>日付</th>
                            <th>距離</th>
                            <th>数量</th>
                            <th>燃費</th>
                            <th>ガスステーション</th>
                            <th>メモ</th>
                        </tr>
                        </thead><tbody>
                    {refuelings_data_list.map((value,index)=>(
                        <tr key={value.refueling_id} style={index%2==0?{background:"lightblue"}:{}}>
                            <td>{value.date}</td>
                            <td>{value.refueling_distance}</td>
                            <td>{value.refueling_amount}</td>
                            <td>{value.fuel_economy}</td>
                            <td>{value.gas_station}</td>
                            <td>{value.memo}</td>
                        </tr>
                   ))}
                    </tbody></table>
                </div>
                :
                (<></>)
            }
        </>
    )
}
