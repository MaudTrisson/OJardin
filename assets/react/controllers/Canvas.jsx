import React, { useState, useEffect } from 'react';
import { fabric } from 'fabric';


export default function () {
  const [canvas, setCanvas] = useState('');
  const [shadowFilter, setShadowFilter] = useState(0);
  const [message, setMessage] = useState('');
  const STATE_IDLE = 'idle';
  const STATE_PANNING = 'panning';
  let initialCoords;

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

      // Ajouter un écouteur d'événement pour l'événement keydown
      document.addEventListener('keydown', (event) => handleCtrlKey(event, canvas));

    // Ajouter un écouteur d'événement pour l'événement keyup
    document.addEventListener('keyup', (event) => handleCtrlKey(event, canvas));

  
    const targetButton = document.getElementById('addRect');
    let shape = document.getElementById('shape');
    let isMouseDown = false;
    let isButtonClicked = false;
    
    var mouseMoveHandler = function(event) {
      if (isMouseDown && isButtonClicked) {
        shape.style.left = (event.clientX - shape.offsetWidth / 2) + 'px';
        shape.style.top = (event.clientY - shape.offsetHeight / 2) + 'px';
      }
    };
    
    // Écouteur d'événement pour le clic initial
    targetButton.addEventListener('mousedown', function(event) {
      isMouseDown = true;
      isButtonClicked = true;
      
      let zoom = canvas.getZoom();
      let computedStyle = window.getComputedStyle(shape);

      let currentWidth = parseFloat(computedStyle.width);
      shape.style.width = (currentWidth * zoom) + 'px';

      let currentHeight = parseFloat(computedStyle.height);
      shape.style.height = (currentHeight * zoom) + 'px';

      document.addEventListener('mousemove', mouseMoveHandler);
      
      shape.style.display = 'block';
      shape.style.left = (event.clientX - shape.offsetWidth / 2) + 'px';
      shape.style.top = (event.clientY - shape.offsetHeight / 2) + 'px';
    });
    
    // Écouteur d'événement pour le relâchement du clic
    document.addEventListener('mouseup', function(event) {
      var canvaEl = document.getElementById('canvas');
      var canvasRect = canvaEl.getBoundingClientRect();

      // Vérifier si les coordonnées de la souris se trouvent à l'intérieur des limites du canvas
      if (event.clientX >= canvasRect.left && event.clientX <= canvasRect.right && event.clientY >= canvasRect.top && event.clientY <= canvasRect.bottom) {
        if (isMouseDown && isButtonClicked) {
          const pointer = canvas.getPointer(event);
          addRect(pointer.x - 25, pointer.y - 25);
          
          canvas.off('mouse:move');
        }
      } 
      shape.style.display = 'none';
      shape.style.width = '50px';
      shape.style.height = '50px';
      isMouseDown = false; // Réinitialiser la variable après le relâchement du clic
      isButtonClicked = false; // Réinitialiser la variable après le relâchement du clic
    });





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
            isGardenLimit: input.dataset.isgardenlimit,
            visible: visible,
            selectable: selectable
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
      var delta = opt.e.deltaY;
      var zoom = canvas.getZoom();
      zoom *= 0.999 ** delta;
      if (zoom > 20) zoom = 20;
      if (zoom < 0.01) zoom = 0.01;
      canvas.zoomToPoint({ x: opt.e.offsetX, y: opt.e.offsetY }, zoom);
      opt.e.preventDefault();
      opt.e.stopPropagation();
    });



  //déplacement du canvas
  canvas.on('mouse:down', (event) => {
    const pointer = canvas.getPointer(event.e);
    const objects = canvas.getObjects();

    for (let i = objects.length - 1; i >= 0; i--) {
      const activeObject = objects[i];
      if (activeObject.containsPoint(pointer)) {
        // La première forme cliquée a été trouvée
        // Effectuez les actions souhaitées sur la première forme trouvée
        console.log(activeObject);
        if (activeObject && activeObject.isGardenLimit == 0) {

          initialCoords = activeObject.getBoundingRect();
       
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


        break; // Sortez de la boucle pour ignorer les formes en dessous
      }
    }

    //TODO mettre un clone controleur



  });

  /*canvas.on('selection:created', function(event) {
    var selectedObject = event.target;
    // Faites quelque chose avec l'objet sélectionné...
    console.log('Élément sélectionné :', selectedObject);
  });*/

  canvas.on('mouse:up', (event) => {

      const activeObject = canvas.getActiveObject();
        // La première forme cliquée a été trouvée
        console.log(activeObject);

        if (activeObject && activeObject.isGardenLimit == 0) {

          activeObject.setCoords();

          if (checkOverlap(activeObject)) {
            activeObject.set({
              left: initialCoords.left,
              top: initialCoords.top
            });
      
            activeObject.setCoords();
            canvas.renderAll();
            setMessage('les formes ne peuvent pas se superposer');
          }
      
          if (activeObject.type == "rect") {

            const scaleX = activeObject.scaleX || 1;
            const scaleY = activeObject.scaleY || 1;

            const newWidth = activeObject.width * scaleX;
            const newHeight = activeObject.height * scaleY;

            activeObject.set({
              width: newWidth,
              height: newHeight,
              scaleX: 1,
              scaleY: 1
            });

            activeObject.setCoords();
            canvas.renderAll();
          }
        }

    
  })




 /*canvas.on('mouse:move', (event) => {

     
  });*/




  

    
}




  //ajoute un rectangle basique au canvas
  const addRect = (left, top) => {

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
    const rectangle = new fabric.Rect({
      left: left,
      top: top,
      height: 50,
      width: 50,
      fill: fill,
      stroke: stroke,
      opacity: opacity,
      shadowtype: shadowType,
      isGardenLimit: 0,
      
    });
    canvas.add(rectangle);
    canvas.renderAll();
    return rectangle;
 
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
          setMessage(data);
			} else {
			  setMessage('pas de données.');
			}
		})
		.catch(function(error) {
			setMessage(error);
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

  function checkOverlap(shape) {
    var objects = canvas.getObjects();
    
    for (var i = 0; i < objects.length; i++) {
      if (objects[i] !== shape && ((objects[i].shadowtype === shape.shadowtype || (objects[i].shadowtype > 0 && shape.shadowtype > 0)) && (objects[i].isGardenLimit == 0 && shape.isGardenLimit == 0)) && isOverlap(objects[i], shape)) {
        return true;
      }
    }
    return false;
  }
  
  function isOverlap(object1, object2) {

    if (object1.type === "circle" && object2.type === "circle") {
      // Récupérer les coordonnées des centres des cercles
      var center1 = object1.getCenterPoint();
      var center2 = object2.getCenterPoint();

      // Calculer les rayons échelonnés
      var scaledRadius1 = object1.radius * Math.max(object1.scaleX, object1.scaleY);
      var scaledRadius2 = object2.radius * Math.max(object2.scaleX, object2.scaleY);

      // Calculer la distance entre les centres des cercles
      var dx = center2.x - center1.x;
      var dy = center2.y - center1.y;
      var distance = Math.sqrt(dx * dx + dy * dy);
      return (distance <= scaledRadius1 + scaledRadius2);
    }

    if (object1.type == "circle") {

      // Calculer les coordonnées du centre du cercle
      var circleCenterX = object1.left + (((object1.radius * object1.scaleX) + (object1.radius * object1.scaleY)) / 2);
      var circleCenterY = object1.top + (((object1.radius * object1.scaleX) + (object1.radius * object1.scaleY)) / 2);

      // Trouver le point le plus proche du cercle à l'intérieur du rectangle
      var closestX = Math.max(object2.left, Math.min(circleCenterX, object2.left + object2.width));
      var closestY = Math.max(object2.top, Math.min(circleCenterY, object2.top + object2.height));

      // Calculer la distance entre le point le plus proche et le centre du cercle
      var distanceX = closestX - circleCenterX;
      var distanceY = closestY - circleCenterY;
      var distanceSquared = distanceX * distanceX + distanceY * distanceY;

      // Vérifier si la distance est inférieure au rayon du cercle
      return distanceSquared <= ((((object1.radius * object1.scaleX) + (object1.radius * object1.scaleY)) / 2) * (((object1.radius * object1.scaleX) + (object1.radius * object1.scaleY)) / 2));
    }

    if (object2.type == "circle") {

      // Calculer les coordonnées du centre du cercle
      var circleCenterX = object2.left + (((object2.radius * object2.scaleX) + (object2.radius * object2.scaleY)) / 2);
      var circleCenterY = object2.top + (((object2.radius * object2.scaleX) + (object2.radius * object2.scaleY)) / 2);

      // Trouver le point le plus proche du cercle à l'intérieur du rectangle
      var closestX = Math.max(object1.left, Math.min(circleCenterX, object1.left + object1.width));
      var closestY = Math.max(object1.top, Math.min(circleCenterY, object1.top + object1.height));

      // Calculer la distance entre le point le plus proche et le centre du cercle
      var distanceX = closestX - circleCenterX;
      var distanceY = closestY - circleCenterY;
      var distanceSquared = distanceX * distanceX + distanceY * distanceY;

      // Vérifier si la distance est inférieure au rayon du cercle
      return distanceSquared <= ((((object2.radius * object2.scaleX) + (object2.radius * object2.scaleY)) / 2) * (((object2.radius * object2.scaleX) + (object2.radius * object2.scaleY)) / 2));
    }
    if (object1.type != "circle" && object2.type != "circle") {
      return (
        object1.left < object2.left + object2.width &&
        object1.left + object1.width > object2.left &&
        object1.top < object2.top + object2.height &&
        object1.top + object1.height > object2.top
      );
    }
    
  }

//gère les droits au panning
  const handleCtrlKey = (event, canvas) => {
    if (event.key === 'Control' || event.key === 'Meta') {
      // Votre code à exécuter lorsque la touche Ctrl est enfoncée ou relâchée
      if (event.ctrlKey || event.metaKey) {
        canvas.toggleDragMode(true);
      } else {
        canvas.toggleDragMode(false);
      }
    }
  };




fabric.Canvas.prototype.toggleDragMode = function(dragMode) {
  // Remember the previous X and Y coordinates for delta calculations
  let lastClientX;
  let lastClientY;
  // Keep track of the state
  let state = STATE_IDLE;
  // We're entering dragmode
  if (dragMode) {
    // Discard any active object
    this.discardActiveObject();
    // Set the cursor to 'move'
    this.defaultCursor = 'move';
    // Loop over all objects and disable events / selectable. We remember its value in a temp variable stored on each object
    this.forEachObject(function(object) {
      object.prevEvented = object.evented;
      object.prevSelectable = object.selectable;
      object.evented = false;
      object.selectable = false;
    });
    // Remove selection ability on the canvas
    this.selection = false;
    // When MouseUp fires, we set the state to idle
    this.on('mouse:up', function(e) {
      state = STATE_IDLE;
    });
    // When MouseDown fires, we set the state to panning
    this.on('mouse:down', (e) => {
      state = STATE_PANNING;
      lastClientX = e.e.clientX;
      lastClientY = e.e.clientY;
    });
    // When the mouse moves, and we're panning (mouse down), we continue
    this.on('mouse:move', (e) => {
      if (state === STATE_PANNING && e && e.e) {
        // let delta = new fabric.Point(e.e.movementX, e.e.movementY); // No Safari support for movementX and movementY
        // For cross-browser compatibility, I had to manually keep track of the delta

        // Calculate deltas
        let deltaX = 0;
        let deltaY = 0;
        if (lastClientX) {
          deltaX = e.e.clientX - lastClientX;
        }
        if (lastClientY) {
          deltaY = e.e.clientY - lastClientY;
        }
        // Update the last X and Y values
        lastClientX = e.e.clientX;
        lastClientY = e.e.clientY;

        let delta = new fabric.Point(deltaX, deltaY);
        this.relativePan(delta);
        //this.trigger('moved');
      }
    });
  } else {
    // When we exit dragmode, we restore the previous values on all objects
    this.forEachObject(function(object) {
      object.evented = (object.prevEvented !== undefined) ? object.prevEvented : object.evented;
      object.selectable = (object.prevSelectable !== undefined) ? object.prevSelectable : object.selectable;
    });
    // Reset the cursor
    this.defaultCursor = 'default';
    // Remove the event listeners
    this.off('mouse:up');
    this.off('mouse:down');
    this.off('mouse:move');
    // Restore selection ability on the canvas
    this.selection = true;
  }
};






  return(
    <div class="canvas-container">
      <div id="shape"></div>
      <button id="addRect" class="btn btn-primary" onClick={() => addRect(canvas)}>□</button>
      <button class="btn btn-primary" onClick={() => addCircle(canvas)}>º</button>
      <button class="btn btn-primary" id="gardenLimit" onClick={() => addGardenLimit(canvas)}>Modifier la limite du jardin</button>
      <button class="btn btn-primary" onClick={() => removeRect(canvas)}>Suppprimer la selection</button>
      <button class="btn btn-primary" id="save_button" onClick={() => save(canvas)}>Sauvegarder</button>
      <p>{message}</p>
      
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