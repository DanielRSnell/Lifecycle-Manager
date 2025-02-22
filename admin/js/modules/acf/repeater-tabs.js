export function initRepeaterTabs() {
  const repeaters = document.querySelectorAll('.acf-field-repeater');
  
  repeaters.forEach(repeater => {
    const existingTabs = repeater.querySelector('.repeater-tabs');
    if (existingTabs) {
      existingTabs.remove();
    }

    const rows = repeater.querySelectorAll('.acf-row:not(.acf-clone)');
    if (!rows.length) return;

    const tabsContainer = document.createElement('div');
    tabsContainer.className = 'repeater-tabs';
    
    const tabsList = document.createElement('div');
    tabsList.className = 'repeater-tabs__list';

    const repeaterLabel = repeater.querySelector('.acf-label label').textContent.replace(' *', '');

    rows.forEach((row, index) => {
      const tab = document.createElement('button');
      tab.className = 'repeater-tabs__tab';
      tab.setAttribute('data-row-id', row.getAttribute('data-id'));
      tab.setAttribute('type', 'button');
      
      tab.innerHTML = `
        <span class="repeater-tabs__number">${index + 1}</span>
        <span class="repeater-tabs__name">${repeaterLabel} ${index + 1}</span>
      `;

      tab.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();
        
        rows.forEach(r => r.style.display = 'none');
        row.style.display = '';
        
        tabsList.querySelectorAll('.repeater-tabs__tab').forEach(t => {
          t.classList.remove('is-active');
        });
        tab.classList.add('is-active');
      });

      tabsList.appendChild(tab);
    });

    tabsContainer.appendChild(tabsList);

    const table = repeater.querySelector('.acf-table');
    table.parentNode.insertBefore(tabsContainer, table);

    rows.forEach((row, index) => {
      row.style.display = index === 0 ? '' : 'none';
    });

    tabsList.querySelector('.repeater-tabs__tab').classList.add('is-active');
  });
}
