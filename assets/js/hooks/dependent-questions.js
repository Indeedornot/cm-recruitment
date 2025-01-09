document.addEventListener('DOMContentLoaded', () => {
  const form = document.querySelector('[data-hook=dependent-questions]');
  if (!form) return;

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
