import * as React from 'react';
import * as ReactDom from 'react-dom';
import Hello from "./hello";
import axios from "axios";

 const initialize = async function (){

     const root = document.getElementById('root')

     const instance = axios.create({ withCredentials: true })

     const loginCheckResult = await instance.get('http://localhost:9000/api/mylogincheck').catch(e=> {
         return {data:{result:'fail'}}
     })

     const logind = loginCheckResult.data.result == 'ok'

     ReactDom.render(<Hello logind={logind}/>,root);
 };

initialize();

