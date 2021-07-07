import * as React from 'react';
import * as ReactDom from 'react-dom';
import Base from "./base";
import axios from "axios";

const initilize = (async function (){

    const root = document.getElementById( 'root' )

    ReactDom.render(<Base />,root)

 });

initilize()


