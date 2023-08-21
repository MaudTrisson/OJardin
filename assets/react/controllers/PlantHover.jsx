import React from 'react';

const PlantHover = ({ plantData }) => {


    console.log(plantData);
        /*Mettre le chemin de l'image en relatif*/
        return (
            <div className="plantHover">
                <p>{plantData.name}</p>
                <p>{plantData.description}</p>
                <p>{plantData.flowering_start.date}</p>
                <p>{plantData.flowering_end.date}</p>
                <img src={`/public/uploads/${plantData.image}`} alt={plantData.name}/>
            </div>
        );
  
    
};

export default PlantHover;






