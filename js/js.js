function verifyOtp(){
	optCode = jQuery('#optCode').val();
      
      jQuery.ajax({
         type : "post",
         dataType : "json",
         url : myAjax.ajaxurl,
         data : {action: "bsp_optVerification", optCode : optCode},
         success: function(response) {
         	
            if(response.Success) {
            	jQuery("#errorMessage").hide();
            	jQuery("#errorMessage").html(response.message);
               jQuery("#errorMessage").fadeIn(response.message);
               jQuery("#errorMessage").css( "color", "green");
               	// self.location ="http://www."+window.location.hostname;
               	self.location ="../";

            }
            else {
            	jQuery("#errorMessage").hide();
               jQuery("#errorMessage").html(response.message);
               jQuery("#errorMessage").fadeIn(response.message);
               jQuery("#errorMessage").css( "color", "red");

            }
         }
      });
}

var url_string = window.location.href; //window.location.href
var url = new URL(url_string);
var c = url.searchParams.get("opt_bsp");
if(c){
   optCode = c;
      
      jQuery.ajax({
         type : "post",
         dataType : "json",
         url : myAjax.ajaxurl,
         data : {action: "bsp_optVerification", optCode : optCode},
         success: function(response) {
            
            if(response.Success) {
               $("#errorMessage").hide();
               $("#errorMessage").html(response.message);
                  alert("your opt is verified");
                  // self.location ="http://www."+window.location.hostname;
                  self.location ="../";

            }
            else {
              alert("Invalid OTP");

            }
         }
      });
}else{
  
}


