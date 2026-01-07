  <div id="footer">
      <hr />
      <div id="innerfooter">
          <p>Home | News and Update | Elections | Results | User's Guide | IT Support Team | Contact | Log Out</p>
          <p>Copyright &copy; 2016 Independent National Electoral Commission </p>
      </div>
  </div>
  <script>
      // global menu toggle for admin pages — keyboard + aria friendly
      (function() {
          document.addEventListener('DOMContentLoaded', function() {
              var toggle = document.getElementById('menu-toggle');
              var cssmenu = document.getElementById('cssmenu');
              var menulist = document.getElementById('cssmenu-list');
              if (toggle && cssmenu && menulist) {
                  // ensure aria-controls is set
                  toggle.setAttribute('aria-controls', menulist.id);
                  toggle.setAttribute('aria-expanded', cssmenu.classList.contains('open') ? 'true' : 'false');

                  function setOpen(open) {
                      if (open) {
                          cssmenu.classList.add('open');
                          toggle.setAttribute('aria-expanded', 'true');
                      } else {
                          cssmenu.classList.remove('open');
                          toggle.setAttribute('aria-expanded', 'false');
                      }
                  }

                  toggle.addEventListener('click', function(e) {
                      e.stopPropagation();
                      setOpen(!cssmenu.classList.contains('open'));
                  });

                  // keyboard support
                  toggle.addEventListener('keydown', function(e) {
                      if (e.key === 'Enter' || e.key === ' ' || e.key === 'Spacebar') {
                          e.preventDefault();
                          setOpen(!cssmenu.classList.contains('open'));
                      }
                  });

                  // close when clicking outside
                  document.addEventListener('click', function(e) {
                      if (!cssmenu.contains(e.target)) setOpen(false);
                  });

                  // prevent touch scroll from immediately closing - small debounce
                  var lastTouch = 0;
                  document.addEventListener('touchstart', function(e) {
                      lastTouch = Date.now();
                  });
                  document.addEventListener('click', function(e) {
                      if (Date.now() - lastTouch < 300) return; // ignore synthetic click after touch
                  }, true);
              }
          });
      })();
  </script>