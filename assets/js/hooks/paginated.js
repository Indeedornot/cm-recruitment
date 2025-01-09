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
