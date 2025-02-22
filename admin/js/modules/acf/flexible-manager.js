export function initFlexibleManager() {
  window.layout = {
      collapseAll() {
        document.querySelectorAll('.layout').forEach(layout => {
          layout.classList.add('-collapsed');
          const toggleBtn = layout.querySelector('[data-name="collapse-layout"]');
          if (toggleBtn) {
            toggleBtn.classList.remove('-clear');
          }
        });
      }
    }

  window.layout.collapseAll(); 

  document.addEventListener('click', (e) => {
    const collapseButton = e.target.closest('[data-name="collapse-layout"]');
    if (!collapseButton) return;

    e.preventDefault();
    const currentLayout = collapseButton.closest('.layout');
    if (!currentLayout) return;

    if (currentLayout.classList.contains('-collapsed')) {
      document.querySelectorAll('.layout').forEach(layout => {
        layout.classList.add('-collapsed');
        const toggleBtn = layout.querySelector('[data-name="collapse-layout"]');
        if (toggleBtn) {
          toggleBtn.classList.remove('-clear');
        }
      });
      
      currentLayout.classList.remove('-collapsed');
      collapseButton.classList.add('-clear');
    } else {
      currentLayout.classList.add('-collapsed');
      collapseButton.classList.remove('-clear');
    }
  });

  if (typeof window.acf !== 'undefined') {
    window.acf.addAction('append', ($el) => {
      if ($el.hasClass('layout')) {
        document.querySelectorAll('.layout').forEach(layout => {
          if (layout !== $el[0]) {
            layout.classList.add('-collapsed');
            const toggleBtn = layout.querySelector('[data-name="collapse-layout"]');
            if (toggleBtn) {
              toggleBtn.classList.remove('-clear');
            }
          }
        });
        
        const newToggle = $el[0].querySelector('[data-name="collapse-layout"]');
        if (newToggle) {
          newToggle.classList.add('-clear');
        }
      }
    });    
  }
}
