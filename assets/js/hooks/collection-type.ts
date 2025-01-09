// Types for better code documentation
interface CollectionTypeConfig {
  itemSelector: string;
  addSelector: string;
  removeSelector: string;
  itemsListSelector: string;
  prototype: string;
  index: number;
}

/**
 * Handles dynamic form collection functionality
 * @param {HTMLElement} wrapper - The main wrapper element containing the collection
 */
const useCollectionType = (wrapper: HTMLElement): void => {
  if (!wrapper) {
    console.warn('Collection type wrapper not found');
    return;
  }

  // Extract configuration from data attributes
  const config: CollectionTypeConfig = {
    itemSelector: wrapper.dataset.itemSelector || '',
    addSelector: wrapper.dataset.addSelector || '',
    removeSelector: wrapper.dataset.removeSelector || '',
    itemsListSelector: wrapper.dataset.itemsListSelector || '',
    prototype: wrapper.dataset.prototype || '',
    index: parseInt(wrapper.dataset.index || '0', 10)
  };

  // Cache DOM elements
  const elements = {
    addButton: wrapper.querySelector<HTMLElement>(config.addSelector),
    itemsList: wrapper.querySelector<HTMLElement>(config.itemsListSelector)
  };

  // Validate required elements
  if (!elements.addButton || !elements.itemsList || !config.prototype) {
    console.error('Required elements or prototype missing', {elements, prototype: config.prototype});
    return;
  }

  /**
   * Creates a new collection item
   * @returns {void}
   */
  const addCollectionItem = (): void => {
    try {
      const newForm = config.prototype
        .replace(/__name__/g, config.index.toString())
        .replace(/__name1__/g, (config.index + 1).toString());
      const temp = document.createElement('template');
      temp.innerHTML = newForm;
      const item = temp.content.cloneNode(true);
      console.log(item);
      elements.itemsList!.appendChild(item);
      config.index++;
    } catch (error) {
      console.error('Error adding collection item:', error);
    }
  };

  /**
   * Removes a collection item
   * @param {Event} event - Click event
   */
  const removeCollectionItem = (event: Event): void => {
    const target = event.target as HTMLElement;
    const removeButton = target.closest(config.removeSelector);

    if (removeButton) {
      const item = removeButton.closest(config.itemSelector);
      item?.remove();
    }
  };

  // Event listeners
  elements.addButton.addEventListener('click', addCollectionItem);
  wrapper.addEventListener('click', removeCollectionItem);
};

document.addEventListener('DOMContentLoaded', () => {
  const wrapper = document.querySelectorAll<HTMLElement>('[data-hook="collection-type"]');
  wrapper.forEach(useCollectionType);
});
