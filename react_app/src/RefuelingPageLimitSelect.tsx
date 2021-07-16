import * as React from "react";

const pageNumSelectable = [10,20,50,100]

export default (props:any)=> {

    const pageLimitSelect = props.pageLimitSelect
    const changePageNumSelect = props.changePageNumSelect

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