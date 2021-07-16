import * as React from "react";
import {sortKeys,sortOrders} from "./sortEnums"

import RefuelingResultTableHeader from "./RefuelingResultTableHeader";
import RefuelingResultTableBody from "./RefuelingResultTableBody";
import refuelings from "./interfaceRefuelings";

export default (props:any)=> {

    const changeSort = props.changeSort
    const sortKey :sortKeys = props.sortKey
    const sortOrder :sortOrders = props.sortOrder
    const refuelings_data_list :Array<refuelings> = props.refuelings_data_list

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