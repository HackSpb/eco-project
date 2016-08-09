$(document).ready(function() {  
  var formEmail = $("input[type='text']");
 formEmail.keyup(function(){
    
    var email = $(formEmail).val();
  
    if(email != 0)
    {
    if(isValidEmailAddress(email))
    {
    console.info("Имейл правильный");
    formEmail.css({
      "background": "url('images/sprites/form/password.png') 8px 18px no-repeat, url('images/sprites/validYes.png') 98% 10px no-repeat",
      "background-color": "white"
    });
    } else {
    console.info("Имейл неправильный");
    formEmail.css({
      "background": "url('images/sprites/form/password.png') 8px 18px no-repeat, url('images/sprites/validNo.png') 98% 10px no-repeat",
      "background-color": "white"
    });
    }
    } else {
    formEmail.css({
  "background-image": "none"
    }); 
    }
   
    });
  
    });
    function isValidEmailAddress(emailAddress) {
    var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
    return pattern.test(emailAddress);
    }