(function ( $ ) {


    $.fn.slidify = function( options ) {
        var mainContainer = this;
        var m_li=$(this).find('li');
        var m_ul=$(this).find('ul');
        var cid =0;
        
        // This is the easiest way to have default options.
        var settings = $.extend({
        // These are the defaults.
        width: 700,
        height: 300,
        activeslide:1,
        activeClass:'myactive',       
        fadeingSpeed:800,
        setTimeout: 3000,
        theme:'black', //"black"|"grey"|"white"|keep it blank for no theme
        responsive:true
        }, options );
        // Greenify the collection based on the settings variable.
       if(settings.responsive === false)
        {
         this.css({
             width: settings.width,
             height: settings.height
         });
         }
        this.addClass("fancy-new-slider-"+settings.theme);
        m_li.hide();    
        m_li.eq(settings.activeslide-1).addClass(settings.activeClass).fadeIn(10);        
        if(settings.responsive === false)
         m_li.width(settings.width).height(settings.height);
        var imgcount = m_li.length;
        makebullets(imgcount);
        mainContainer.find('div.slidify-bullets a').eq(settings.activeslide-1).addClass('bullet-active');
        if(settings.responsive === false)
        {
         var ulwidth = (m_li.width())*imgcount;
          m_ul.width(ulwidth);
        }
        var callinterval = (settings.fadeingSpeed * 2)+500;
     
        function changetheslide(){
         cid = $('.'+settings.activeClass).index(); 
           m_li.eq(cid).removeClass(settings.activeClass).fadeOut(settings.fadeingSpeed);           
           setTimeout(function () {
               if(cid === (imgcount)-1){
                    mainContainer.find('div.slidify-bullets a').removeClass('bullet-active');
                   mainContainer.find('div.slidify-bullets a').eq(0).addClass('bullet-active');
                   m_li.eq(0).addClass(settings.activeClass).fadeIn(settings.fadeingSpeed);                    
                }
                else{
                    mainContainer.find('div.slidify-bullets a').removeClass('bullet-active');
                    mainContainer.find('div.slidify-bullets a').eq(cid+1).addClass('bullet-active');
                    m_li.eq(cid+1).addClass(settings.activeClass).fadeIn(settings.fadeingSpeed);                    
                 }
            }, settings.fadeingSpeed);    
        }
        function makebullets(slideLength){
            var createbullets="";
            for(k=0;k<slideLength;k++){
              createbullets= createbullets + "<a href='javascript:void(0)' rel="+k+"></a>";
            }
            var bullets="<div class='slidify-bullets'>"+createbullets+"</div>";
            mainContainer.append(bullets);
        }
        $('.slidify-bullets a').click(function(){
            var cslide=$(this).attr('rel');            
             m_li.removeClass(settings.activeClass).fadeOut(settings.fadeingSpeed);
              mainContainer.find('div.slidify-bullets a').removeClass('bullet-active');
             mainContainer.find('div.slidify-bullets a').eq(cslide).addClass('bullet-active')
             m_li.eq(cslide).addClass(settings.activeClass).fadeIn(settings.fadeingSpeed);
             cid=cslide;
            
        });
        setInterval(changetheslide,callinterval);
    };
}( jQuery ));