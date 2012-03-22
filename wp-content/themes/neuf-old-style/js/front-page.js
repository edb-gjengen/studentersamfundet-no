$(document).ready(function(){
	/* events */
    var events = $('#events');
    var articles = events.find('article').each(function(){
            $(this).hover(function(){
                    $(this).find('header.info').children().slideDown('fast');
            },
            function(){
                    $(this).find('header.info').find('.price').slideUp('fast');
                    $(this).find('header.info').find('.venue').slideUp('fast');
                    $(this).find('header.info').find('.type').slideUp('fast');
            });
    });

    /* 
     * Eventslider in header.
     * Doc: http://jquery.malsup.com/cycle/options.html
     */
    $('#slider') 
    .cycle({ 
            fx:     'fade', 
            speed:  'fast', 
            timeout: 8000, 
            next:   '#nextLink', 
            prev:   '#prevLink',
            pager:  '#slidernav', 
            pauseOnPagerHover: 1,
            pagerAnchorBuilder: function(index, DOMelement) {
                return '<a href="#" class="element' + index + '"><span class="circle"></span></a>';
            },
    });

});
