import React, { useState, useEffect } from 'react';
import Plantcard from './Plantcard';

const Search = ({ plants }) => {

    
if (plants) {
    const [searchProperties, setSearchProperties] = useState(null);
    const [plantsInfo, setPlantsInfo] = useState(JSON.parse(plants));
    const [searchInfos, setSearchInfos] = useState({
        shadowtype : plantsInfo[0]['shadowtype'],
        groundType : plantsInfo[0]['groundtype'],
        groundAcidity : plantsInfo[0]['groundacidity'],
        category: null,
        usefulness: null,
        color: null,
        name: null
    });

    useEffect(() => {
        getPlantProperties().then((data) => {
            setSearchProperties(data);
        });
    }, []);

    useEffect(() => {
        var url = 'http://localhost:8000/plant/search';

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json;charset=utf-8'
                },
                body: JSON.stringify(searchInfos)
            })
            .then((resp) => resp.text())
            .then(function(data) {
                if (data) {
                    setPlantsInfo(JSON.parse(data));
                } else {
                    console.log('pas de données.');
                }
            })
            .catch(function(error) {
                //setMessage(error);
            })

     }, [searchInfos]);


    function getPlantProperties() {
        var url = 'http://localhost:8000/plant/properties';
  
        return fetch(url)
        .then((resp) => resp.text())
        .then(function(data) {
          if (data) {
            return JSON.parse(data);
          } else {
            throw new Error('Pas de données');
          }
        })
        .catch(function(error) {
          console.log(error);
        });
    }



        const handleRadioChange = (event) => {
            let array = { ...searchInfos }; // Utilisez spread operator pour créer une copie
            if (event.target.name == 'name') {
                array[event.target.name] = event.target.value;
            } else {
                array[event.target.name] = parseInt(event.target.value) + 1;
            }
            console.log(array);
            setSearchInfos(array);
        };

        return (
            
            <div>
                {searchProperties && (
                    <form>
                        <div id="name" className='mb-4'>
                            <p className="font-weight-bold">Nom de la plante</p>
                                    <input type="text" name="name" value={searchInfos.name} onChange={handleRadioChange}/>
                        </div>
                        <div id="shadowtype" className='mb-4'>
                            <p className="font-weight-bold">Ombrages</p>
                            {searchProperties.shadowtypes.map((shadowtype, index) => (
                                <label key={index}>
                                    <input type="radio" name="shadowtype" value={index} onChange={handleRadioChange} checked={Number(shadowtype.id) === Number(plantsInfo[0].shadowtype)}/>
                                    {shadowtype.name}
                                </label>   
                            ))}
                        </div>
                        <div id="groundtype" className='mb-4'>
                            <p className="font-weight-bold">Types de sol</p>
                            {searchProperties.groundtypes.map((groundtype, index) => (
                                <label key={index}>
                                    <input type="radio" name="groundtype" value={index} onChange={handleRadioChange} checked={Number(groundtype.id) === Number(plantsInfo[0].groundtype)}/>
                                    {groundtype.name}
                                </label>   
                            ))}
                        </div>
                        <div id="groundacidity" className='mb-4'>
                            <p className="font-weight-bold">Acidités de sol</p>
                            {searchProperties.groundacidities.map((groundacidity, index) => (
                                <label key={index}>
                                    <input type="radio" name="groundacidity" value={index} onChange={handleRadioChange} checked={Number(groundacidity.id) === Number(plantsInfo[0].groundacidity)}/>
                                    {groundacidity.name}
                                </label>   
                            ))}
                        </div>
                        <div id="category" className='mb-4'>
                            <p className="font-weight-bold">Categories</p>
                            {searchProperties.categories.map((category, index) => (
                                <label key={index}>
                                    <input type="radio" name="category" value={index} onChange={handleRadioChange}/>
                                    {category.name}
                                </label>   
                            ))}
                        </div>
                        <div id="usefulness" className='mb-4'>
                            <p className="font-weight-bold">Utilité</p>
                            {searchProperties.usefulnesses.map((usefulness, index) => (
                                <label key={index}>
                                    <input type="radio" name="usefulness" value={index} onChange={handleRadioChange}/>
                                    {usefulness.name}
                                </label>   
                            ))}
                        </div>
                        <div id="color" className='mb-4'>
                            <p className="font-weight-bold">Couleur</p>
                            {searchProperties.colors.map((color, index) => (
                                <label key={index}>
                                    <input type="radio" name="color" value={index} onChange={handleRadioChange}/>
                                    {color.name}
                                </label>   
                            ))}
                        </div>
                    </form>
                )}
                {plantsInfo && (
                    <ul>
                        {plantsInfo.map((plantInfo) => (
                            <Plantcard id={plantInfo.id} key={plantInfo.id} plant={plantInfo}/>
                        ))}
                    </ul>
                )}
                
            </div>
            
        );
    }
  
    
};

export default Search;






