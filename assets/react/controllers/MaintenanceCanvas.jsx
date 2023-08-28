import React, { useState, useEffect } from 'react';
import { fabric } from 'fabric';
import Search from './Search';

export default function () {
  const [canvas, setCanvas] = useState(null); //TODO voir si null mieux que string vide
  const [shapes, setShapes] = useState({
    new: null,
    type: '',
    y: 0,
    x: 0,
    width: 0,
    height: 0,
    m2: 0,
    radius: 0,
    plant: null
  });
  const [message, setMessage] = useState('');
  const [flowerbedProperties, setFlowerbedProperties] = useState(null);
  const STATE_IDLE = 'idle';
  const STATE_PANNING = 'panning';
  let initialCoords;

  let shapeType;
  let shape;



  useEffect(() => {
    //pour info, echelle des formes du canva = 1/50 par rapport à la réalité
    setCanvas(initCanvas());
    getFlowerbedProperties().then((data) => {
      setFlowerbedProperties(data);
    });

    


  }, []);

  //transformation de l'état du canvas en rajoutant les parterres déjà enregistrés
  useEffect(() => {
    if (canvas) {
      addExistingFlowerbed(canvas);

      // Ajouter un écouteur d'événement pour l'événement keydown
      document.addEventListener('keydown', (event) => handleCtrlKey(event, canvas));

      // Ajouter un écouteur d'événement pour l'événement keyup
      document.addEventListener('keyup', (event) => handleCtrlKey(event, canvas));

      plantHoverDisplay(canvas);

      let objects = canvas.getObjects();
      objects.forEach(object => {
        object.selectable = false;
        if (object.kind == 'plant') {
            if (object.plant.maintenanceAction.id == 1) {
                if (object.plant.maintenanceAction.level > 0) {
                    var maintenanceActionCircle = new fabric.Circle({
                        left: object.left + object.width / 1.5,  // Ajoutez un décalage de 5 pixels à gauche du premier cercle
                        top: object.top + object.width / 1.5,    // Ajoutez un décalage de 5 pixels vers le haut du premier cercle
                        radius: object.radius / 2,
                        fill: 'green'
                      });

                      switch (object.plant.maintenanceAction.level) {
                        case 1:
                            maintenanceActionCircle.set("fill", 'green');
                          break;
                        case 2:
                            maintenanceActionCircle.set("fill", 'yellow');
                          break;
                        case 3:
                            maintenanceActionCircle.set("fill", 'orange');
                          break;
                        case 4:
                            maintenanceActionCircle.set("fill", 'red');
                          break;
                      }

                      canvas.add(maintenanceActionCircle);

                }
            }
            object.on('mousedown', function(event) {
                  document.querySelector('#plant_maintenance_action').style.visibility = 'visible';
                  console.log(object);
                  document.querySelector('#maintenanceActionDone').setdata.plantId = object.plant.plant.id;
                  document.querySelector('#maintenanceActionDone').setdata.maintenanceActionId = object.plant.maintenanceAction.id;
              });
        }
      })

      console.log(objects);




    }
  }, [canvas]);
  

  const initCanvas = () => (
    new fabric.Canvas('canvas', {
      height: 400,
      width: 700,
      backgroundColor: 'white'
    })
  )

//récupére les parterres déjà enreistrés, s'il y en a, et leur données transmisent à partir d'un champ input
  const addExistingFlowerbed = canva => {
    if (document.querySelectorAll('input.flowerbed_data')) {

        let inputs = document.querySelectorAll('input.flowerbed_data');
        let flowerbedPromises = [];

        inputs.forEach((input) => {
            let flowerbed_datas = JSON.parse(input.value);
            let flowerbed;

            if (flowerbed_datas.kind !== 'shadow') {
                if (flowerbed_datas.formtype === "rect") {
                    flowerbed = new fabric.Rect({
                    kind: flowerbed_datas.kind,
                    top: parseFloat(flowerbed_datas.top),
                    left: parseFloat(flowerbed_datas.left),
                    height: parseFloat(flowerbed_datas.height),
                    width: parseFloat(flowerbed_datas.width),
                    fill: flowerbed_datas.fill,
                    opacity: parseFloat(flowerbed_datas.fillOpacity),
                    stroke: flowerbed_datas.stroke,
                    scaleX: parseFloat(flowerbed_datas.scalex),
                    scaleY: parseFloat(flowerbed_datas.scaley),
                    angle: parseFloat(flowerbed_datas.flipangle),
                    shadowtype: parseInt(flowerbed_datas.shadowtype),
                    isGardenLimit: flowerbed_datas.isGardenLimit,
                    });
                } else {
                    flowerbed = new fabric.Circle({
                    kind: flowerbed_datas.kind,
                    top: parseFloat(flowerbed_datas.top),
                    left: parseFloat(flowerbed_datas.left),
                    radius: flowerbed_datas.ray,
                    fill: flowerbed_datas.fill,
                    opacity: parseFloat(flowerbed_datas.fillOpacity),
                    stroke: flowerbed_datas.stroke,
                    scaleX: parseFloat(flowerbed_datas.scalex),
                    scaleY: parseFloat(flowerbed_datas.scaley),
                    angle: parseFloat(flowerbed_datas.flipangle),
                    shadowtype: parseInt(flowerbed_datas.shadowtype),
                    isGardenLimit: flowerbed_datas.isGardenLimit,
                    });
                }
            
                flowerbed.set("groundType", flowerbed_datas.groundtype);
                flowerbed.set("groundAcidity", flowerbed_datas.groundacidity);
                if (flowerbed_datas.shadowtype == 0) {
                flowerbedPromises.push(
                    getFlowerbedProperties().then((data) => {
                        data.groundtypes.forEach((property) => {
                            if (parseInt(flowerbed_datas.groundtype) == property.id) {
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
            

                if (flowerbed_datas.kind == "plant") {
                flowerbed.set("plant", flowerbed_datas.plant);
                flowerbed.set("fill", "#" + flowerbed_datas.plant.plant.color.hexa_code);
                }
                
                //les ajouter au canvas
                canva.add(flowerbed);

            }
            
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

}

  const plantHoverDisplay = (canvo) => {
    let objects = canvo.getObjects();

    const hoverElement = document.querySelector('#canvasPlantHover');
    let isHovering = false;

    canvo.on("mouse:move", function(event) {
        const mouse = canvo.getPointer(event.e);

        let foundHoverable = false;

        for (const obj of objects) {

            if (obj.kind === "plant") {
                const distance = Math.sqrt((mouse.x - obj.left - obj.radius) ** 2 + (mouse.y - obj.top - obj.radius) ** 2);

                if (distance <= obj.radius) {
                    let plant = obj.plant.plant || obj.plant; // Utilisation de l'opérateur logique "OU" pour choisir la bonne propriété
                    let maintenanceAction = obj.plant.maintenanceAction 
                    foundHoverable = true;
                    isHovering = true;
                    if (isHovering) {
                        displayPlantInfo(plant, maintenanceAction);
                    }
                }
            }
        }

        if (!foundHoverable) {
            hideHoverDisplay();
        }
    });

    const displayPlantInfo = (plant, maintenanceAction) => {

        hoverElement.style.visibility = "visible";
        hoverElement.innerHTML = `
            <p>Nom: ${plant.name}</p>
            <p>Description: ${plant.description}</p>
            <p>Planté le : ${plant.planting_date.date.substring(0, 10)}</p>
        `;

        if (maintenanceAction.id == 1 && maintenanceAction.level > 0) {
            hoverElement.innerHTML += `
            <p><strong>Actions à effectuer : </strong></p>
            <p><img src="/media/maintenanceActions/watering.png" width="12" /> : ${maintenanceAction.waterQty.toFixed(2)} L</p>
        `;
        }
        isHovering = true;
    };

    const hideHoverDisplay = () => {
        hoverElement.innerHTML = "";
        hoverElement.style.visibility = "hidden";
        isHovering = false;
    };


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


  function fabricObjectToSimpleArray(obj, simplifyFabricObj) {
    // Copier les propriétés sérialisables dans le nouvel objet
    if (obj['isGardenLimit'] == 0) {
      if (obj['shadowtype'] != 0) {
        simplifyFabricObj['shadowtype'] = obj['shadowtype'];
      }
      if (obj['groundType'] != '' || obj['groundType'] != NaN) {
        simplifyFabricObj['groundType'] = obj['groundType'];
      }
      if (obj['groundAcidity'] != '' || obj['groundAcidity'] != NaN) {
        simplifyFabricObj['groundAcidity'] = obj['groundAcidity'];
      }
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
    
    <div className="canvas-container d-flex">
      <div className="col-8">
        <div id="canvasPlantHover"></div>

        <p>{message}</p>

        <canvas id="canvas" />

        <div id="plant_maintenance_action" >
            <label htmlFor="checkbox">Fait : </label>
            <input type="checkbox" id="checkbox" name="checkbox" />
            <button id="" className="btn btn-primary">Valider</button>
        </div>

      </div>

  </div>

  

  
  );
}