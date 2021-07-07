import {Link, useLocation} from "react-router-dom";
import * as React from "react";


export default class UserHeader extends React.Component<{} ,{}> {
    // getLocation(){
    //     const location = useLocation();
    //     console.log(location.pathname)
    //     return location
    // }
    render() {
        // const location = this.getLocation();

        return (
            <>
                <div>ログインしてます</div>
                [<Link to="/refueling">給油データ</Link>]
                [<Link to="/household_account">家計簿</Link>]
            </>
        );
    }
}
