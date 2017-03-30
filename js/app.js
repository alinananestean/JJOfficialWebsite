$(document).foundation();

$( document ).ready(function() {

    //Scroll Arrows

    $('.scroll-arrows').find('a').click(function(e){
        e.preventDefault();
        var offset = -110;
        var href = $(this).attr('href');
        var anchor = $(href).offset();
        $('body,html').animate({
            scrollTop: anchor.top + offset
        }, 800);
    });


    //On input focus label changes color

    $("form :input").focus(function() {
        $("label[for='" + this.id + "']").addClass("labelfocus");

    }).blur(function() {
        $("label").removeClass("labelfocus");
    });

});