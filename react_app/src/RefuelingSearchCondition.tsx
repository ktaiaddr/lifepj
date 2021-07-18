import * as React from "react";
import {UseSortType} from "./hooks/use-refuelings";

export default ({refuelingHook}:{refuelingHook:UseSortType})=> {

    const { _setSearchCondition ,
            searchCondition     ,
            searchResult        ,
            resetSearch         ,
            buttonDisabled      ,
            resetDone           } = refuelingHook

    return (
        <>
            <div className="row mb-4">
                <div className="col-lg-6">
                    <div className="row">
                        <div className="col">
                            日付：
                        </div>
                    </div>
                    <div className="row">
                        <div className="col-lg-5">
                            <input type="date"
                                   className="form-control"
                                   placeholder="日付"
                                   name="date_start"
                                   value={searchCondition.date_start}
                                   onInput={_setSearchCondition} />
                        </div>
                        ～
                        <div className="col-lg-5">
                            <input type="date"
                                   value={searchCondition.date_end}
                                   className="form-control"
                                   placeholder="日付"
                                   name="date_end"
                                   onInput={_setSearchCondition}  />
                        </div>
                    </div>
                </div>
                <div className="col-lg-3">
                    <div className="row">
                        <div className="col">
                            距離：
                        </div>
                    </div>
                    <div className="row">
                        <div className="col-lg-5">
                            <input type="number"
                                   className="form-control"
                                   value={searchCondition.distance_lower}
                                   placeholder="距離下限"
                                   min="0" max="1000"
                                   name="distance_lower"
                                   onInput={_setSearchCondition} />
                        </div>
                        ～
                        <div className="col-lg-5">
                            <input type="number"
                                   value={searchCondition.distance_high}
                                   className="form-control"
                                   placeholder="距離上限"
                                   min="0"
                                   max="1000"
                                   name="distance_high"
                                   onInput={_setSearchCondition} />
                        </div>
                    </div>
                </div>
                <div className="col-lg-3">
                    <div className="row">
                        <div className="col">
                            数量：
                        </div>
                    </div>
                    <div className="row">
                        <div className="col-lg-5">
                            <input type="number"
                                   className="form-control"
                                   placeholder="数量下限"
                                   value={searchCondition.amount_lower}
                                   min="0" max="100"
                                   name="amount_lower" onChange={_setSearchCondition} />
                        </div>
                        ～
                        <div className="col-lg-5">
                            <input type="number" className="form-control" placeholder="数量上限" min="0" max="100"
                                   value={searchCondition.amount_high}
                                   name="amount_high" onInput={_setSearchCondition} />
                        </div>
                    </div>
                </div>

            </div>
            <div className="row mb-4">

                <div className="col-lg-6">
                    <div className="row">
                        <div className="col">
                            ガスステーション：
                        </div>
                        <div className="row">
                            <div className="col">
                                <input type="text"
                                       value={searchCondition.gas_station}
                                       className="form-control" placeholder="ガスステーション" name="gas_station" onInput={_setSearchCondition} />
                            </div>
                        </div>
                    </div>
                </div>
                <div className="col-lg-6">
                    <div className="row">
                        <div className="col">
                            メモ：
                        </div>
                        <div className="row">
                            <div className="col">
                                <input type="text"
                                       value={searchCondition.memo}
                                       className="form-control" placeholder="メモ" name="memo" onInput={_setSearchCondition} />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div className="mb-4 d-flex">

                    <div className="0">
                        <button className={"btn"+ (buttonDisabled?" btn-secondary":" btn-primary")} onClick={searchResult} disabled={buttonDisabled}>検索</button>
                    </div>
                    <div className="mx-2">
                        <button className={"btn"+ (resetDone?" btn-secondary":" btn-primary")} onClick={resetSearch} disabled={resetDone}>リセット</button>
                    </div>
            </div>
        </>
    )
}