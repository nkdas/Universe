$(".menu-open").click(function(e) {
    e.preventDefault();
    if ("toggled" != $("#wrapper").attr("class")) {
        $("#wrapper").toggleClass("toggled");
    }
});
$("#menu-close").click(function(e) {
    e.preventDefault();
    if ("toggled" == $("#wrapper").attr("class")) {
        $("#wrapper").toggleClass("toggled");
    }
});
