import React, { useState, useEffect } from 'react';
import Plantcard from './PlantCard';
import PlantHover from './PlantHover';

const Search = ({ searchFlowerbedInfo, plants }) => {

if (plants) {
    const [searchProperties, setSearchProperties] = useState(null);
    const [plantsInfo, setPlantsInfo] = useState(JSON.parse(plants));
    const [searchInfos, setSearchInfos] = useState({
        shadowtype : searchFlowerbedInfo['shadowtype'],
        groundType : searchFlowerbedInfo['groundType'],
        groundAcidity : searchFlowerbedInfo['groundAcidity'],
        category: null,
        usefulness: null,
        color: null,
        name: null
    });
    const [HoveredElement, setHoveredElement] = useState({
        isHovered: false,
        hoveredElement: null
    });

    useEffect(() => {
        getPlantProperties().then((data) => {
            setSearchProperties(data);
        });

        hoverAddEvents();

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
                    setPlantsInfo('pas de données');
                }
            })
            .catch(function(error) {
                //setMessage(error);
            })

     }, [searchInfos]);

     useEffect(() => {
        var url = 'http://localhost:8000/plant/search';

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json;charset=utf-8'
                },
                body: JSON.stringify(searchFlowerbedInfo)
            })
            .then((resp) => resp.text())
            .then(function(data) {
                if (data) {
                    setPlantsInfo(JSON.parse(data));
                    hoverRemoveEvents();
                    hoverAddEvents();
                } else {
                    setPlantsInfo("pas de données");
                }
            })
            .catch(function(error) {
                //setMessage(error);
            })

            let inputs = document.querySelectorAll('input[type="radio"]');
            inputs.forEach(input => {
                input.checked = false;
                
                for (let key in searchFlowerbedInfo) {
                    let info = searchFlowerbedInfo[key];
                    
                    if ((input.name.toLowerCase() == key.toLowerCase()) && (input.value == info-1)) {
                        input.checked = true;
                    }
                    console.log(input);
                }
                
            });
                
            

     }, [searchFlowerbedInfo]);

     useEffect(() => {
        setPlantsInfo(plantsInfo);

        if (document.querySelector('#search_container').classList.contains('hide')) {
            document.querySelector('#search_container + ul').style.height = '400px';
        } else {
            document.querySelector('#search_container + ul').style.height = '200px';
        }
     }, [plantsInfo]);


     const hoverAddAndRemoveEvents = () => {
        hoverAddEvents();
        hoverRemoveEvents();
     }

     const hoverAddEvents = () => {
        document.querySelectorAll('.plantCard').forEach((button) => {
            button.addEventListener('mouseenter', handleMouseEnter);
        });

        document.querySelectorAll('.plantCard').forEach((button) => {
            button.addEventListener('mouseleave', handleMouseLeave);
        });
     }

     const hoverRemoveEvents = () => {
        document.querySelectorAll('.plantCard').forEach((button) => {
            button.removeEventListener('mouseenter', handleMouseEnter);
        });

        document.querySelectorAll('.plantCard').forEach((button) => {
            button.removeEventListener('mouseleave', handleMouseLeave);
        });
     }

    const handleMouseEnter = (event) => {
        event.stopPropagation();
        //besoin de decoder car c'est du json HTML
        const decodedPlantData = JSON.parse(new DOMParser().parseFromString(event.target.getAttribute('data-plant'), 'text/html').body.textContent);
        setHoveredElement({
            isHovered: true, 
            hoveredElement: decodedPlantData
        });
    };

    const handleMouseLeave = (event) => {
        event.stopPropagation();
        setHoveredElement({
            isHovered: false, 
            hoveredElement: null
        });
    };

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
            setSearchInfos(array);

            //gérer le bon affichage des boutons radio
            let parentElements = event.target.parentNode.parentNode;
            let labelElements = parentElements.querySelectorAll('label');

            for (let label of labelElements) {

                let labelTextContent = label.textContent;

                let childElement = label.querySelector('input[type="radio"]');
                label.innerHTML = "";

                let newInput = document.createElement('input');
                newInput.type = "radio";
                newInput.name = childElement.name;
                newInput.value = childElement.value.toString();
                

                label.appendChild(newInput);
                label.innerHTML += labelTextContent;

                let newInputElement = label.querySelector('input[type="radio"]');
                newInputElement.onchange = handleRadioChange;
                if (newInputElement.value == event.target.value) {
                    newInputElement.checked = true;
                }

            }

        };

        const displayFilter = () => {
            document.querySelector('#search_container').classList.toggle('hide');

            if (document.querySelector('#search_container').classList.contains('hide')) {
                document.querySelector('#search_container + ul').style.height = '400px';
            } else {
                document.querySelector('#search_container + ul').style.height = '200px';
            }

        }


        return (
            
        <div>
            <div id="filter_icon_container" onClick={() => displayFilter()}>
                <i className="fa fa-filter"></i><span>  Filtres</span>
            </div>
            <div id="search_container" className="hide">
                {searchProperties && (
                    <form>
                        <div id="name" className='mb-4'>
                            <p className="font-weight-bold">Nom de la plante</p>
                                    <input type="text" name="name" value={searchInfos.name ? searchInfos.name : ''} onChange={handleRadioChange}/>
                        </div>
                        <div id="shadowtype" className='mb-4'>
                            <p className="font-weight-bold">Ombrages</p>
                            {searchProperties.shadowtypes.map((shadowtype, index) => (
                                <label key={index}>
                                    <input type="radio" name="shadowtype" value={index} onChange={handleRadioChange} checked={Number(shadowtype.id) === Number(searchFlowerbedInfo.shadowtype)}/>
                                    {shadowtype.name}
                                </label>   
                            ))}
                        </div>
                        <div id="groundtype" className='mb-4'>
                            <p className="font-weight-bold">Types de sol</p>
                            {searchProperties.groundtypes.map((groundtype, index) => (
                                <label key={index}>
                                    <input type="radio" name="groundtype" value={index} onChange={handleRadioChange} checked={Number(groundtype.id) === Number(searchFlowerbedInfo.groundType)}/>
                                    {groundtype.name}
                                </label>   
                            ))}
                        </div>
                        <div id="groundacidity" className='mb-4'>
                            <p className="font-weight-bold">Acidités de sol</p>
                            {searchProperties.groundacidities.map((groundacidity, index) => (
                                <label key={index}>
                                    <input type="radio" name="groundacidity" value={index} onChange={handleRadioChange} checked={Number(groundacidity.id) === Number(searchFlowerbedInfo.groundAcidity)}/>
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
            </div>
                {plantsInfo && plantsInfo !== "pas de données" && (
                    <ul>
                        {plantsInfo.map((plantInfo) => (
                        <Plantcard id={plantInfo.id} key={plantInfo.id} plant={plantInfo} onChange={hoverAddAndRemoveEvents}/>
                        ))}
                    </ul>
                    )}
                    {plantsInfo && plantsInfo === "pas de données" && (
                    <ul>
                        <p className="search_no_data">Aucune plante ne correspond à votre recherche.</p>
                    </ul>
                )}
                {HoveredElement.isHovered && (
                    <PlantHover key={HoveredElement.hoveredElement['id']} plantData={HoveredElement.hoveredElement} />
                )}
        </div>
            
        );
    }
  
    
};

export default Search;






