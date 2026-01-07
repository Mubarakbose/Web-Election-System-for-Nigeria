(function(){
  // Simple enhancement around HTML5 constraint validation
  function showError(field, msg){
    field.classList.add('invalid');
    var parent = field.closest('.form-control') || field.parentElement;
    var em = parent.querySelector('.error-message');
    if(!em){
      em = document.createElement('div');
      em.className = 'error-message';
      parent.appendChild(em);
    }
    em.textContent = msg;
    parent.classList.add('has-error');
  }
  function clearError(field){
    field.classList.remove('invalid');
    var parent = field.closest('.form-control') || field.parentElement;
    var em = parent.querySelector('.error-message');
    if(em){ em.textContent = ''; parent.classList.remove('has-error'); }
  }
  function validateField(field){
    clearError(field);
    if(!field.checkValidity()){
      var msg = field.validationMessage || 'Please correct this field.';
      showError(field, msg);
      return false;
    }
    // custom data-min attribute handling
    var min = field.getAttribute('data-minlength');
    if(min){
      if(field.value.trim().length < parseInt(min,10)){
        showError(field, 'Minimum length is '+min+' characters.');
        return false;
      }
    }
    // data-match: ensure matching fields (e.g., confirm password)
    var matchSel = field.getAttribute('data-match');
    if(matchSel){
      var other = document.querySelector(matchSel);
      if(other && field.value !== other.value){
        showError(field, 'Does not match.');
        return false;
      }
    }
    return true;
  }
  document.addEventListener('DOMContentLoaded', function(){
    document.querySelectorAll('form').forEach(function(form){
      // attach input listeners
      form.querySelectorAll('input,textarea,select').forEach(function(f){
        f.addEventListener('input', function(){ clearError(f); });
        f.addEventListener('blur', function(){ validateField(f); });
      });
      form.addEventListener('submit', function(e){
        var valid = true;
        var firstInvalid = null;
        form.querySelectorAll('input,textarea,select').forEach(function(f){
          if(!validateField(f)){
            valid = false;
            if(!firstInvalid) firstInvalid = f;
          }
        });
        if(!valid){
          e.preventDefault();
          if(firstInvalid && typeof firstInvalid.focus === 'function') firstInvalid.focus();
        }
      });
    });
  });
})();
