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
  return(
    <div>
      <button onClick={() => addRect(canvas)}>Rectangle</button>
     <br/><br/>
     <canvas id="canvas" />
    </div>
  );
}