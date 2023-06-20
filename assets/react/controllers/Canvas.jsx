import React, { useState, useEffect } from 'react';
import { fabric } from 'fabric';


export default function () {
  const [canvas, setCanvas] = useState('');
  useEffect(() => {
    setCanvas(initCanvas());
  }, []);

  const initCanvas = () => (
    new fabric.Canvas('canvas', {
      height: 400,
      width: 900,
      backgroundColor: 'white'
    })
  )

//récupére les parterres déjà enreistrés, s'il y en a, et leur données transmisent à partir d'un champ input
  const addExistingFlowerbed = canva => {

    if (document.querySelectorAll('input.flowerbed_data')) {
      let inputs = document.querySelectorAll('input.flowerbed_data');

      //console.log(inputs);

      inputs.forEach((input) => {
        const flowerbed = new fabric.Rect({
          top: parseFloat(input.dataset.top),
          left: parseFloat(input.dataset.left),
          height: parseFloat(input.dataset.height),
          width: parseFloat(input.dataset.width),
          fill: input.dataset.fill,
          stroke: input.dataset.stroke,
          scaleX: parseFloat(input.dataset.scalex),
          scaleY: parseFloat(input.dataset.scaley),
          angle: parseFloat(input.dataset.flipangle),
        });

        flowerbed.set("shadowType", input.dataset.shadowtype);
        flowerbed.set("groundType", input.dataset.groundtype);
        flowerbed.set("groundAcidity", input.dataset.groundacidity);
        
        //les ajouter au canvas
        canva.add(flowerbed);
        canva.renderAll();
      })
    };

    //zoom / dézoom
    canva.on('mouse:wheel', function(opt) {
      let delta = opt.e.deltaY;
      let zoom = canvas.getZoom();
      zoom *= 0.999 ** delta;
      if (zoom > 20) zoom = 20;
      if (zoom < 0.01) zoom = 0.01;
      canvas.zoomToPoint({ x: opt.e.offsetX, y: opt.e.offsetY }, zoom);
      opt.e.preventDefault();
      opt.e.stopPropagation();
    });

    let isPanning = false;
  let lastPosX = 0;
  let lastPosY = 0;

  //déplacement du canvas
  canvas.on('mouse:down', (event) => {
    if (canvas.getActiveObject()) {
      document.querySelector('#flowerbed-property').style.visibility = "visible";
    } else {
      document.querySelector('#flowerbed-property').style.visibility = "hidden";
    }

      const pointer = canvas.getPointer(event.e);
      lastPosX = pointer.x;
      lastPosY = pointer.y;
      isPanning = true;
  });

  canvas.on('mouse:move', (event) => {
      if (!isPanning) return;
      if (canvas.getActiveObject() || event.e.ctrlKey) {
        // Si un élément est sélectionné ou si la touche Ctrl est enfoncée,
        // on n'active pas le déplacement du canvas
        return;
      }
      if (canvas.selection) {
        canvas.selection = false;
        canvas.discardActiveObject();
      }
      const pointer = canvas.getPointer(event.e);
      const deltaX = pointer.x - lastPosX;
      const deltaY = pointer.y - lastPosY;
      canvas.relativePan(new fabric.Point(deltaX, deltaY));
      lastPosX = pointer.x;
      lastPosY = pointer.y;
  });

  canvas.on('mouse:up', () => {
    isPanning = false;
  });

    
}

  //transformation de l'état du canvas en rajoutant les parterres déjà enregistrés
  useEffect(() => {
    if (canvas) {
      addExistingFlowerbed(canvas);
      createFlowerbedProperties();
    }
  }, [canvas]);


  //ajoute un rectangle basique au canvas
  const addRect = canvi => {

    //si la vue est en shadowtype l'objet aura la propriété shadowtype true
    let shadowType = 0;
    if (shadowType) {
      shadowType = 1;
    }
    const rect = new fabric.Rect({
      height: 280,
      width: 200,
      fill: 'white',
      stroke: 'purple',
      shadowtype: shadowType
      
    });
    canvi.add(rect);
    canvi.renderAll();
 
  }

  //supprime l'element selectionner dans le canvas
  const removeRect = canva => {
    const selectedObjects = canvas.getActiveObjects();

    selectedObjects.forEach((object) => {
      canvas.remove(object);
    });
    canvas.discardActiveObject();
    canvas.renderAll();
  }

  const addCustomProperty = canva => {
    const selectedObjects = canvas.getActiveObjects();
    selectedObjects.forEach((object) => {

      if (document.querySelector("#flowerbed_title").value) {
        object.set('flowerbedTitle', document.querySelector("#flowerbed_title").value);
      }

      if (document.querySelector("#groundType").value) {
        object.set('groundType', parseInt(document.querySelector("#groundType").value));
      }
      
      if (document.querySelector("#groundAcidity").value) {
        object.set('groundAcidity', parseInt(document.querySelector("#groundAcidity").value));
      }

      

      getFlowerbedProperties().then((data) => {
        data.shadowtypes.forEach((property) => {
          if (object.get('shadowType') == property.id) {
            object.set('fill', property.color);
            //object.set('opacity', property.color_opacity);
          }
        /*if (document.querySelector("#groundType").value) {
          data.groundtypes.forEach((property) => {
            if (object.get('groundType') == property.id) {
              let url = property.image;

              fabric.Image.fromURL(groundTypesUrl + url, function(img) {
                // Réglage de l'image en tant qu'arrière-plan de la forme
                object.set('fill', new fabric.Pattern({
                  source: img.getElement(),
                  repeat: 'no-repeat'
                }));
                canvas.renderAll();
              });
            }
            
          })
        }*/
      })


        canvas.renderAll();
      })

    });
  }

  //enregistre les éléments du canvas
  const save = (canve) => {

    let objects = canve.getObjects();

    let data = [];
    console.log(objects);

    objects.forEach((object) => {
      console.log(object);
      data.push({
        //title: object.flowerbedTitle, 
        formtype: object.type, 
        top: object.top, 
        left: object.left, 
        width: object.width, 
        height: object.height, 
        ray: object.ray, 
        scalex: object.scaleX, 
        scaley: object.scaleY, 
        fill: object.fill, 
        opacity: object.opacity, 
        stroke: object.stroke, 
        flipangle: object.angle,
        shadowtype: object.shadowtype,
        groundtype: object.groundType,
        groundacidity: object.groundAcidity,
      });
    })

    console.log(data);

    var url = 'http://localhost:8000/flowerbed/save/40'; // TODO : mettre un chemin relatif

    fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json;charset=utf-8'
      },
      body: JSON.stringify(data)
    })
    .then((resp) => resp.text())
		.then(function(data) {
			if (data) {
        let save_dutton = document.querySelector('#save_button');
        let span = document.createElement('span');
        span.textContent = data;
			  save_dutton.insertAdjacentElement("afterend", span)
			} else {
			  console.log('pas de données');
			}
		})
		.catch(function(error) {
			console.log(error);
		})
    
  }

  const createFlowerbedProperties = () => {
    
      getFlowerbedProperties().then((data) => {
        data.shadowtypes.forEach((property) => {
          let option_shadowtype = document.createElement('option');
          option_shadowtype.id = property.id;
          option_shadowtype.value = property.id;
          option_shadowtype.name = property.name;
          option_shadowtype.textContent = property.name;
          document.querySelector('select#shadow').appendChild(option_shadowtype);
        })

        data.groundtypes.forEach((property) => {
          let option_groundtype = document.createElement('option');
          option_groundtype.id = property.id;
          option_groundtype.value = property.id;
          option_groundtype.name = property.name;
          option_groundtype.textContent = property.name;
          document.querySelector('select#groundType').appendChild(option_groundtype);
      
        })

        data.groundacidities.forEach((property) => {
          let option_groundacidity = document.createElement('option');
          option_groundacidity.id = property.id;
          option_groundacidity.value = property.id;
          option_groundacidity.name = property.name;
          option_groundacidity.textContent = property.name;
          document.querySelector('select#groundAcidity').appendChild(option_groundacidity);
        })
      })

        
      

  }

  const getFlowerbedProperties = () => {
    var url = 'http://localhost:8000/flowerbed/properties';
  
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


  return(
    <div class="canvas-container">
      <button class="btn btn-primary" onClick={() => addRect(canvas)}>Rectangle</button>
      <button class="btn btn-primary" onClick={() => removeRect(canvas)}>Suppprimer la selection</button>
      <button class="btn btn-primary" id="save_button" onClick={() => save(canvas)}>Sauvegarder</button>
      
     <br/><br/>
     <canvas id="canvas" />
     <br/><br/>

    <div id="flowerbed-property">
      <select id="shadow" defaultValue="1">
        <option value="null">Aucune</option>
      </select>
      <select id="groundType" defaultValue="1">
        <option value="null">Aucune</option>
      </select>
      <select id="groundAcidity" defaultValue="1">
        <option value="null">Aucune</option>
      </select>
      <input name="flowerbed_title" id="flowerbed_title" placeholder='Nom du parterre'/>
      <button class="btn btn-primary" onClick={() => addCustomProperty(canvas)}>Enregistrer</button>
     </div>

  </div>
  );
}