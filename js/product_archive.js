jQuery(document).ready(function($) {

    // Cache selectors
    var lastId,
        topMenu = $("#ct-pcat-sidebar"),
        topMenuHeight = topMenu.outerHeight(),
        // All list items
        menuItems = topMenu.find("a"),
        // Anchors corresponding to menu items
        scrollItems = menuItems.map(function(){
          var item = $($(this).attr("href"));
          if (item.length) { return item; }
        });
    
    // Bind click handler to menu items
    // so we can get a fancy scroll animation
    menuItems.click(function(e){

      $('html, body').animate({
          scrollTop: $($.attr(this, 'href')).offset().top
      }, 500);
      e.preventDefault();
    });
    
    // Bind to scroll
    $(window).scroll(function(){
       // Get container scroll position
       var fromTop = $(this).scrollTop()+15;
       
       // Get id of current scroll item
       var cur = scrollItems.map(function(){
         if ($(this).offset().top < fromTop)
           return this;
       });
       // Get the id of the current element
       cur = cur[cur.length-1];
       var id = cur && cur.length ? cur[0].id : "";
       
       if (lastId !== id) {
           lastId = id;
           // Set/remove active class
           menuItems
             .parent().removeClass("ct-pcat-active")
             .end().filter("[href='#"+id+"']").parent().addClass("ct-pcat-active");
       }                   
    });
  
});


