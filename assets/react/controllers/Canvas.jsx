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

  
  canvas.on('mouse:down', (event) => {
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
    }
  }, [canvas]);


  //ajoute un rectangle basique au canvas
  const addRect = canvi => {

    const rect = new fabric.Rect({
      height: 280,
      width: 200,
      fill: 'white',
      stroke: 'purple',
      
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

  //enregistre les éléments du canvas
  const save = canve => {

    let objects = canve.getObjects();
    let datastring = JSON.stringify(objects);
    let data = datastring;

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

  return(
    <div class="canvas-container">
      <button class="btn btn-primary" onClick={() => addRect(canvas)}>Rectangle</button>
      <button class="btn btn-primary" onClick={() => removeRect(canvas)}>Suppprimer la selection</button>
      <button class="btn btn-primary" id="save_button" onClick={() => save(canvas)}>Sauvegarder</button>
      
     <br/><br/>
     <canvas id="canvas" />
    </div>
  );
}