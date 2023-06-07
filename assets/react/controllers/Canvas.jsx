import React, { useState, useEffect } from 'react';
import { fabric } from 'fabric';


export default function () {
  const [canvas, setCanvas] = useState('');
  useEffect(() => {
    setCanvas(initCanvas());
  }, []);

  const initCanvas = () => (
    new fabric.Canvas('canvas', {
      height: 500,
      width: 900,
      backgroundColor: 'pink'
    })
  )
  const addRect = canvi => {
    const rect = new fabric.Rect({
      height: 280,
      width: 200,
      fill: 'yellow'
    });
    canvi.add(rect);
    canvi.renderAll();
  }
  return(
    <div>
      <button onClick={() => addRect(canvas)}>Rectangle</button>
     <br/><br/>
     <canvas id="canvas" />
    </div>
  );
}