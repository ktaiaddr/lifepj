import * as React from "react";
import refuelings from "./interfaceRefuelings";
import {Link} from "react-router-dom";

export default (props:any)=> {

    const refuelings_data_list :Array<refuelings> = props.refuelings_data_list

    const rowColor = (index: number)=> ( index%2==0 ? {background:"lightblue"} : {} )

    return (
        <tbody>
        {refuelings_data_list.map((value,index)=>(
            <tr key={ value.refueling_id } style={rowColor(index)}>
                <td className={'text-nowrap'}><Link to={ 'refueling/regist/'+value.refueling_id }>{ value.date               }</Link></td>
                <td className={'text-nowrap'}>{ value.refueling_distance }</td>
                <td className={'text-nowrap'}>{ value.refueling_amount   }</td>
                <td className={'text-nowrap'}>{ value.fuel_economy       }</td>
                <td className={'text-nowrap'}>{ value.gas_station        }</td>
                <td className={'text-nowrap'}>{ value.memo               }</td>
            </tr>
        ))}
        </tbody>
    )
}