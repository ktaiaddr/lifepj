// Hello コンポーネントを定義
import * as React from "react";
import HelloChild  from "./hello_child";
import axios from "axios";
axios.defaults.headers['X-Requested-With'] = 'XMLHttpRequest';

// Hello コンポーネントの属性（プロパティ）を定義
interface HelloState {
    name?: string;  // オプショナルな name 属性
    age?: number|string;  // オプショナルな name 属性
}

export default class Hello extends React.Component<null,HelloState> {
    async componentDidMount() {
        // setTimeout(()=>alert(1),1000);
        const instance = axios.create({
            withCredentials: true
        })
        const res3 = await instance.post('http://localhost:9000/login', {
            email:'test@test444444.local',
            password:'test444444',
        }, { withCredentials: true }
        )

// console.log(res3);

        const res2 = await instance.post('http://localhost:9000/api/refuelings/regist', {
            // ここにクエリパラメータを指定する
            'date'                : '2021-07-05',
            'refueling_amount'    : 1,
            'refueling_distance'  : 500,
            'gas_station'         : "g",
            'memo'                : "m",
            }
            , { withCredentials: true }
        ).catch((e)=> {return e} );
console.log(res2)



        const res = await instance.get('http://localhost:9000/api/refuelings', {
            // ここにクエリパラメータを指定する
            'date_start'      :  '2021-01-01',
            'date_end'        :  '2021-08-01',
            'amount_low'      :  1.1,
            'amount_high'     :  200.1,
            'distance_low'    :  1.1,
            'distance_high'   :  1000,
            'gas_station'     :  'g',
            'memo'            :  "m",
            'page'            :  1,
            }
        ).catch((e)=> {return e} );
        console.log(res)

    }
    componentWillMount() {
        // setTimeout(()=>alert(2),1000);
    }
    constructor(props:any) {
        super(props);
        this.state = {name : "fug",age:"hoge"}
    }
    alert(){
        this.setState({
            name: (this.state.name == 'fug') ? 'hage':'fug'
        });
    }
    alert2(){
        this.setState({age:
                ( typeof (this.state.age) == 'number')?
                    (this.state.age >=110 ? 100 :this.state.age+1)
                    :100
        });
    }
    render(): React.ReactNode {
        const name = this.state.name ?? 'Unknown';

        return (
            <HelloChild
                name={this.state.name}
                age={this.state.age}
                alert={this.alert.bind(this)}
                alert2={this.alert2.bind(this)}
            />
        );
    }
}
