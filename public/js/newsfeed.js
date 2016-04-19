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
    //var feed = "https://www.facebook.com/feeds/notifications.php?id=100002424017246&viewer=100002424017246&key=AWhlgCt9ZDBJAqs_&format=rss20";
    new GFdynamicFeedControl(feed, "feedControl");
}
google.load("feeds", "1");