function defaultFeed()
{
    var defaultFeedValue = 'cnn_latest';
    load(defaultFeedValue);
}
function feed(){
    var feedValue = $("#searchFeed").val();
    load(feedValue)
}
function load(feedValue) {
    var feed ="http://rss.cnn.com/rss/" + feedValue +".rss";
    new GFdynamicFeedControl(feed, "feedControl");
}
google.load("feeds", "1");