import React, { useState, useEffect } from 'react';
import { fabric } from 'fabric';


export default function () {
  const [canvas, setCanvas] = useState('');
  const [shadowFilter, setShadowFilter] = useState(0);
  useEffect(() => {
    setCanvas(initCanvas());
  }, []);

  useEffect(() => {
    if (canvas) {
      let objects = canvas.getObjects();
      objects.forEach(object => {
        if (object.shadowtype > 0) {
          object.visible = shadowFilter;
          object.selectable = shadowFilter;
          canvas.bringToFront(object);
        } else {
          object.selectable = !shadowFilter;
        }
      })
      canvas.renderAll();
    }
  }, [shadowFilter, canvas]);

  //transformation de l'état du canvas en rajoutant les parterres déjà enregistrés
  useEffect(() => {
    if (canvas) {
      addExistingFlowerbed(canvas);
      createFlowerbedProperties();
    }
  }, [canvas]);


  const initCanvas = () => (
    new fabric.Canvas('canvas', {
      height: 400,
      width: 900,
      backgroundColor: 'white'
    })
  )

  const handleShadowFilterEvent = () => {
    // Mettre à jour la valeur de la variable globale en fonction de l'événement
    if (shadowFilter === 1) {
      setShadowFilter(0);
    } else {
      setShadowFilter(1);
    }

  };

//récupére les parterres déjà enreistrés, s'il y en a, et leur données transmisent à partir d'un champ input
  const addExistingFlowerbed = canva => {
    if (document.querySelectorAll('input.flowerbed_data')) {
      let inputs = document.querySelectorAll('input.flowerbed_data');
      let flowerbedPromises = [];
      
      inputs.forEach((input) => {
        let flowerbed;
        let visible = input.dataset.shadowtype > "0" ? false : true;
        let selectable = input.dataset.isgardenlimit == "1" ? false : true;

        if (input.dataset.formtype === "rect") {
          flowerbed = new fabric.Rect({
            top: parseFloat(input.dataset.top),
            left: parseFloat(input.dataset.left),
            height: parseFloat(input.dataset.height),
            width: parseFloat(input.dataset.width),
            fill: input.dataset.fill,
            opacity: parseFloat(input.dataset.fillopacity),
            stroke: input.dataset.stroke,
            scaleX: parseFloat(input.dataset.scalex),
            scaleY: parseFloat(input.dataset.scaley),
            angle: parseFloat(input.dataset.flipangle),
            shadowtype: parseInt(input.dataset.shadowtype),
            isGardenLimit: input.dataset.isgardenlimit,
            visible: visible,
            selectable: selectable
          });
        } else {
            flowerbed = new fabric.Circle({
            top: parseFloat(input.dataset.top),
            left: parseFloat(input.dataset.left),
            radius: input.dataset.ray,
            fill: input.dataset.fill,
            opacity: parseFloat(input.dataset.fillopacity),
            stroke: input.dataset.stroke,
            scaleX: parseFloat(input.dataset.scalex),
            scaleY: parseFloat(input.dataset.scaley),
            angle: parseFloat(input.dataset.flipangle),
            shadowtype: parseInt(input.dataset.shadowtype),
            visible: visible
          });
        }
        flowerbed.set("groundType", input.dataset.groundtype);
        flowerbed.set("groundAcidity", input.dataset.groundacidity);
        if (input.dataset.shadowtype == 0) {
          flowerbedPromises.push(
              getFlowerbedProperties().then((data) => {
                  data.groundtypes.forEach((property) => {
                      if (parseInt(input.dataset.groundtype) == property.id) {
                          let url = property.image;
                          return new Promise((resolve, reject) => {
                              fabric.Image.fromURL(groundTypesUrl + url, function(img) {
                                  flowerbed.set('fill', new fabric.Pattern({
                                      source: img.getElement(),
                                      repeat: 'no-repeat'
                                  }));
                                  canva.requestRenderAll();
                                  resolve();
                              });
                          });
                      }
                  });
              })
          );
        }
        
        //les ajouter au canvas
        canva.add(flowerbed);
        
      })

      Promise.all(flowerbedPromises).then(() => {
        canva.renderAll();
    });
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

    let activeObject = canvas.getActiveObject();
    console.log(activeObject);
    if (activeObject) {
      if (activeObject.shadowtype > 0) {
        document.querySelector('#flowerbed-shadow-property').style.visibility = "visible";
        document.querySelector('select#shadowType').value = activeObject.shadowtype;
      } else {
        document.querySelector('#flowerbed-property').style.visibility = "visible";
        document.querySelector('select#groundType').value = activeObject.groundType == undefined ? null : parseInt(activeObject.groundType);
        document.querySelector('select#groundAcidity').value = activeObject.groundAcidity == undefined ? null : parseInt(activeObject.groundAcidity);
        //document.querySelector('select#flowerbed_title').value = activeObject.title == undefined ? null : activeObject.title;
      }
    } else {
        document.querySelector('#flowerbed-shadow-property').style.visibility = "hidden";
        document.querySelector('#flowerbed-property').style.visibility = "hidden";
    }

    //TODO mettre les valeurs dans champs en fonction de ceux de l'element selectionné
  
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




  //ajoute un rectangle basique au canvas
  const addRect = canvi => {

    //si la vue est en shadowtype l'objet aura la propriété shadowtype true
    let shadowType = 0;
    let fill;
    let opacity;
    let stroke;

    if (shadowFilter) {
      shadowType = 1;
      fill = "grey";
      opacity = 0.5;
      stroke = 'transparent';
    } else {
      fill = 'white';
      opacity = 1;
      stroke = 'black';
    }
    const rect = new fabric.Rect({
      height: 280,
      width: 200,
      fill: fill,
      stroke: stroke,
      opacity: opacity,
      shadowtype: shadowType,
      isGardenLimit: 0
      
    });
    canvi.add(rect);
    canvi.renderAll();
    console.log(rect);
 
  }

  //ajoute un rectangle basique au canvas
  const addCircle = canvi => {

    //si la vue est en shadowtype l'objet aura la propriété shadowtype true
    let shadowType = 0;
    let fill;
    let opacity;
    let stroke;

    if (shadowFilter) {
      shadowType = 1;
      fill = "grey";
      opacity = 0.5;
      stroke = 'transparent';
    } else {
      fill = 'white';
      opacity = 1;
      stroke = 'black';
    }
    const circle = new fabric.Circle({
      radius: 50,
      fill: fill,
      stroke: stroke,
      opacity: opacity,
      shadowtype: shadowType,
      isGardenLimit: 0
    });
    canvi.add(circle);
    canvi.renderAll();
  }

  //ajoute un rectangle basique au canvas
  const addGardenLimit = canvi => {
    
    let canvasObjects = canvi.getObjects();
    let gardenLimitButton = document.querySelector('button#gardenLimit');
    let rect;
    let gardenLimitAlreadyExist;
  
    if (gardenLimitButton.textContent === "Modifier la limite du jardin") {
      document.querySelector('button#gardenLimit').textContent = "Valider la limite du jardin";
      canvasObjects.forEach((object) => {
        if (object.isGardenLimit == "1") { //TODO isGardenLimit à créer dans les objets forme
          gardenLimitAlreadyExist = true;
          object.set("selectable", true);
          canvi.setActiveObject(object);
          canvi.renderAll();
        } else {
          object.set("selectable", false);
        }
      })

      if (!gardenLimitAlreadyExist) {
        rect = new fabric.Rect({
          width: 200,
          height: 100,
          fill: 'transparent',
          stroke: 'green',
          isGardenLimit: 1,
          shadowtype: 0,
        });

        canvi.add(rect);
        rect.sendToBack();
        canvi.renderAll();
      }

    } else {
      document.querySelector('button#gardenLimit').textContent = "Modifier la limite du jardin";
      canvasObjects.forEach((object) => {
        if (object.isGardenLimit == "1") {
          object.set("selectable", false);
          canvi.discardActiveObject();
          canvi.renderAll();
        } else {
          object.set("selectable", true);
        }
      })
    }


    
 
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

      if (object.shadowtype > 0) {
        if (document.querySelector("#shadowType").value) {
          object.set('shadowtype', document.querySelector("#shadowType").value);
        }

      } else {
        if (document.querySelector("#flowerbed_title").value) {
          object.set('flowerbedTitle', document.querySelector("#flowerbed_title").value);
        }
  
        if (document.querySelector("#groundType").value) {
          object.set('groundType', parseInt(document.querySelector("#groundType").value));
        }
        
        if (document.querySelector("#groundAcidity").value) {
          object.set('groundAcidity', parseInt(document.querySelector("#groundAcidity").value));
        }
      }
    
      console.log(object);
      

      getFlowerbedProperties().then((data) => {
        
        data.shadowtypes.forEach((property) => {
          if (object.shadowtype > 0) {
            if (object.get('shadowtype') == property.id) {
              object.set('fill', property.color);
            }
          } else {
            if (document.querySelector("#groundType").value) {
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
            }
          }
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

      if (object.shadowtype == 0 && object.groundType != undefined) {
        object.fill = "transparent";
      }

      object.radius = object.type == "circle" ? object.radius : 0;
      
      data.push({
        //title: object.flowerbedTitle, 
        formtype: object.type, 
        top: object.top, 
        left: object.left, 
        width: object.width, 
        height: object.height, 
        ray: object.radius, 
        scalex: object.scaleX, 
        scaley: object.scaleY, 
        fill: object.fill, 
        opacity: object.opacity, 
        stroke: object.stroke, 
        flipangle: object.angle,
        shadowtype: object.shadowtype,
        groundtype: object.groundType,
        groundacidity: object.groundAcidity,
        isGardenLimit: object.isGardenLimit
      });
    })

    console.log(data);

    let garden_id = document.querySelector('input#garden_id').value;

    var url = 'http://localhost:8000/flowerbed/save/' + garden_id; // TODO : mettre un chemin relatif

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
          document.querySelector('select#shadowType').appendChild(option_shadowtype);
      
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
      <button class="btn btn-primary" onClick={() => addRect(canvas)}>□</button>
      <button class="btn btn-primary" onClick={() => addCircle(canvas)}>º</button>
      <button class="btn btn-primary" id="gardenLimit" onClick={() => addGardenLimit(canvas)}>Modifier la limite du jardin</button>
      <button class="btn btn-primary" onClick={() => removeRect(canvas)}>Suppprimer la selection</button>
      <button class="btn btn-primary" id="save_button" onClick={() => save(canvas)}>Sauvegarder</button>
      
     <br/><br/>
     <div class="d-flex">

      <div class="form-check form-switch me-4">
        <input onClick={() => handleShadowFilterEvent()} class="form-check-input off" type="checkbox" role="switch" id="flexSwitchCheckDefault" />
        <label class="form-check-label" for="flexSwitchCheckDefault">Ombrages</label>
      </div>

      <canvas id="canvas" />

     </div>
      
     <br/><br/>

    <div id="flowerbed-property">
      <select id="groundType" defaultValue="1">
        <option value="null">Aucune</option>
      </select>
      <select id="groundAcidity" defaultValue="1">
        <option value="null">Aucune</option>
      </select>
      <input name="flowerbed_title" id="flowerbed_title" placeholder='Nom du parterre'/>
      <button class="btn btn-primary" onClick={() => addCustomProperty(canvas)}>Enregistrer</button>
     </div>


     <div id="flowerbed-shadow-property">
      <select id="shadowType" defaultValue="1">
      </select>
      <button class="btn btn-primary" onClick={() => addCustomProperty(canvas)}>Enregistrer</button>
     </div>
  </div>

  
  );
}