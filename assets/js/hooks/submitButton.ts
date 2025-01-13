const disableSubmitButton = (button: Element) => {
  setTimeout(() => {
    button.classList.add('disabled');
    if (button.tagName === 'A') {
      button.setAttribute('href', '#');
    } else {
      // @ts-ignore
      button.disabled = true;
    }
  }, 0);
}

document.addEventListener('DOMContentLoaded', () => {
  const submitButtons = document.querySelectorAll("[data-hook^='submit-button']");
  submitButtons.forEach((button) => {
    const form = button.closest('form');
    if (form) {
      form.addEventListener('submit', (e) => {
        for (let i = 0; i < submitButtons.length; i++) {
          disableSubmitButton(submitButtons[i]);
        }
      });
    } else {
      button.addEventListener('click', (e) => disableSubmitButton(e.target as Element));
    }
  });
});
