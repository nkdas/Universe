function Load_external_content()
{
      $('#liveRefresh').load().hide().fadeIn(3000);
}
setInterval('Load_external_content()', 20000);

$(document).ready(function(){
     $(".scoreLink").click(function () {
        var linkId = $(this).attr("href");
        var result  = "<?php $u=$array["+linkId+"]['id'] ?>";
        $("div#liveScore").html(result);
        return false;
    });
});