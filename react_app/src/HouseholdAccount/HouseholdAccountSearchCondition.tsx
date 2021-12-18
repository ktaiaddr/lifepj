import * as React from "react";

export default ({
                    transactionTypeDefinitionsList,
                    accountList,
                    _setTransactionTypeSearchValue,
                    _setAccountIdSearchValue,
                    transactionTypeSearchValue,
                    accountIdSearchValue,
                    viewMonth,
                    _setViewMonth,
                    balanceAggregateViewModel,
                    transactionSearchRange,
                    nowMonth,
                    closingMonth}
                    :{
    transactionTypeDefinitionsList:any,accountList:any,_setTransactionTypeSearchValue:any,_setAccountIdSearchValue:any,transactionTypeSearchValue:number|null,accountIdSearchValue:number|null,
    viewMonth:string,_setViewMonth:any,balanceAggregateViewModel:any,transactionSearchRange:any,nowMonth:string,closingMonth:number})=> {
    console.log(closingMonth)
    console.log(parseInt(viewMonth.replace('-','')))
    return (
        <>
            <div className="row mb-4">
                <div className="col-lg-12">
                    <div className="row">
                        <div className="row">
                            <div className="mb-4 d-flex">
                                <div className="0">
                                    <input className="form-control" type="month" value={viewMonth} onChange={_setViewMonth} max={transactionSearchRange.maxMonth} min={transactionSearchRange.minMonth}/>

                                </div>
                            </div>
                        </div>
                        <div className="row">
                            {parseInt(viewMonth.replace('-',''))<parseInt(nowMonth.replace('-',''))&&closingMonth<parseInt(viewMonth.replace('-',''))?(
                                <div className="mb-4 d-flex">
                                    <button style={{maxWidth:"200px",background:"#dc3545",color:"white"}} className="form-control" type={'button'} onClick={()=>alert(1)}>締め処理を行う</button>
                                </div>
                            ):""}
                        </div>
                        <div className="row">
                            <div className="mb-4 d-flex">
                                <table className="table" style={{verticalAlign:"middle"}}>
                                    <thead>
                                    <tr>
                                        <th>口座</th>
                                        <th>残高</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    {balanceAggregateViewModel.map((balanceAggregate:{
                                        account_id: number
                                        account_name: string
                                        balance: number
                                        aggregate_balance?: number
                                        latest_closing_balance?: number
                                        closing_next_month_day_of_first?: string
                                    })=>{
                                        return (
                                            <tr>
                                                <td>
                                                    {balanceAggregate.account_name}
                                                </td>
                                                <td>
                                                    {balanceAggregate.balance.toLocaleString()+"円"}
                                                </td>
                                            </tr>
                                        )
                                    })}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div className="row">
                            <div className="col-md-2 col-lg-2">
                                対象取引種別：
                            </div>
                            <div className="col-md-10 col-lg-10">
                                <label style={{padding:"0 10px"}}>
                                    <input className="form-check-input"
                                           type     = "radio"
                                           name     = "transaction_type"
                                           value    = {0}
                                           onChange = {_setTransactionTypeSearchValue}
                                           checked  = {transactionTypeSearchValue==0} />
                                    全て
                                </label>
                                {transactionTypeDefinitionsList.map((transactionTypeDefinition:any)=>(<label style={{padding:"0 10px"}}>
                                    <input className="form-check-input"
                                           type     = "radio"
                                           name     = "transaction_type"
                                           value    = {transactionTypeDefinition.typeValue}
                                           key      = {transactionTypeDefinition.typeValue}
                                           onChange = {_setTransactionTypeSearchValue}
                                           checked  = {transactionTypeSearchValue==transactionTypeDefinition.typeValue} />
                                    {transactionTypeDefinition.typeLabel}
                                </label>))}
                            </div>
                        </div>

                        <div className="row">
                            <div className="col-md-2 col-lg-2">
                                対象口座：
                            </div>
                            <div className="col-md-10 col-lg-10">
                                <label style={{padding:"0 10px"}}>
                                    <input className="form-check-input"
                                           type     = "radio"
                                           name="account_id"
                                           value={0}
                                           onChange={_setAccountIdSearchValue}
                                           checked={accountIdSearchValue==0} />
                                    全て
                                </label>
                                {accountList.map((account:any)=>(<label style={{padding:"0 10px"}}>
                                    <input className="form-check-input"
                                           type     = "radio"
                                           name     = "account_id"
                                           value    = {account.accountId}
                                           key      = {account.accountId}
                                           onChange = {_setAccountIdSearchValue}
                                           checked  = {accountIdSearchValue==account.accountId} />
                                    {account.name}
                                </label>))}
                            </div>
                        </div>
                    </div>
                </div>

                {/*<div className="mb-4 d-flex">*/}
                {/*    <div className="0">*/}
                {/*        <button className={"btn"+ (""?" btn-secondary":" btn-primary")} onClick={()=>""} disabled={false}>検索</button>*/}
                {/*    </div>*/}
                {/*    <div className="mx-2">*/}
                {/*        <button className={"btn"+ (""?" btn-secondary":" btn-primary")} onClick={()=>""} disabled={false}>リセット</button>*/}
                {/*    </div>*/}
                {/*</div>*/}
            </div>
        </>
    )
}