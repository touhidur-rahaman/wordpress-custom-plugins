jQuery(function($) {
    $('.nasa-login-register-ajax').click(function() {
      if ($('span.show-password-input.display-password.popup').length) {
      }else{
        setTimeout(
        function() 
        {
          $('span.show-password-input.display-password.popup').click(function() {
            const password =  document.querySelector('#nasa_password');
            const regpassword =  document.querySelector('#nasa_reg_password');
            const type = password.getAttribute( 'type') === 'password' ? 'text' : 'password';
            const type2 = regpassword.getAttribute( 'type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            regpassword.setAttribute('type', type2);
          })
        }, 500);
      }
    });
});