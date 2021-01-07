import React from 'react'
import ReactLoading from "react-loading";

export default function LoadingCircle() {
    return (
        <div style={{top: '50%', left: '50%', zIndex: 10}}>
            <ReactLoading color='black'/>
        </div>
    )
}
