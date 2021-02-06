$(document).ready(function() {
    window.addEventListener("load",function(){
        const loader = document.querySelector(".loader");
        loader.className += " hidden";
        $('.loader').hide();
        $('#body_data').removeAttr("hidden");
    });
});