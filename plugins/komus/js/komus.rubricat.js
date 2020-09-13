var rubricatorRun = function (link) {
	$('.list').css('cursor','pointer').css('list-style-image','url(plugins/komus/images/plus.gif)').click(function(event){
  		if (this == event.target) {
              if ($(this).children().is('ul:hidden')) {
                $(this).css('list-style-image','url(plugins/komus/images/minus.gif)').children().show();
               }
              else {
                $(this).css('list-style-image','url(plugins/komus/images/plus.gif)').children().hide();
               }
            }
         return false;
      }).click();

	  ajaxSuccessHandlers[0] = function(){ 
          Shadowbox.setup("a.search", {
          	handleOversize: "drag",
              modal: true
          });
        };
      
      $('#right_call form').submit(function() {                	
      	var search = $('.input').val();
       	ajaxSend({url: link, divId: 'result_search', formId: 'search'});        
        return false;         
      });
};
