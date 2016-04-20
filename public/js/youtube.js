var CLIENT_ID = '955613673981-fpdqjatkpft248qod3ddnin03tmcp9o1.apps.googleusercontent.com';
var SCOPES = ["https://www.googleapis.com/auth/youtube"];

/**
 * Check if current user has authorized this application.
 */
function checkAuth() {
    gapi.auth.authorize(
        {
            'client_id': CLIENT_ID,
            'scope': SCOPES.join(' '),
            'immediate': true
        }, handleeAuthResult);
}

/**
 * Handle response from authorization server.
 *
 * @param {Object} authResult Authorization result.
 */
function handleAuthResult(authResult) {
    var authorizeDiv = document.getElementById('authorize-div');
    if (authResult && !authResult.error) {
        // Hide auth UI, then load client library.
        //authorizeDiv.style.display = 'none';
        loadYoutubeApi();
    } else {
        // Show auth UI, allowing the user to initiate authorization by
        // clicking authorize button.
        authorizeDiv.style.display = 'inline';
    }
}

/**
 * Initiate auth flow in response to user clicking authorize button.
 *
 * @param {Event} event Button click event.
 */
function handleYoutubeAuthClick(event) {
    gapi.auth.authorize(
        {client_id: CLIENT_ID, scope: SCOPES, immediate: false},
        handleAuthResult);
    return false;
}

/**
 * Load Google Calendar client library. List upcoming events
 * once client library is loaded.
 */
function loadYoutubeApi() {
    gapi.client.load("youtube", "v3", function() {
        // yt api is ready
    });
}

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
          var data = '<div class="item"><iframe class="video w100"  src="http://www.youtube.com/embed/{{videoid}}" frameborder="0" allowfullscreen></iframe></div>';
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
