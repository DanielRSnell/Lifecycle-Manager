export function initAcfForms() {
  console.log('Initializing ACF Forms...');
  
  function updateStatus(status) {
    const $indicator = $('.status-indicator');
    const $dot = $indicator.find('.status-dot');
    const $text = $indicator.find('.status-text');
    
    $dot.removeClass('unchanged saving saved').addClass(status);
    $text.text(status.charAt(0).toUpperCase() + status.slice(1));
  }
  
  function attachFormListeners() {
    console.log('Attaching form listeners');
    $('.acf-form').off('submit').on('submit', function(e) {
      e.preventDefault();
      console.log('Form submitted');
      
      const $form = $(this);
      $form.addClass('is-loading');
      updateStatus('saving');
      
      $.ajax({
        url: window.location.href,
        method: 'POST',
        data: $form.serialize(),
        success: (response) => {
          console.log('Form submitted successfully', $form);
          $form.trigger('acf_form_success', [response]);
          updateStatus('saved');
          setTimeout(() => updateStatus('unchanged'), 3000);
        },
        error: (xhr, status, error) => {
          console.error('Form submission failed:', error);
          updateStatus('unchanged');
        },
        complete: () => {
          console.log('Request complete');
          $form.removeClass('is-loading');
        }
      });
    });
  }

  // Initialize
  attachFormListeners();
  console.log('ACF Forms initialization complete');
}

// Make it globally available
window.initAcfForms = initAcfForms;
