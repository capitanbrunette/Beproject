$(document).ready(function() {
    // executes when HTML-Document is loaded and DOM is ready
    console.log("document is ready");


    $( ".card" ).hover(
        function() {
            $(this).addClass('shadow-lg').css('cursor', 'pointer');
        }, function() {
            $(this).removeClass('shadow-lg');
        }
    );

// document ready
});

function myFunction(url) {
    var http = new XMLHttpRequest();
    if (confirm("Are you sure you want to delete your account?")) {
        window.location.replace(url);

    }
}





