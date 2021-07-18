import * as React from "react";
import {sortKeys,sortOrders} from "./sortEnums"

import RefuelingResultTableHeader from "./RefuelingResultTableHeader";
import RefuelingResultTableBody from "./RefuelingResultTableBody";
import refuelings from "./interfaceRefuelings";
import {UseSortType} from "./hooks/use-refuelings";

export default (props:{refuelingHook:UseSortType})=> {

    const changeSort = props.refuelingHook.changeSort
    const sortKey :sortKeys = props.refuelingHook.sortKey
    const sortOrder :sortOrders = props.refuelingHook.sortOrder
    const refuelings_data_list :Array<refuelings> = props.refuelingHook.refuelings_data_list

    const rowColor = (index: number)=>{
        return index%2==0 ? {background:"lightblue"} : {}
    }

    return (
        <table className="table">

            <RefuelingResultTableHeader sortKey={sortKey}
                                        sortOrder={sortOrder}
                                        changeSort={changeSort} />

            <RefuelingResultTableBody refuelings_data_list={refuelings_data_list} />

        </table>
    )
}