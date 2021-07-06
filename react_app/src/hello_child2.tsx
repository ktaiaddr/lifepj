// Hello コンポーネントを定義
import * as React from "react";
import axios from "axios";

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

export default class HelloChild2 extends React.Component<HelloProps,{refuelings_data_list:refuelings[]}> {

    async componentDidMount() {

        const instance = axios.create({ withCredentials: true })

        const result = await instance.get('http://localhost:9000/api/refuelings').catch(e=> {
            return {data:{result:'fail'}}
        })

        if(result)
            this.setRefuelingsDataList(result.data)
    }
    setRefuelingsDataList(list:[]){
        this.setState({
            refuelings_data_list:list
        })
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
                {this.state.refuelings_data_list
                    ?
                    this.state.refuelings_data_list.map(value=>
                        <>
                            <div>{value.user_id}</div>
                            <div>{value.date}</div>
                            <div>{value.refueling_amount}</div>
                            <div>{value.refueling_distance}</div>
                            <div>{value.gas_station}</div>
                            <div>{value.memo}</div>
                        </>
                    )
                    :
                    <></>
                }
            </>
        );
    }
}
