import * as React from "react";
import axios from "axios";
import {useState} from "react";
import {RouteComponentProps} from "react-router";

// type Props = RouteComponentProps<{
//     match: {
//         token: string
//     }
// } >;
export default (props: any)=>{

    const [mail,       setMail]:any = useState('test@test444444.local')
    const [password,       setPassword]:any = useState('')
    const [passwordConfirmation,       setPasswordConfirmation]:any = useState('')
    const {params} = props.match
    const token = params.token

    function setEmail(event: React.ChangeEvent<HTMLInputElement>){
        setMail( event.target.value);
    }
    function _setPassword(event: React.ChangeEvent<HTMLInputElement>){
        setPassword( event.target.value);
    }
    function _setPasswordConfirmation(event: React.ChangeEvent<HTMLInputElement>){
        setPasswordConfirmation( event.target.value);
    }
    // $request->only('email', 'password', 'password_confirmation', 'token'),

    async function submitReset(){
        // setForgotStatus(false)
        const instance = axios.create({withCredentials: true})

        interface resType {
            data: boolean
        }

        const resetResult : resType = await instance.post('http://localhost:9000/api/mypasswordresetexec'
            , {email:mail,password:password,password_confirmation:passwordConfirmation,token:token}
            ,{ withCredentials: true }
        ).catch( e => {
            return {data:false};
        });

        if( resetResult ){
            // if( forgotResult.data == true ) setForgotStatus(true)
        }
    }

    return (<div>
        <h1 className="title">パスワード再設定</h1>
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
            <p className="control has-icons-left has-icons-right">
                <input className="input" type="password" placeholder="password" name="password"  value={password} onInput={_setPassword} />
                <span className="icon is-small is-left">
                            <i className="fas fa-envelope" />
                        </span>
                <span className="icon is-small is-right">
                            <i className="fas fa-check" />
                        </span>
            </p>
        </div>
        <div className="field">
            <p className="control has-icons-left has-icons-right">
                <input className="input" type="password" placeholder="password_confirmation" name="password_confirmation"  value={passwordConfirmation} onInput={_setPasswordConfirmation} />
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
                <button className="button is-success" onClick={submitReset} >
                    送信
                </button>
            </p>
        </div>
    </div>)
}
