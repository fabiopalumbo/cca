/**
 * Created by loopear on 15/09/15.
 */
$(document).ready(function(){
    $(".dropdown-toggle").click(function(){
        $(".dropdown-perfil").slideToggle("slow");
    });

    var buttonToggled = false;
    $('#toggle').click(function(){
        if(!buttonToggled){
            $("#menu").stop().slideDown(300);
            $("#toggle").toggleClass("on",200);
            buttonToggled = true;
        } else{
            $("#menu").stop().slideUp(300);
            $("#toggle").removeClass("on",200);
            buttonToggled = false;
        }
    });

});

function overlay() {
    var el = document.getElementById("overlay");
    el.style.visibility = (el.style.visibility == "visible") ? "hidden" : "visible";
    document.getElementById('body').style.opacity=  0.4;

}