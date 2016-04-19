function Load_external_content()
{
      $('#liveRefresh').load().hide().fadeIn(3000);
}
setInterval('Load_external_content()', 200000000000);

$(document).ready(function(){
     $(".scoreLink").click(function () {
        var linkId = $(this).attr("href");
        var result  = "<?php $u=$array["+linkId+"]['id'] ?>";
        alert(result);
        $("div#liveScore").html(result);
        return false;
    });
});