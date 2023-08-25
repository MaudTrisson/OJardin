/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';

// You can specify which plugins you need
import { Tooltip, Toast, Popover } from 'bootstrap';

// start the Stimulus application
import './bootstrap';

import Glide from '@glidejs/glide';

import { registerReactControllerComponents } from '@symfony/ux-react';
registerReactControllerComponents(require.context('./react/controllers', true, /\.(j|t)sx?$/));

document.addEventListener('DOMContentLoaded', () => {

    const glideContainer = document.querySelector('.glide');
    const slideCount = glideContainer.querySelectorAll('.glide__slide').length;

    let glideConfig = {
        type: 'carousel',
        perView: 1
    };

    if (slideCount > 1) {
        // Activer le défilement automatique si plus d'une diapositive
        glideConfig.autoplay = true;
        glideConfig.autoplayInterval = 5000; // Intervalle entre les diapositives en millisecondes
        glideConfig.animationDuration = 5000;
    }

    const glide = new Glide('.glide', glideConfig);

    glide.mount();

     // Délai en millisecondes avant le début de la transition
     const delayBeforeStart = 3000;

     // Désactiver l'autoplay au chargement de la page
     glide.disable();
 
     // Attendre le délai avant de démarrer l'autoplay
     setTimeout(() => {
         glide.enable();
         glide.mount();
     }, delayBeforeStart);

    
});
