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

document.addEventListener('DOMContentLoaded', () => {
  const paginateds = document.querySelectorAll('[data-hook="paginated"]');
  for (let i = 0; i < paginateds.length; i++) {
    const paginated = paginateds[i];
    paginated.querySelectorAll('input, select').forEach(e =>
      e.addEventListener('change', () => {
        const hook = e.dataset.hook;
        if (hook) {
          const targets = paginated.querySelectorAll(`[data-hook="${hook}"]`);
          targets.forEach(target => target.value = e.value);
        }
        return paginated.dispatchEvent(new Event('submit'));
      }));

    paginated.querySelectorAll('input, select').forEach(e =>
      e.addEventListener('change', () => {
        return paginated.dispatchEvent(new Event('submit'));
      }));

    paginated.addEventListener('submit', (e) => {
      const url = e.target.dataset.action;
      const data = new FormData(e.target);
      window.location.href = `${url}?${new URLSearchParams(data).toString()}`;
    });
  }
})

document.addEventListener('DOMContentLoaded', () => {
  const form = document.querySelector('[data-hook=dependent-questions]');

  // Create maps keyed by question-key for O(1) lookups
  const elementMap = new Map();
  const childrenMap = new Map();
  const parentMap = new Map();

  // Single pass to build all maps
  form.querySelectorAll('[data-question-key]').forEach(el => {
    const key = el.dataset.questionKey;
    elementMap.set(key, el);

    const dependsOn = el.dataset.dependsOn;
    if (dependsOn) {
      const parents = JSON.parse(dependsOn);
      parentMap.set(key, parents);

      parents.forEach(parentKey => {
        if (!childrenMap.has(parentKey)) {
          childrenMap.set(parentKey, new Set());
        }
        childrenMap.get(parentKey).add(key);
      });
    }
  });

  const hasForceCheckedChildren = (key) => {
    const children = childrenMap.get(key);
    if (!children) return false;

    return Array.from(children).some(childKey => {
      const child = elementMap.get(childKey);
      return child && child.disabled && child.checked;
    });
  };

  const handleChange = ({target}) => {
    // Ignore changes on disabled checkboxes
    if (target.disabled) {
      // Revert any attempted changes on disabled checkboxes
      target.checked = target.hasAttribute('checked');
      return;
    }

    const key = target.dataset.questionKey;
    const isChecked = target.checked;

    if (!isChecked && hasForceCheckedChildren(key)) {
      // Prevent unchecking if has force-checked children
      target.checked = true;
      return;
    }

    if (isChecked && parentMap.has(key)) {
      // Check parents (unless disabled)
      parentMap.get(key).forEach(parentKey => {
        const parent = elementMap.get(parentKey);
        if (parent && !parent.checked && !parent.disabled) {
          parent.checked = true;
          parent.dispatchEvent(new Event('change', {bubbles: true}));
        }
      });
    } else if (!isChecked && childrenMap.has(key)) {
      // Uncheck only non-forced children
      childrenMap.get(key).forEach(childKey => {
        const child = elementMap.get(childKey);
        if (child && child.checked && !child.disabled) {
          child.checked = false;
          child.dispatchEvent(new Event('change', {bubbles: true}));
        }
      });
    }
  };

  // Single event listener delegation
  form.addEventListener('change', e => {
    if (e.target.dataset.questionKey) {
      handleChange(e);
    }
  });
});

/** @param {HTMLElement} wrapper */
const useCollectionType = (wrapper) => {
  const selectors = {
    item: wrapper.dataset.itemSelector,
    add: wrapper.dataset.addSelector,
    remove: wrapper.dataset.removeSelector,
    itemWrapper: wrapper.dataset.itemWrapperSelector,
  }

  const addButton = wrapper.querySelector(selectors.add);
  const itemWrapper = wrapper.querySelector(selectors.itemWrapper);

  let lastItem = wrapper.dataset.index;
  addButton.addEventListener('click', function (e) {
    const newForm = wrapper.dataset.prototype.replace(/__name__/g, lastItem);
    const item = itemWrapper.content.cloneNode(true);
    item.querySelector('.form-item').innerHTML = newForm;
    wrapper.appendChild(item);
    lastItem++;
  });
  wrapper.addEventListener('click', function (e) {
    if (e.target.closest(selectors.remove)) {
      e.target.closest(selectors.item).remove();
    }
  });
};
document.addEventListener('DOMContentLoaded', () => {
  console.log('DOMContentLoaded');
  useCollectionType(document.querySelector('.subpostings-wrapper'));
});
