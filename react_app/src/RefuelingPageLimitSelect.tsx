import * as React from "react";
import {UseSortType} from "./hooks/use-refuelings";

const pageNumSelectable = [10,20,50,100]

export default (props:{refuelingHook:UseSortType})=> {

    const pageLimitSelect = props.refuelingHook.pageLimitSelect
    const changePageNumSelect = props.refuelingHook.changePageNumSelect

    return (<div className="col-5 col-sm-2">
        <div className="input-group">
            <select className="form-select" defaultValue={pageLimitSelect} onChange={changePageNumSelect}>
                {pageNumSelectable.map(num=>
                    <option value={num} key={num}>{num}件</option>
                )}
            </select>
        </div>
    </div>)
}