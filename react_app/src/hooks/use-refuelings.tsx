import {BaseSyntheticEvent, useEffect, useState} from "react";
import {sortKeys, sortOrders} from "../Refueling/sortEnums";
import refuelings from "../Refueling/interfaceRefuelings";
import * as React from "react";
import axios from "axios";

const initCondition = ()=> {
    return {
        date_start    : '',
        date_end      : '',
        distance_lower: '',
        distance_high : '',
        amount_lower  : '',
        amount_high   : '',
        gas_station   : '',
        memo          : '',
    }
}
const initCondition_data = initCondition()
type t_searchCondition = typeof initCondition_data
interface MySearchInputElement extends HTMLInputElement{
    name: keyof t_searchCondition
}

type UseSortType = {
    refuelings_data_list: refuelings[],//給油データ一覧データ
    refuelingsCount     : number,      //給油データ件数
    readed              : boolean,     //読込完了
    pageLimitSelect     : number,      //1ページ表示件数
    pagingNumber        : number,      //ページング位置（ページ番号）
    pagingSelectable    : number[],    //ページング配列（1...n）
    searchCondition     : t_searchCondition,//検索条件
    buttonDisabled      : boolean,//検索ボタン無効状態
    resetDone           : boolean,//リセット完了状態
    sortKey             : sortKeys,//並びキー
    sortOrder           : sortOrders,//並び昇順降順
    changeSort          : (e: BaseSyntheticEvent)=>void,//並び変更関数
    _setSearchCondition : (e: React.ChangeEvent<MySearchInputElement>)=>void,
    changePageNumSelect : (e: React.ChangeEvent<HTMLSelectElement>)=>void,
    changPagingNumber   : (e: React.ChangeEvent<HTMLSelectElement>)=>void,
    pagingPrevious      : ()=>void,
    pagingNext          : ()=>void,
    searchResult        : ()=>void,
    resetSearch         : ()=>void,
}

const useRefuelings = (): UseSortType => {
    const [refuelings_data_list,setRefuelings_data_list] = useState<refuelings[]>([])        //給油データのリスト
    const [refuelingsCount     ,setRefuelingsCount     ] = useState<number>(0)               //給油データの件数
    const [readed              ,setReaded              ] = useState<boolean>(true)           //読み込み状態完了フラグ
    const [pageLimitSelect     ,setPageLimitSelect     ] = useState<number>(10)              //表示件数設定値
    const [pagingNumber        ,setPagingNumber        ] = useState<number>(1)               //ページング（現在ページ）
    const [pagingSelectable    ,setPagingSelectable    ] = useState<number[]>([1])           //ページングの上限値(結果依存）
    const [searchCondition     ,setSearchCondition     ] = useState<t_searchCondition>(initCondition())  //検索条件（日付～メモ）
    const [buttonDisabled      ,setButtonDisabled      ] = useState<boolean>(true)           //検索ボタンが無効状態
    const [resetDone           ,setResetDone           ] = useState<boolean>(true)           //検索がリセット状態
    const [enableSarch         ,setEnableSarch         ] = useState<boolean>(false)          //検索可能
    const [sortKey             ,setSortKey             ] = useState<sortKeys>(sortKeys.DATE)           //
    const [sortOrder           ,setSortOrder           ] = useState<sortOrders>(sortOrders.DESC)       //

    //ソート切り替え時処理
    const changeSort = (e:BaseSyntheticEvent)=> {
        const dataName = e.target.getAttribute('data-name')
        if(dataName == sortKey){
            setSortOrder( sortOrder == sortOrders.ASC ?sortOrders.DESC:sortOrders.ASC )
            return
        }
        setSortKey( dataName )
    }
    //検索条件の入力時イベント
    function _setSearchCondition(e:React.ChangeEvent<MySearchInputElement>){
        console.log(e)
        const tmp  = {...searchCondition}
        const name = e.target.name

        tmp[name] = e.target.value
        searchCondition[name] = e.target.value
        setSearchCondition(tmp)
        setButtonDisabled(false)
        setResetDone(false)
    }

    function changePageNumSelect(e:React.ChangeEvent<HTMLSelectElement>){
        setPageLimitSelect(Number(e.target.value))
        setPagingNumber(1)
    }

    function changPagingNumber(e:React.ChangeEvent<HTMLSelectElement>){
        setPagingNumber(Number(e.target.value))
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


    const f = async ()=>{
        // setReaded(false)
        console.log("api request")
        const url = 'http://'+process.env.API_ENDPOINT+'/api/refuelings'
        const instance = axios.create({ withCredentials: true })
        const result = await instance.get(url,{
            params:{
                limit         : pageLimitSelect,
                page          : pagingNumber,
                date_start    : searchCondition.date_start     || null,
                date_end      : searchCondition.date_end       || null,
                amount_low    : searchCondition.amount_lower   ?  Number(searchCondition.amount_lower  ) : null,
                amount_high   : searchCondition.amount_high    ?  Number(searchCondition.amount_high   ) : null,
                distance_low  : searchCondition.distance_lower ?  Number(searchCondition.distance_lower) : null,
                distance_high : searchCondition.distance_high  ?  Number(searchCondition.distance_high ) : null,
                gas_station   : searchCondition.gas_station    || null,
                memo          : searchCondition.memo           || null,
                sort_key      : sortKey || null,
                sort_order    : sortOrder || null,
            },
        }).catch(e=> {
            return {data:{result:'fail'}}
        })
        return result;
    };

    async function searchRequest(){
        const f2 = async()=>{
            const result = await f();
            if(result){
                setRefuelings_data_list(result.data.list)
                setRefuelingsCount(result.data.count)
                setPagingSelectable([...Array(Math.ceil(result.data.count/pageLimitSelect)).keys()].map(i => ++i))
                setReaded(true)
            }
        }
        await f2();
        setButtonDisabled(true)
    }

    //検索ボタン押下時イベント
    function searchResult(){
        setPagingNumber(1)      //ページングを1ページに戻す
        setEnableSarch(true)    //検索イベントを発火させる
        setButtonDisabled(true) //検索ボタンを無効状態にする
    }
    //リセットボタン押下時イベント
    function resetSearch(){
        setPagingNumber(1)            //ページングを1ページに戻す
        setSearchCondition(initCondition()) //検索条件を全部初期に戻す
        setEnableSarch(true)          //検索イベントを発火させる
        setResetDone(true)            //リセットボタンを無効状態にする
    }

    // ページング切り替え時処理イベント（ページタイトル）
    useEffect(()=>{
        document.title = "給油データ一覧" + "["+pagingNumber+"]"
    }, [pagingNumber]);

    //表示件数・ページング・ソートキー切り替え・ソート昇順降順切り替え時 イベント
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

    //検索イベント発火時処理
    useEffect(()=>{
        if(enableSarch){
            searchRequest()             //検索処理を実行
            setEnableSarch(false) //検索イベント発火をオフにする
        }
    },[enableSarch])

    return {
        refuelings_data_list,
        refuelingsCount,
        readed,
        pageLimitSelect,
        pagingNumber,
        pagingSelectable,
        searchCondition,
        buttonDisabled,
        resetDone,
        sortKey,
        sortOrder,
        changeSort,
        _setSearchCondition,
        changePageNumSelect,
        changPagingNumber,
        pagingPrevious,
        pagingNext,
        searchResult,
        resetSearch,
    }
}

export {UseSortType}
export default useRefuelings
