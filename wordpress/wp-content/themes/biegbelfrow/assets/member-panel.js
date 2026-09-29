(() => {
  const modals = [...document.querySelectorAll('[data-member-results-modal]')];
  if (!modals.length) return;

  modals.forEach((modal) => {
    const dialog = modal.querySelector('.member-results-dialog');
    const tabs = [...modal.querySelectorAll('[data-member-results-tab]')];
    const panels = [...modal.querySelectorAll('[data-member-results-panel]')];
    let opener;
    const openTab = (type, scrollToResult = false) => {
      tabs.forEach((tab) => tab.setAttribute('aria-selected', String(tab.dataset.memberResultsTab === type)));
      panels.forEach((content) => { content.hidden = content.dataset.memberResultsPanel !== type; });
      if (!scrollToResult) return;
      const result = modal.querySelector(`[data-member-results-panel="${type}"] [data-member-result-highlight]`);
      if (result) requestAnimationFrame(() => result.scrollIntoView({ behavior: 'auto', block: 'center' }));
    };
    const close = () => { modal.hidden = true; document.body.classList.remove('member-results-open'); opener?.focus(); };
    document.querySelectorAll(`[data-member-results-open="${modal.dataset.memberResultsModal}"]`).forEach((button) => {
      button.addEventListener('click', () => {
        opener = button;
        modal.hidden = false;
        document.body.classList.add('member-results-open');
        const selected = tabs.find((tab) => tab.getAttribute('aria-selected') === 'true')?.dataset.memberResultsTab;
        if (selected) openTab(selected, true);
        dialog.focus();
      });
    });
    tabs.forEach((tab) => tab.addEventListener('click', () => openTab(tab.dataset.memberResultsTab)));
    modal.querySelector('[data-member-results-close]').addEventListener('click', close);
    modal.addEventListener('click', (event) => { if (event.target === modal) close(); });
    document.addEventListener('keydown', (event) => { if (event.key === 'Escape' && !modal.hidden) close(); });
  });
})();
