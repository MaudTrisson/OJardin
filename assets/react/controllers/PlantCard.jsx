import React from 'react';

const Plantcard = ({ plant }) => {
  
    if (plant) {

        return (
            <div className="plantCard" id={plant.id} data-plant={JSON.stringify(plant)}>
                <p>{plant.name}</p>
            </div>
        );
    }
  
    
};

export default Plantcard;






