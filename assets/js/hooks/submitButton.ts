const disableSubmitButton = (e: Event) => {
  const element = e.target as HTMLButtonElement;
  const form = element.closest('form');
  let submitButtons;
  if (form) {
    submitButtons = form.querySelectorAll("[data-hook^='submit-button']");
  } else {
    submitButtons = [element];
  }
  console.log(submitButtons);
  submitButtons.forEach((button) => {
    button.classList.add('disabled');
    if (button.tagName === 'a') {
      button.setAttribute('href', '#');
    } else {
      // @ts-ignore
      button.disabled = true;
    }
  });
}

document.addEventListener('DOMContentLoaded', () => {
  const submitButtons = document.querySelectorAll("[data-hook^='submit-button']");
  submitButtons.forEach((button) => {
    button.addEventListener('click', (e) => disableSubmitButton(e));
  });
});
