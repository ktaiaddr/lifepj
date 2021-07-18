import * as React from "react";
import {sortKeys,sortOrders} from "./sortEnums"

import RefuelingResultTableHeader from "./RefuelingResultTableHeader";
import RefuelingResultTableBody from "./RefuelingResultTableBody";
import {UseSortType} from "./hooks/use-refuelings";

export default ({refuelingHook}:{refuelingHook:UseSortType})=> {

    const changeSort           = refuelingHook.changeSort
    const sortKey              = refuelingHook.sortKey
    const sortOrder            = refuelingHook.sortOrder
    const refuelings_data_list = refuelingHook.refuelings_data_list

    return (
        <table className="table">

            <RefuelingResultTableHeader sortKey={sortKey}
                                        sortOrder={sortOrder}
                                        changeSort={changeSort} />

            <RefuelingResultTableBody refuelings_data_list={refuelings_data_list} />

        </table>
    )
}