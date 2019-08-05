$(document).on('click', 'a[href^="#"]', function (event) {
    event.preventDefault();

    $('html, body').animate({
        scrollTop: $($.attr(this, 'href')).offset().top
    }, 500);
});

$(document).ready(function() {
    //Page loader
    if ($('.pageloader').length) {

        $('.pageloader').toggleClass('is-active');

        
        var pageloaderTimeout = setTimeout( function() {
            $('.pageloader').toggleClass('is-active');
            clearTimeout( pageloaderTimeout );
        }, 1500 );
    }
});