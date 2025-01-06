import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './packages/packages';
import './js/app';
import './views/views';
import './app.scss';

document.addEventListener('DOMContentLoaded', () => {
  const createHiddenInput = (element) => {
    const hiddenInput = document.createElement('input');
    hiddenInput.type = 'hidden';
    hiddenInput.name = element.name;
    hiddenInput.value = element.value;
    element.parentElement.appendChild(hiddenInput);
  };

  // Process disabled input elements
  document
    .querySelectorAll(':disabled[data-hook^="readonlyInput"]')
    .forEach((input) => {
      createHiddenInput(input);
      input.name = ''; // Remove name to prevent submission
    });
});

