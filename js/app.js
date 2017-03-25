$(document).foundation();

$( document ).ready(function() {

    $('.scroll-arrows').find('a').click(function(e){
        e.preventDefault();
        var offset = -110;
        var href = $(this).attr('href');
        var anchor = $(href).offset();
        $('body,html').animate({
            scrollTop: anchor.top + offset
        }, 800);
    });

});