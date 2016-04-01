function tplawesome(e,t){res=e;for(var n=0;n<t.length;n++){res=res.replace(/\{\{(.*?)\}\}/g,function(e,r){return t[n][r]})}return res}

$(function() {
    $("#youtube-form").on("submit", function(e) {
       e.preventDefault();
       // prepare the request
       var request = gapi.client.youtube.search.list({
            part: "snippet",
            type: "video",
            q: encodeURIComponent($("#search").val()).replace(/%20/g, "+"),
            maxResults: 6,
            safeSearch: "strict",
            videoDefinition: "standard"
       }); 
       // execute the request
       request.execute(function(response) {
          var results = response.result;
          $("#youtube-results").html("");
          $.each(results.items, function(index, item) {
          var data = '<div class="item"><iframe class="video w100"  src="//www.youtube.com/embed/{{videoid}}" frameborder="0" allowfullscreen></iframe></div>';
                $("#youtube-results").append(tplawesome(data, [{"title":item.snippet.title, "videoid":item.id.videoId}]));
           
          });
          resetVideoHeight();
       });
    });
    
    $(window).on("resize", resetVideoHeight);
});

function resetVideoHeight() {
    $(".video").css("height", $("#youtube-results").width() * 9/16);
}

function init() {
    gapi.client.setApiKey("AIzaSyC81SGJIDyufp9HgdqAXTGjORVbzWHA__8");
    gapi.client.load("youtube", "v3", function() {
        // yt api is ready
    });
}
