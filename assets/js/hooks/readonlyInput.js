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
