import React from 'react';
import Plantcard from './Plantcard';

const Search = ({ searchProperties, plants }) => {

    if (plants) {

        const plantsInfo = JSON.parse(plants);

        const handleRadioChange = (event) => {
            //code a effectué au changement du champ radio selectionné
          };

        return (
            <div>
                <form>
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
                    <button type="submit">Lancer une nouvelle recherche</button>
                </form>
                <ul>
                    {plantsInfo.map((plantInfo) => (
                        <Plantcard id={plantInfo.id} key={plantInfo.id} plant={plantInfo}/>
                    ))}
                </ul>
            </div>
        );
    }
  
    
};

export default Search;






