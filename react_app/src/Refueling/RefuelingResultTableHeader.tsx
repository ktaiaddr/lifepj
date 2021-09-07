import * as React from "react";
import {sortKeys,sortOrders} from "./sortEnums"

export default (props:any)=> {
    const changeSort = props.changeSort
    const sortKey :sortKeys = props.sortKey
    const sortOrder :sortOrders = props.sortOrder

    const textClass = (key:sortKeys)=> {
        if(sortKey == key) return "text-primary"
        return "text-secondary"
    }
    const sortText = (order:sortOrders)=>{
        if(sortOrders.DESC == order) return "▼"
        return "▲"
    }

    return (<thead>
    <tr>
        <th className={'text-nowrap'}>日付
            <span onClick={changeSort} data-name={sortKeys.DATE} className={textClass(sortKeys.DATE)}>
                                        {sortText(sortOrder)}
                                    </span>
        </th>
        <th className={'text-nowrap'}>距離
            <span onClick={changeSort} data-name={sortKeys.DISTANCE}
                  className={textClass(sortKeys.DISTANCE)}>
                                        {sortText(sortOrder)}
                                    </span>
        </th>
        <th className={'text-nowrap'}>数量
            <span onClick={changeSort} data-name={sortKeys.AMOUNT}
                  className={textClass(sortKeys.AMOUNT)}>
                                        {sortText(sortOrder)}
                                    </span>
        </th>
        <th className={'text-nowrap'}>燃費
            <span onClick={changeSort} data-name={sortKeys.FUELECONOMY}
                  className={textClass(sortKeys.FUELECONOMY)}>
                                        {sortText(sortOrder)}
                                    </span>
        </th>
        <th className={'text-nowrap'}>ガスステーション
            <span onClick={changeSort}
                  data-name={sortKeys.GASSTATION}
                  className={textClass(sortKeys.GASSTATION)}>
                                        {sortText(sortOrder)}
                                    </span>
        </th>
        <th className={'text-nowrap'}>メモ
            <span onClick={changeSort} data-name={sortKeys.MEMO}
                  className={textClass(sortKeys.MEMO)}>
                                        {sortText(sortOrder)}
                                    </span>
        </th>
    </tr>
    </thead>)
}