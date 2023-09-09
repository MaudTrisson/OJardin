import React from 'react';

const PlantHover = ({ plantData }) => {


    console.log(plantData);
        /*Mettre le chemin de l'image en relatif*/
        return (
            <div className="plantHover">
                <p><img src={`/uploads/${plantData.image}`} width="100px" alt={plantData.name}/></p>
                <p>{plantData.name}</p>
                <p>{plantData.description}</p>
                <p>Début de floraison : {plantData.flowering_start.date}</p>
                <p>Fin de floraison :{plantData.flowering_end.date}</p>
            </div>
        );
  
    
};

export default PlantHover;






