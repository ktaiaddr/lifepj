// Hello コンポーネントを定義
import * as React from "react";
import axios from "axios";
import UserHeader from "./user_header";
import {BaseSyntheticEvent, cloneElement, SyntheticEvent, useEffect, useState} from "react";
import RefuelingSearchCondition from "./RefuelingSearchCondition";
import RefuelingPageLimitSelect from "./RefuelingPageLimitSelect";
import RefuelingPagenation from "./RefuelingPagenation";
import RefuelingResultTable from "./RefuelingResultTable";
import {sortKeys,sortOrders} from "./sortEnums"
import refuelings from "./interfaceRefuelings";

const initCondition = ()=> {
    return {
        date_start:'',
        date_end:'',
        distance_lower:'',
        distance_high:'',
        amount_lower:'',
        amount_high:'',
        gas_station:'',
        memo:'',
    }
}

export default (props:any)=>{
    const [refuelings_data_list,setRefuelings_data_list]:[Array<refuelings>,any] = useState([])
    const [refuelingsCount,setRefuelingsCount]:any = useState(0)
    const [readed,setReaded]:any = useState(false)
    const [pageLimitSelect,setPageLimitSelect]:[number,any] = useState(10)
    const [pagingNumber,setPagingNumber]:[number,any] = useState(1)
    const [pagingSelectable,setPagingSelectable]:[number[],any] = useState([1])
    const [searchCondition, setSearchCondition] :any = useState(initCondition())
    const [buttonDisabled,setButtonDisabled]:any = useState(true)
    const [resetDone,setResetDone]:any = useState(true)
    const [enableSarch,setEnableSarch]:any = useState(false)
    const [sortKey,setSortKey] = useState<sortKeys>(sortKeys.DATE)
    const [sortOrder,setSortOrder]:any = useState<sortOrders>(sortOrders.DESC)

    function _setSearchCondition(e:React.ChangeEvent<HTMLInputElement>){
        const tmp = {...searchCondition}
        tmp[e.target.name] = e.target.value
        setSearchCondition(tmp)
        setButtonDisabled(false)
        setResetDone(false)
    }
    function searchResult()
    {
        setButtonDisabled(true)
        setPagingNumber(1)
        setEnableSarch(true)
    }
    function resetSearch() {
        setSearchCondition(initCondition())
        setPagingNumber(1)
        setEnableSarch(true)
        setResetDone(true)
    }
    useEffect(()=>{
        if(enableSarch){
            resetSearchRequest()
            setEnableSarch(false)
        }
    },[enableSarch])




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
                sort_key:sortKey || null,
                sort_order:sortOrder || null,
            },
        }).catch(e=> {
            return {data:{result:'fail'}}
        })
        return result;
    };

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

    }, [pageLimitSelect,pagingNumber,sortKey,sortOrder]);

    function changeSort(e:BaseSyntheticEvent)
    {
        const dataName = e.target.getAttribute('data-name')
        if(dataName == sortKey){
            setSortOrder( sortOrder == sortOrders.ASC ?sortOrders.DESC:sortOrders.ASC )
            return
        }
        setSortKey( dataName )
    }

    return (
        <>
            <div><UserHeader /></div>

            {readed ===true?
                <div>

                    <RefuelingSearchCondition searchCondition={searchCondition}
                                              _setSearchCondition={_setSearchCondition}
                                              searchResult={searchResult}
                                              resetSearch={resetSearch}
                                              buttonDisabled={buttonDisabled}
                                              resetDone={resetDone} />

                    <div className="row">

                        <RefuelingPageLimitSelect pageLimitSelect={pageLimitSelect}
                                                  changePageNumSelect={changePageNumSelect} />

                        <RefuelingPagenation pagingPrevious={pagingPrevious}
                                             pagingNumber={pagingNumber}
                                             changPagingNumber={changPagingNumber}
                                             pagingSelectable={pagingSelectable}
                                             pagingNext={pagingNext} />

                    </div>

                    <div>{refuelingsCount}件</div>

                    <RefuelingResultTable  changeSort={changeSort}
                                           sortKey={sortKey}
                                           sortOrder={sortOrder}
                                           sortKeys={sortKeys}
                                           sortOrders={sortOrders}
                                           refuelings_data_list={refuelings_data_list} />
                </div>
                :
                (<></>)
            }
        </>
    )
}
