import * as React from "react";
import {Link} from "react-router-dom";

export default class RefuelingSubHeader extends React.Component<{} ,{}> {

    render() {
        return (
            <>
                [<Link to="/refueling">一覧</Link>]
                [<Link to="/refueling/regist">登録</Link>]
            </>
        );
    }
}
