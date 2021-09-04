import * as React from "react";
import {UseSortType} from "../hooks/use-refuelings";

export default ({refuelingHook}:{refuelingHook:UseSortType})=> {

    const {
        totalAmount     ,
        totalDistance     ,
        totalFuelEconomy     ,
    } = refuelingHook

    return (<>
        <div className="col-12 col-sm-4">総走行距離：{totalDistance} km</div>
        <div className="col-12 col-sm-4">総給油量：{totalAmount} l</div>
        <div className="col-12 col-sm-4">燃費：{totalFuelEconomy} km/l</div>
    </>)
}