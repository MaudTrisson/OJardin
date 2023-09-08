import React from 'react';

const Gauge = ({filling, overflow }) => {

    return (

        <div id="self_sufficiency_container" >
            <div id="self_sufficiency_gauge" style={{ width: `${filling}px`, backgroundColor: (overflow) ? 'red' : '#5c8a9a'}}></div>
        </div>

    );

};

export default Gauge;






