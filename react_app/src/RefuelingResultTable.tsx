import * as React from "react";

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
export default (props:any)=> {

    const changeSort = props.changeSort
    const sortKey = props.sortKey
    const sortOrder = props.sortOrder
    const sortKeys = props.sortKeys
    const sortOrders = props.sortOrders
    const refuelings_data_list :[Array<refuelings>,any] = props.refuelings_data_list

    return ( <table className="table">
        <thead>
        <tr>
            <th>日付
                <span onClick={changeSort} data-name={sortKeys.DATE} className={sortKey==sortKeys.DATE?"text-primary":"text-secondary"}>
                                        {sortOrder==sortOrders.DESC?"▼":"▲"}
                                    </span>
            </th>
            <th>距離
                <span onClick={changeSort} data-name={sortKeys.DISTANCE} className={sortKey==sortKeys.DISTANCE?"text-primary":"text-secondary"}>
                                        {sortOrder==sortOrders.DESC?"▼":"▲"}
                                    </span>
            </th>
            <th>数量
                <span onClick={changeSort} data-name={sortKeys.AMOUNT} className={sortKey==sortKeys.AMOUNT?"text-primary":"text-secondary"}>
                                        {sortOrder==sortOrders.DESC?"▼":"▲"}
                                    </span>
            </th>
            <th>燃費
                <span onClick={changeSort} data-name={sortKeys.FUELECONOMY} className={sortKey==sortKeys.FUELECONOMY?"text-primary":"text-secondary"}>
                                        {sortOrder==sortOrders.DESC?"▼":"▲"}
                                    </span>
            </th>
            <th>ガスステーション
                <span onClick={changeSort} data-name={sortKeys.GASSTATION} className={sortKey==sortKeys.GASSTATION?"text-primary":"text-secondary"}>
                                        {sortOrder==sortOrders.DESC?"▼":"▲"}
                                    </span>
            </th>
            <th>メモ
                <span onClick={changeSort} data-name={sortKeys.MEMO} className={sortKey==sortKeys.MEMO?"text-primary":"text-secondary"}>
                                        {sortOrder==sortOrders.DESC?"▼":"▲"}
                                    </span>
            </th>
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
    </tbody></table>)
}