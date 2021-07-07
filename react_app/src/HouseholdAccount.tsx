// Hello コンポーネントを定義
import * as React from "react";
import axios from "axios";
import {Link} from "react-router-dom";
import UserHeader from "./user_header";

// Hello コンポーネントの属性（プロパティ）を定義
interface HelloProps {}

interface refuelings{
    user_id :number
    date :string
    refueling_amount :number
    refueling_distance:number
    gas_station:string
    memo:string
}

export default class HouseholdAccount extends React.Component<HelloProps,{refuelings_data_list:refuelings[]}> {

    async componentDidMount() {
    }
    constructor(props:any) {
        super(props);
        this.state = {
            refuelings_data_list:[]
        }
    }
    render(): React.ReactNode {

        return (
            <>
                <UserHeader />
                <div>
                    家計簿のページ
                </div>
            </>
        );
    }
}
