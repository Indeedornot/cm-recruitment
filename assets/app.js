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
  const inputSyncElements = document.querySelectorAll('[data-hook^="inputSync"]');
  console.log({inputSyncElements})
  const groupedElements = {};
  inputSyncElements.forEach((element) => {
    const hook = element.getAttribute('data-hook');
    (groupedElements[hook] ??= []).push(element);
  });

  Object.entries(groupedElements).forEach(([hook, elements]) => {
    window.inputSync(elements);
  });

  console.log({groupedElements})
})

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

document.addEventListener('DOMContentLoaded', () => {
  const tables = document.querySelectorAll('[data-hook="userTable"]');
  tables.forEach((e) => e.addEventListener('click', async (event) => {
    /** @type {HTMLElement} */
    const target = event.target;
    if (!target.matches('.toggle-user-btn')) {
      return;
    }

    const name = target.getAttribute('data-user-name');
    const type = target.getAttribute('data-type');
    const path = target.getAttribute('data-path');

    const el = document.createElement('div');
    let text;
    if (target.classList.contains('disable-user-btn')) {
      text = translations[`admin.accounts.manage.${type}.disable_text`];
    } else {
      text = translations[`admin.accounts.manage.${type}.restore_text`];
    }
    el.innerHTML = text.replace('{name}', name);

    const response = await Swal.fire({
      title: translations['common.confirm_your_action'],
      html: el,
      icon: 'warning',
      confirmButtonText: translations['common.confirm']
    });

    if (response.isConfirmed) {
      window.location.href = path;
    }
  }));
});
