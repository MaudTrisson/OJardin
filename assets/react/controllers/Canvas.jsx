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

  const addRect = canvi => {

    const rect = new fabric.Rect({
      height: 280,
      width: 200,
      fill: 'white',
      stroke: 'purple',
      
    });
    canvi.add(rect);
    canvi.renderAll();
 
    canvas.on('mouse:wheel', function(opt) {
      let delta = opt.e.deltaY;
      let zoom = canvas.getZoom();
      zoom *= 0.999 ** delta;
      if (zoom > 20) zoom = 20;
      if (zoom < 0.01) zoom = 0.01;
      canvas.zoomToPoint({ x: opt.e.offsetX, y: opt.e.offsetY }, zoom);
      opt.e.preventDefault();
      opt.e.stopPropagation();
    });
  }

  const save = canve => {

    let objects = canve.getObjects();
    let datastring = JSON.stringify(objects);
    let data = datastring;

    var url = 'http://localhost:8000/garden/save/40'; // mettre un chemin relatif

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

		/*fetch(url)
		.then((resp) => resp.json())
		.then(function(data) {
			if (data) {
			  console.log(data);
			} else {
			  console.log('pas de données');
			}
		})
		.catch(function(error) {
			console.log(error);
		})*/

    /*
    var xhr = new XMLHttpRequest();
    xhr.open("POST", url, true);

    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
      if (xhr.readyState === 4 && xhr.status === 200) {
        var obj = JSON.parse(xhr.responseText);

        
      } else if (xhr.readyState === 4) {
        console.log('erreur');
      }
    };
    xhr.send(JSON.stringify(data));*/
    
  }

  return(
    <div>
      <button class="btn btn-primary" onClick={() => addRect(canvas)}>Rectangle</button>
      <button class="btn btn-primary" id="save_button" onClick={() => save(canvas)}>Sauvegarder</button>
     <br/><br/>
     <canvas id="canvas" />
    </div>
  );
}