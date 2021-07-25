import * as React from "react";
import axios from "axios";
import {useState} from "react";

export default (props: any)=>{

    const [name,         setName]:any = useState('')
    const [mail,         setMail]:any = useState('')
    const [password, setPassword]:any = useState('')
    const [passwordConfirmation, setPasswordConfirmation]:any = useState('')
    const [message, setMessage]:any = useState('')

    function _setName(event: React.ChangeEvent<HTMLInputElement>){
        setName( event.target.value);
    }
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

    async function submitRegist(){
        // setForgotStatus(false)
        const instance = axios.create({withCredentials: true})

        interface resType {
            data: boolean
        }

        const regsiterResult : resType = await instance.post('http://localhost:9000/api/myregistuser'
            , {name:name,email:mail,password:password,password_confirmation:passwordConfirmation
            }
            ,{ withCredentials: true }
        ).catch( e => {
            return {data:false};
        });

        if( regsiterResult ){
            if( regsiterResult.data ) {
                setMessage('仮登録が完了しました、メールを確認して認証してください')
                return
            }
            if( !regsiterResult.data ) setMessage('仮登録が失敗しました')
        }
    }


    if(message){
        return (
            <>
                <div>
                    <h1 className="title">ユーザ登録</h1>
                    <span>{message}</span>
                </div>
            </>
        )
    }

    return (
        <>
            <div>
                <h1 className="title">ユーザ登録</h1>

                <div className="field">
                    <p className="control has-icons-left has-icons-right">
                        <input className="input" type="text" placeholder="名前" name="name"  value={name} onInput={_setName} />
                          <span className="icon is-small is-right">
                            <i className="fas fa-check" />
                        </span>
                    </p>
                </div>

                <div className="field">
                    <p className="control has-icons-left has-icons-right">
                        <input className="input" type="email" placeholder="メールアドレス" name="mail"  value={mail} onInput={setEmail} />
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
                        <input className="input" type="password" placeholder="パスワード" name="password"  value={password} onInput={_setPassword} />
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
                        <input className="input" type="password" placeholder="パスワード確認" name="password_confirmation"  value={passwordConfirmation} onInput={_setPasswordConfirmation} />
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
                        <button className="button is-success" onClick={submitRegist} >
                            送信
                        </button>
                    </p>
                </div>
            </div>
        </>)
}
