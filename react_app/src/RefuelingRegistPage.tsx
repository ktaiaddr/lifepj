import * as React from "react";
import UserHeader from "./user_header";
import RefuelingSubHeader from "./RefuelingSubHeader";

export default class RefuelingRegistPage extends React.Component<{},{}> {

    constructor(props:any) {
        super(props);
        this.state = {}
    }
    render(): React.ReactNode {

        return (
            <>
                <div><UserHeader /></div>
                <div><RefuelingSubHeader /></div>
                登録するページ
            </>
        );
    }
}