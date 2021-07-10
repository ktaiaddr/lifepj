import * as React from "react";
import axios from "axios";
import {useState} from "react";

export default (props: {})=>{

    const [mail,       setMail]:any = useState('test@test444444.local')
    const [forgotStatus, setForgotStatus]:any = useState(null)

    function setEmail(event: React.ChangeEvent<HTMLInputElement>){
        setMail( event.target.value);
    }

    async function submitForget(){
        setForgotStatus(false)
        const instance = axios.create({withCredentials: true})

        interface resType {
            data: boolean
        }

        const forgotResult : resType = await instance.post('http://localhost:9000/api/mypasswordreset'
            , {email:mail}
            ,{ withCredentials: true }
        ).catch( e => {
            return {data:false};
        });

        if( forgotResult ){
            if( forgotResult.data == true ) setForgotStatus(true)
        }
    }

    return (<div>
        <h1 className="title">パスワードリセット</h1>
        {forgotStatus===false&&<div className="message">パスワードリセットメールの送信に失敗しました</div>}
        {forgotStatus===true &&<div className="message">パスワードリセットメールを送信しました</div>}
        <div className="field">
            <p className="control has-icons-left has-icons-right">
                <input className="input" type="email" placeholder="Email" name="mail"  value={mail} onInput={setEmail} />
                <span className="icon is-small is-left">
                            <i className="fas fa-envelope" />
                        </span>
                <span className="icon is-small is-right">
                            <i className="fas fa-check" />
                        </span>
            </p>
        </div>
        <div className="field">
            <p className="control">
                <button className="button is-success" onClick={submitForget} >
                    送信
                </button>
            </p>
        </div>
    </div>)
}
