import React, { useState, useEffect } from 'react';
import { fabric } from 'fabric';
import Search from './Search';

export default function () {
  const [canvas, setCanvas] = useState(null); //TODO voir si null mieux que string vide
  const [shadowFilter, setShadowFilter] = useState(0);
  const [shapes, setShapes] = useState({
    new: null,
    type: '',
    y: 0,
    x: 0,
    width: 0,
    height: 0,
    m2: 0,
    radius: 0,
  });
  const [message, setMessage] = useState('');
  const [searchPlantInfo, setSearchPlantInfo] = useState(null);
  const [searchFlowerbedInfo, setsearchFlowerbedInfo] = useState(null);
  const [flowerbedProperties, setFlowerbedProperties] = useState(null);
  const STATE_IDLE = 'idle';
  const STATE_PANNING = 'panning';
  let initialCoords;

  let isMouseDown = false;
  let isButtonClicked = false;
  let shapeType;
  let shape;



  useEffect(() => {
    //pour info, echelle des formes du canva = 1/50 par rapport à la réalité
    setCanvas(initCanvas());
    getFlowerbedProperties().then((data) => {
      setFlowerbedProperties(data);
    });
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
          if (object['isGardenLimit'] == "1") {
            object.selectable = false;
          } else {
            object.selectable = !shadowFilter;
          }
        }
      })
      canvas.renderAll();
    }

    
  }, [shadowFilter, canvas]);


  useEffect(() => {
    if (canvas) {
      if (shapes.new) {
        let newShape;
        if (shapes.type == 'rectangle') {
          newShape = addRect(shapes.x, shapes.y, shadowFilter);
        } else if (shapes.type == 'circle'){
          newShape = addCircle(shapes.x, shapes.y, shadowFilter, shapes.radius);
        }
        //check if the new form add is on another same form type
        if (checkOverlap(newShape)) {
          canvas.remove(newShape);
          canvas.renderAll();
          setMessage('les formes ne peuvent pas se superposer');
        }
      }
    }
  }, [shapes, canvas]);

  useEffect(() => {
    document.querySelectorAll('.plantCard').forEach((button) => {
      button.addEventListener('mousedown', (event) => {  
        shape = document.getElementById('PlantTemp');
        
        isMouseDown = true;
        isButtonClicked = true;
        shapeType = 2;
        
        let zoom = canvas.getZoom();
  
        let currentWidth = event.target.getAttribute('data-width') ;
        shape.style.width = (currentWidth * zoom) + 'px';
        shape.style.height = (currentWidth * zoom) + 'px';
  
        shape.style.display = 'block';
        shape.style.left = (event.clientX - shape.offsetWidth / 2) + 'px';
        shape.style.top = (event.clientY - shape.offsetHeight / 2) + 'px';
        
        const mouseMoveCallback = (event) => mouseMoveHandler(event, shape);
        const mouseUpCallBack = (event) => mouseUpHandler(event, shape);

        document.addEventListener('mouseup', mouseUpCallBack);
        // Ajouter l'écouteur d'événement avec la fonction de rappel
        document.addEventListener('mousemove', mouseMoveCallback);
        });
      }); 


      var mouseMoveHandler = function(event, shape) {
        if (isMouseDown && isButtonClicked) {
          shape.style.left = (event.clientX - shape.offsetWidth / 2) + 'px';
          shape.style.top = (event.clientY - shape.offsetHeight / 2) + 'px';
        }
      };

      var mouseUpHandler = function(event, shape) {
        if (isMouseDown && isButtonClicked) {
          var canvaEl = document.getElementById('canvas');
          var canvasRect = canvaEl.getBoundingClientRect();
          
          let shapeWidth = parseInt(shape.style.width) / canvas.getZoom();
          let shapeHeight = parseInt(shape.style.height) / canvas.getZoom();

          // Vérifier si les coordonnées de la souris se trouvent à l'intérieur des limites du canvas
          if (event.clientX >= canvasRect.left && event.clientX <= canvasRect.right && event.clientY >= canvasRect.top && event.clientY <= canvasRect.bottom) {
            if (isMouseDown && isButtonClicked) {
              const pointer = canvas.getPointer(event);
              setShapes({
                ...shapes,
                new: true,
                type: 'circle',
                x: (pointer.x - shapeWidth / 2),
                y: (pointer.y - shapeHeight / 2),
                width: shapeWidth / shapeWidth,
                height: shapeHeight / shapeHeight,
                m2: (3.14 * (shapeWidth / 2) * (shapeHeight / 2)),
                radius: (shapeWidth / 2)
              });
              isButtonClicked = false;
              shape.style.display = 'none';
              canvas.off('mouse:move', mouseMoveHandler);
            }
      };
    }}
   
  }, [searchPlantInfo]);
      

  //transformation de l'état du canvas en rajoutant les parterres déjà enregistrés
  useEffect(() => {
    if (canvas) {
      addExistingFlowerbed(canvas);
      createFlowerbedProperties();

      // Ajouter un écouteur d'événement pour l'événement keydown
      document.addEventListener('keydown', (event) => handleCtrlKey(event, canvas));

      // Ajouter un écouteur d'événement pour l'événement keyup
      document.addEventListener('keyup', (event) => handleCtrlKey(event, canvas));

  }
}, [canvas]);
  


  const initCanvas = () => (
    new fabric.Canvas('canvas', {
      height: 400,
      width: 700,
      backgroundColor: 'white'
    })
  )

  const handleShadowFilterEvent = () => {

    const selectedObject = canvas.getActiveObject();
    // Vérifier si un objet est sélectionné
    if (selectedObject) {
      // Désélectionner l'objet en le définissant sur null
      canvas.discardActiveObject();
      canvas.renderAll();
    }

    // Mettre à jour la valeur de la variable globale en fonction de l'événement
      if (shadowFilter === 1) {
        document.querySelector('#flowerbed-shadow-property').style.visibility = "hidden";
        setShadowFilter(0);
      } else {
        document.querySelector('#flowerbed-property').style.visibility = "hidden";
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
        let selectable = input.dataset.isgardenlimit == "1" || input.dataset.shadowtype > "0" ? false : true;

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

    const handleNewShapeEvent = () => {

      //add tempShape rect
      const test = event => {
        shape = document.getElementById('Rect');
  
        isMouseDown = true;
        isButtonClicked = true;
        shapeType = 1;
        
        let zoom = canvas.getZoom();
        let computedStyle = window.getComputedStyle(shape);
  
        let currentWidth = parseFloat(computedStyle.width);
        shape.style.width = (currentWidth * zoom) + 'px';
  
        let currentHeight = parseFloat(computedStyle.height);
        shape.style.height = (currentHeight * zoom) + 'px';
  
        shape.style.display = 'block';
        shape.style.left = (event.clientX - shape.offsetWidth / 2) + 'px';
        shape.style.top = (event.clientY - shape.offsetHeight / 2) + 'px';
  
        document.addEventListener('mousemove', function(event) {
          mouseMoveHandler(event, shape);
        });
      
  
      };
  
      
  
      //add tempShape circle
      const test2 = event => {
        shape = document.getElementById('Circle');
  
        isMouseDown = true;
        isButtonClicked = true;
        shapeType = 2;
        
        let zoom = canvas.getZoom();
        let computedStyle = window.getComputedStyle(shape);
  
        let currentWidth = parseFloat(computedStyle.width);
        shape.style.width = (currentWidth * zoom) + 'px';
  
        let currentHeight = parseFloat(computedStyle.height);
        shape.style.height = (currentHeight * zoom) + 'px';
  
        shape.style.display = 'block';
        shape.style.left = (event.clientX - shape.offsetWidth / 2) + 'px';
        shape.style.top = (event.clientY - shape.offsetHeight / 2) + 'px';
  
        const mouseMoveCallback = (event) => mouseMoveHandler(event, shape);

        // Ajouter l'écouteur d'événement avec la fonction de rappel
        document.addEventListener('mousemove', mouseMoveCallback);
  
      };

      
  
      var mouseMoveHandler = function(event, shape) {
        if (isMouseDown && isButtonClicked) {
          shape.style.left = (event.clientX - shape.offsetWidth / 2) + 'px';
          shape.style.top = (event.clientY - shape.offsetHeight / 2) + 'px';
        }
      };
  
      //add shape on mouseup
      const test3 = event => {
        if (isMouseDown && isButtonClicked) {
          var canvaEl = document.getElementById('canvas');
          var canvasRect = canvaEl.getBoundingClientRect();
  
          // Vérifier si les coordonnées de la souris se trouvent à l'intérieur des limites du canvas
          if (event.clientX >= canvasRect.left && event.clientX <= canvasRect.right && event.clientY >= canvasRect.top && event.clientY <= canvasRect.bottom) {
            if (isMouseDown && isButtonClicked) {
              const pointer = canvas.getPointer(event);
              if (shapeType == 1) {
                setShapes({
                  ...shapes,
                  new: true,
                  type: 'rectangle',
                  x: (pointer.x - 25),
                  y: (pointer.y - 25),
                  width: 50 / 50,
                  height: 50 / 50,
                  m2: (50 / 50) * (50 / 50)
                });
              } else if (shapeType == 2) {
                setShapes({
                  ...shapes,
                  new: true,
                  type: 'circle',
                  x: (pointer.x - 25),
                  y: (pointer.y - 25),
                  width: 50 / 50,
                  height: 50 / 50,
                  m2: (3.14 * 25 * 25),
                  radius: 25
                });
              }
              
              canvas.off('mouse:move', mouseMoveHandler);
            }
          } 
        
  
          document.getElementById('Rect').style.display = 'none';
          document.getElementById('Rect').style.width = '50px';
          document.getElementById('Rect').style.height = '50px';
  
          document.getElementById('Circle').style.display = 'none';
          document.getElementById('Circle').style.width = '50px';
          document.getElementById('Circle').style.height = '50px';
  
          isMouseDown = false; // Réinitialiser la variable après le relâchement du clic
          isButtonClicked = false; // Réinitialiser la variable après le relâchement du clic
  
        }
      };
  
      document.getElementById('addRect').addEventListener('mousedown', test);
      document.getElementById('addCircle').addEventListener('mousedown', test2); 
      document.addEventListener('mouseup', test3);

      
  
  }

    //ecoute l'ajout d'une nouvelle forme
    handleNewShapeEvent();


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
      if (activeObject.containsPoint(pointer) && activeObject.selectable) {

        if (activeObject && activeObject.isGardenLimit == 0) {

          initialCoords = activeObject.getBoundingRect();
          //fait apparaitre les champs de personnalisation des parterres et ombres
          if (activeObject.shadowtype > 0) {
            document.querySelector('#flowerbed-shadow-property').style.visibility = "visible";
            document.querySelector('select#shadowType').value = activeObject.shadowtype;
          } else {
            document.querySelector('#flowerbed-property').style.visibility = "visible";
            document.querySelector('select#groundType').value = activeObject.groundType == '' || activeObject.groundType == undefined ? null : parseInt(activeObject.groundType);
            document.querySelector('select#groundAcidity').value = activeObject.groundAcidity == '' || activeObject.groundType == undefined ? null : parseInt(activeObject.groundAcidity);
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

        if (activeObject) {

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

            const newWidth = (activeObject.width * scaleX) / 50;
            const newHeight = (activeObject.height * scaleY) / 50;

            setShapes({
              ...shapes,
              new: false,
              type: 'rectangle',
              x: 0,
              y: 0,
              width: newWidth,
              height: newHeight,
              m2: newWidth * newHeight
            });

            activeObject.set({
              width: newWidth * 50,
              height: newHeight * 50,
              scaleX: 1,
              scaleY: 1
            });

            activeObject.setCoords();
            canvas.renderAll();
          }

          if (activeObject.type == "circle") {

            const scaleX = (activeObject.scaleX || 1) * 2;
            const scaleY = (activeObject.scaleY || 1) * 2;

            const newWidth = (activeObject.radius * scaleX) / 50;
            const newHeight = (activeObject.radius * scaleY) / 50;

            setShapes({
              ...shapes,
              new: false,
              type: 'circle',
              x: 0,
              y: 0,
              width: newWidth,
              height: newHeight,
              m2: Math.PI * newWidth * newHeight
            });
          }

        }

    
  })




 canvas.on('mouse:move', (event) => {
  const activeObject = canvas.getActiveObject();

  if (activeObject) {
    if (activeObject.type == "rect") {

      const scaleX = activeObject.scaleX || 1;
      const scaleY = activeObject.scaleY || 1;

      const newWidth = (activeObject.width * scaleX) / 50;
      const newHeight = (activeObject.height * scaleY) / 50;

      setShapes({
        ...shapes,
        new: false,
        type: 'rectangle',
        x: 0,
        y: 0,
        width: newWidth,
        height: newHeight,
        m2: newWidth * newHeight
      });
    }

    if (activeObject.type == "circle") {

      const scaleX = (activeObject.scaleX || 1) * 2;
      const scaleY = (activeObject.scaleY || 1) * 2;

      const newWidth = (activeObject.radius * scaleX) / 50;
      const newHeight = (activeObject.radius * scaleY) / 50;

      setShapes({
        ...shapes,
        new: false,
        type: 'circle',
        x: 0,
        y: 0,
        width: newWidth,
        height: newHeight,
        m2: Math.PI * newWidth * newHeight
      });
    }
   
  }


  });

   
}


  const addRect = (left, top, shadowFilter) => {
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
  const addCircle = (left, top, shadowFilter, radius) => {
    //si la vue est en shadowtype l'objet aura la propriété shadowtype true
    let shadowType = 0;
    let fill;
    let opacity;
    let stroke;
    let shapeRadius;
    
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

    if (radius) {
      shapeRadius = radius;
    } else {
      shapeRadius = 25;
    }


    const circle = new fabric.Circle({
      left: left,
      top: top,
      radius: radius,
      fill: fill,
      stroke: stroke,
      opacity: opacity,
      shadowtype: shadowType,
      isGardenLimit: 0,
    });
    canvas.add(circle);
    canvas.renderAll();
    return circle;
  }

  

  //ajoute un rectangle basique au canvas
  const addGardenLimit = canvi => {
    
    let canvasObjects = canvi.getObjects();
    let gardenLimitButton = document.querySelector('button#gardenLimit');
    let rect;
    let gardenLimitAlreadyExist;
  
    if (gardenLimitButton.textContent === "Modifier la limite du jardin") {

      document.querySelector('button#gardenLimit').textContent = "Valider la limite du jardin";

      //désactive tous les élément d'action sauf le bouton gardenLimit
      document.querySelector('#switchShadowFilter').disabled = true;
      document.querySelectorAll('button').forEach(button => {
        button.disabled = true;
      })
      gardenLimitButton.disabled = false;
      document.querySelectorAll('select').forEach(select => {
        select.disabled = true;
      })
      document.querySelectorAll('input').forEach(input => {
        input.disabled = true;
      })

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

      //réactive tous les élément d'action
      document.querySelector('#switchShadowFilter').disabled = false;
      document.querySelectorAll('button').forEach(button => {
        button.disabled = false;
      })
      document.querySelectorAll('select').forEach(select => {
        select.disabled = false;
      })
      document.querySelectorAll('input').forEach(input => {
        input.disabled = false;
      })

      canvasObjects.forEach((object) => {
        if (object.isGardenLimit == "1") {
          object.set("selectable", false);
          canvi.discardActiveObject();
          canvi.renderAll();
        } else {
          //rendre les objets selectionnable en fonction du filtre selectionnés
          if (shadowFilter && object.shadowtype == 1) {
            object.set("selectable", true);
          } else if (!shadowFilter && object.shadowtype == 0) {
            object.set("selectable", true);
          }
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

  //ajoute les propriétés déjà setter aux formes lors du chargement de la page
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

  function search(canva) {

    const objects = canvas.getObjects();

    objects.forEach(object => {
      object.set('selectable', false);
      canvas.renderAll();
    })

    document.querySelectorAll('button').forEach(button => {
      button.disabled = true;
    })
    document.querySelectorAll('input').forEach(input => {
      input.disabled = true;
    })
    document.querySelectorAll('select').forEach(select => {
      select.disabled = true;
    })
    canvas.getObjects().forEach(object => {
      setTimeout(() => {
        object.selectable = true;
      }, 3000);
    })

    document.querySelector('#searchButton').disabled = false;

    const handleClick = (event) => {


      const pointer = canvas.getPointer(event);
      const x = pointer.x;
      const y = pointer.y;

      

      // Filtrer les objets qui se trouvent à l'endroit du clic
      const objectsAtClick = objects.filter((obj) => {
        const boundingRect = obj.getBoundingRect();
        return boundingRect.left <= x && x <= boundingRect.left + boundingRect.width &&
               boundingRect.top <= y && y <= boundingRect.top + boundingRect.height;
      });

      document.querySelectorAll('button').forEach(button => {
        button.disabled = false;
      })
      document.querySelectorAll('input').forEach(input => {
        input.disabled = false;
      })
      document.querySelectorAll('select').forEach(select => {
        select.disabled = false;
      })
     
      var url = 'http://localhost:8000/plant/search'; // TODO : mettre un chemin relatif
      var datas = objectsAtClick;

      let simplifyFabricObj = {
        shadowtype: null,
        groundType: null,
        groundAcidity: null
      }

      datas.forEach((data) => {
        fabricObjectToSimpleArray(data, simplifyFabricObj);
      });

      setsearchFlowerbedInfo(simplifyFabricObj);


      fetch(url, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json;charset=utf-8'
        },
        body: JSON.stringify(simplifyFabricObj)
      })
      .then((resp) => resp.text())
      .then(function(data) {
        if (data) {
            setSearchPlantInfo(data);
        } else {
          console.log('pas de données.');
        }
      })
      .catch(function(error) {
        setMessage(error);
      })
      

      canvas.off('mouse:down', handleClick);
    };

    canvas.on('mouse:down', handleClick);

    

  }

  function fabricObjectToSimpleArray(obj, simplifyFabricObj) {
    // Copier les propriétés sérialisables dans le nouvel objet
    if (!obj['isGardenLimit'] == '0') {
      if (obj['shadowtype'] != 0) {
        simplifyFabricObj['shadowtype'] = obj['shadowtype'];
      }
      if (obj['groundType'] != '') {
        simplifyFabricObj['groundType'] = obj['groundType'];
      }
      if (obj['groundAcidity'] != '') {
        simplifyFabricObj['groundAcidity'] = obj['groundAcidity'];
      }
    }


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
    
    <div className="canvas-container d-flex">
      <div className="col-8">
        <div id="Rect"></div>
        <div id="Circle"></div>
        <div id="PlantTemp"></div>
        <button id="addRect" className="btn btn-primary">□</button>
        <button id="addCircle" className="btn btn-primary">º</button>
        <button className="btn btn-primary" id="gardenLimit" onClick={() => addGardenLimit(canvas)}>Modifier la limite du jardin</button>
        <button className="btn btn-primary" onClick={() => removeRect(canvas)}>Suppprimer la selection</button>
        <button className="btn btn-primary" id="save_button" onClick={() => save(canvas)}>Sauvegarder</button>
        <p><span>longeur : {shapes.width.toFixed(2)} m</span><span> - </span><span>largeur : {shapes.height.toFixed(2)} m</span><span> - </span><span>surface : {shapes.m2.toFixed(2)} m²</span></p>

        <p>{message}</p>


        <div className="form-check form-switch me-4">
          <input onClick={() => handleShadowFilterEvent()} className="form-check-input off" type="checkbox" role="switch" id="switchShadowFilter" />
          <label className="form-check-label" htmlFor="switchShadowFilter">Ombrages</label>
        </div>

        <canvas id="canvas" />

      <br/><br/>

        <div id="flowerbed-property">
          <select id="groundType" defaultValue="1">
            <option value="null">Aucune</option>
          </select>
          <select id="groundAcidity" defaultValue="1">
            <option value="null">Aucune</option>
          </select>
          <input name="flowerbed_title" id="flowerbed_title" placeholder='Nom du parterre'/>
          <button className="btn btn-primary" onClick={() => addCustomProperty(canvas)}>Enregistrer</button>
        </div>


        <div id="flowerbed-shadow-property">
          <select id="shadowType" defaultValue="1">
          </select>
          <button className="btn btn-primary" onClick={() => addCustomProperty(canvas)}>Enregistrer</button>
        </div>
      </div>

      <div className="col-4">
        <button id="searchButton" className="btn btn-primary" onClick={() => search(canvas)}>Lancer une recherche</button>
      
        {searchPlantInfo && (
            <Search searchFlowerbedInfo={searchFlowerbedInfo} plants={searchPlantInfo}/>
        )}
      </div>

  </div>

  

  
  );
}