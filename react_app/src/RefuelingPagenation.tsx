import * as React from "react";


export default (props:any)=> {

    const pagingPrevious = props.pagingPrevious
    const pagingNumber = props.pagingNumber
    const changPagingNumber = props.changPagingNumber
    const pagingSelectable :number[]= props.pagingSelectable
    const pagingNext = props.pagingNext

    return (<div className="col-7 col-sm-10">
        <nav aria-label="Page navigation example">
            <ul className="pagination justify-content-end">
                <li className="page-item">
                    <a className="page-link" href="#" onClick={pagingPrevious}>＜</a>
                </li>
                <li className="page-item">
                    <select className="form-select" value={pagingNumber} onChange={changPagingNumber}>
                        {pagingSelectable.map(num=>
                            <option value={num} key={num}>{num}</option>
                        )}
                    </select>
                </li>
                <li className="page-item">
                    <a className="page-link" href="#" onClick={pagingNext}>＞</a>
                </li>
            </ul>
        </nav>
    </div>)
}