import React from 'react';

const PlantCard = ({ plant }) => {
  
    if (plant) {
        return (
            <div className="plantCard">
                <p>{plant.name}</p>
                <p>{plant.description}</p>
                <p><span>Hauteur : {plant.height}</span> - <span>largeur : {plant.width}</span></p>
            </div>
            
        );
    }
  
    
};

export default PlantCard;






