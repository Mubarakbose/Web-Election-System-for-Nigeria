(function(window, document, $){
  if (!window) return;
  // Ensure we have a jQuery reference; support different load orders
  $ = $ || window.jQuery || window.$;
  if (typeof $ === 'undefined' || $ === null) {
    // jQuery is required for flash helper; do nothing if missing
    if (window && window.console && window.console.warn) {
      window.console.warn('flash.js: jQuery not found — flash UI will not be interactive.');
    }
    return;
  }
  // Simple flash message helper. Depends on jQuery.
  function ensureContainer(){
    var $c = $('#global-flash');
    if ($c.length) return $c;
    $c = $('<div id="global-flash" role="status" aria-live="polite"></div>');
    // insert after header container if present, else at body top
    var $head = $('#HeadContainer');
    if ($head.length) { $head.after($c); } else { $('body').prepend($c); }
    return $c;
  }

  function showFlash(message, type, timeout){
    var $c = ensureContainer();
    type = type || 'info';
    var cls = 'flash-' + type;
    var $msg = $('<div class="flash-item"></div>').addClass(cls);
    var $close = $('<button type="button" class="flash-close" aria-label="Dismiss">\u00d7</button>');
    $msg.append($('<div class="flash-text"></div>').text(String(message))).append($close);
    // close handler
    $close.on('click', function(){ $msg.fadeOut(180, function(){ $(this).remove(); }); });
    $c.append($msg);
    // auto-dismiss
    timeout = (typeof timeout === 'number') ? timeout : 5000;
    if (timeout > 0) setTimeout(function(){ $msg.fadeOut(300, function(){ $(this).remove(); }); }, timeout);
    return $msg;
  }

  // expose globally
  window.showFlash = showFlash;

  // delegated handler: ensure any .flash-close (existing or future) will close its message
  $(document).on('click', '.flash-close', function(e){
    e.preventDefault();
    var $btn = $(this);
    var $msg = $btn.closest('.flash-item');
    $msg.fadeOut(180, function(){ $(this).remove(); });
  });

  // Enhance any server-rendered flash items on load: add close handler if missing
  $(function(){
    var $global = $('#global-flash');
    // If there's at least one form on the page, move the flash container into the first form inside #Content (preferred)
    if ($global.length) {
      var $targetForm = $('#Content').find('form').first();
      if (!$targetForm || $targetForm.length === 0) {
        $targetForm = $('form').first();
      }
      if ($targetForm && $targetForm.length) {
        // move the container into the form as the first child so it appears within the form
        $global.detach().prependTo($targetForm);
      }
    }

    $('#global-flash .flash-item').each(function(){
      var $msg = $(this);
      if ($msg.find('.flash-close').length === 0) {
        var $close = $('<button type="button" class="flash-close" aria-label="Dismiss">\u00d7</button>');
        $msg.append($close);
        $close.on('click', function(){ $msg.fadeOut(180, function(){ $(this).remove(); }); });
      } else {
        // bind handler if present but not bound
        $msg.find('.flash-close').off('click').on('click', function(){ $msg.fadeOut(180, function(){ $(this).remove(); }); });
      }
    });
  });

  // Replace native alert to avoid blocking dialogs — capture and render inline instead.
  if (!window._nativeAlert) window._nativeAlert = window.alert;
  window.alert = function(msg){
    try{
      showFlash(msg, 'info', 8000);
    }catch(e){
      // fallback to native alert
      window._nativeAlert(msg);
    }
  };

  // Small convenience: show error/success shortcuts
  window.showFlashSuccess = function(msg, timeout){ return showFlash(msg, 'success', timeout); };
  window.showFlashError = function(msg, timeout){ return showFlash(msg, 'error', timeout); };

  // expose clear
  window.clearFlashes = function(){ $('#global-flash .flash-item').remove(); };

})(window, document, window.jQuery);
