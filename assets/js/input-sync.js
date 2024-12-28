/**
 * @param {NodeListOf<HTMLElement>|string} elements
 */
export const inputSync = function (elements) {
  console.log(elements);
  if (typeof elements === 'string') {
    elements = document.querySelectorAll(elements);
  }
  console.log(elements);
  elements.forEach((element) => {
    element.addEventListener('input', function () {
      elements.forEach((element) => {
        element.value = this.value;
      });
    });
  });
};
