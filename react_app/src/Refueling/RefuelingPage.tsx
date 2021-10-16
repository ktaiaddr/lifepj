// Hello コンポーネントを定義
import * as React from "react";
import UserHeader from "../user_header";
import RefuelingSearchCondition from "./RefuelingSearchCondition";
import RefuelingPageLimitSelect from "./RefuelingPageLimitSelect";
import RefuelingResultTotalData from "./RefuelingResultTotalData";
import RefuelingPagenation      from "./RefuelingPagenation";
import RefuelingResultTable     from "./RefuelingResultTable";
import useRefuelings            from "../hooks/use-refuelings";

export default ()=>{
    const refuelingHook = useRefuelings()          // Custom Hook
    return (
        <>
            <div><UserHeader /></div>

            <div>

                <RefuelingSearchCondition refuelingHook={refuelingHook} />

                <div className="row">

                    <RefuelingPageLimitSelect refuelingHook={refuelingHook} />

                    <RefuelingPagenation refuelingHook={refuelingHook}  />

                </div>

                <div className="row mb-4 ">

                    <RefuelingResultTotalData refuelingHook={refuelingHook} />

                </div>
                {
                    refuelingHook.readed &&(<>
                        <div>{refuelingHook.refuelingsCount}件</div>

                        <RefuelingResultTable refuelingHook={refuelingHook} />
                    </>)
                }
            </div>
        </>
    )
}
