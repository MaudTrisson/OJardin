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

  const save = canve => {

    let objects = canve.getObjects();

    /*objects.forEach(object => {
      console.log(object);
    });*/

    var datastring = JSON.stringify(objects);
    var xhr = new XMLHttpRequest();
    var url = "/ajax/SaveGardenCanva.php";
    var data = datastring;

    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-Type", "application/json");

    xhr.onreadystatechange = function () {
      if (xhr.readyState === 4 && xhr.status === 200) {
        var data = JSON.parse(xhr.responseText);
        var obj = JSON.stringify(data);
        console.log(obj);
      } else if (xhr.readyState === 4) {
        console.log('erreur');
      }
    };

    xhr.send(JSON.stringify(data));
    
  }

  return(
    <div>
      <button onClick={() => addRect(canvas)}>Rectangle</button>
      <button onClick={() => save(canvas)}>Sauvegarder</button>
     <br/><br/>
     <canvas id="canvas" />
    </div>
  );
}