// JQuery to toggle opening and closing of the sidebar
$(".menu-open").click(function(e) {
    e.preventDefault();
    if ("toggled" != $("#wrapper").attr("class")) {
        $("#wrapper").toggleClass("toggled");
    }
});
$(".menu-close").click(function(e) {
    e.preventDefault();
    if ("toggled" == $("#wrapper").attr("class")) {
        $("#wrapper").toggleClass("toggled");
    }
});

$(document).ready(function(){
    $("#facebookFeedButton").click();
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "http://universe.com/index/get-theme",
        success: function(data) {
            if (data.status) {
                $('body').css('background-image', data.status);
            }
        },
        error: function( jqXHR, textStatus, errorThrown) {
            console.log(errorThrown);
        }
    });
});

$("#facebookFeedButton").click(function() {
    $.ajax({
        type: "POST",
        dataType: "html",
        url: "http://universe.com/index/facebook-refresh",
        success: function(data) {
            document.getElementById('facebookBody').innerHTML = data;
        },
        error: function( jqXHR, textStatus, errorThrown) {
            console.log(errorThrown);
        }
    });
});

$("#fbFeedSave").click(function() {
    var data = $('#facebookFeedUrl').val();
    var regex = new RegExp('&', 'g');
    data = data.replace(regex, '-aMp-');

    $.ajax({
        type: "POST",
        dataType: "json",
        url: "http://universe.com/index/save-facebook-url",
        data: 'facebookUrl=' + data,
        success: function(data) {
            if ('success' == data.status) {
                document.getElementById('message').innerHTML
                    = "<div class='notification alert alert-success card-shadow'>"
                    + "<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>"
                    + "Facebook feed url saved successfully."
                    + "</div>";
            } else {
                document.getElementById('message').innerHTML
                    = "<div class='notification alert alert-danger card-shadow'>"
                    + "<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>"
                    + "Sorry! Unable to process your request."
                    + "</div>";
            }
        },
        error: function( jqXHR, textStatus, errorThrown) {
            console.log(errorThrown);
        }
    });
});

// Functions for GoogleMap starts
var x = document.getElementById("googleMap");

function initMap() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition);
    } else {
        x.innerHTML = "Geolocation is not supported by this browser.";
    }
}

function showPosition(position) {

    lat = position.coords.latitude;
    lon = position.coords.longitude;

    usersLocation = new google.maps.LatLng(position.coords.latitude,position.coords.longitude);
    var mapProp = {
        center:usersLocation,
        zoom:15,
        mapTypeId:google.maps.MapTypeId.ROADMAP
    };
    var map = new google.maps.Map(document.getElementById("googleMap"),mapProp);

    var marker=new google.maps.Marker({
        position:usersLocation
    });
    marker.setMap(map);

    // Create the search box and link it to the UI element.
    var input = document.getElementById('pac-input');
    var searchBox = new google.maps.places.SearchBox(input);
    map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

    // Bias the SearchBox results towards current map's viewport.
    map.addListener('bounds_changed', function() {
        searchBox.setBounds(map.getBounds());
    });

    var markers = [];
    // Listen for the event fired when the user selects a prediction and retrieve
    // more details for that place.
    searchBox.addListener('places_changed', function() {
        var places = searchBox.getPlaces();

        if (places.length == 0) {
            return;
        }

        // Clear out the old markers.
        markers.forEach(function(marker) {
            marker.setMap(null);
        });
        markers = [];

        // For each place, get the icon, name and location.
        var bounds = new google.maps.LatLngBounds();
        places.forEach(function(place) {
            var icon = {
                url: place.icon,
                size: new google.maps.Size(71, 71),
                origin: new google.maps.Point(0, 0),
                anchor: new google.maps.Point(17, 34),
                scaledSize: new google.maps.Size(25, 25)
            };

            // Create a marker for each place.
            markers.push(new google.maps.Marker({
                map: map,
                icon: icon,
                title: place.name,
                position: place.geometry.location
            }));

            if (place.geometry.viewport) {
                // Only geocodes have viewport.
                bounds.union(place.geometry.viewport);
            } else {
                bounds.extend(place.geometry.location);
            }
        });
        map.fitBounds(bounds);
    });
}

// Functions for GoogleMap ends here


// Setting themes

$('.theme-showcase').click(function(){
    theme = $(this).css('background-image');
    $('body').css('background-image', theme);
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "http://universe.com/index/save-theme",
        data: 'theme=' + theme,
        success: function(data) {
            if ('success' == data.status) {
                document.getElementById('message').innerHTML
                    = "<div class='notification alert alert-success card-shadow'>"
                    + "<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>"
                    + "Theme set successfully."
                    + "</div>";
            } else {
                document.getElementById('message').innerHTML
                    = "<div class='notification alert alert-danger card-shadow'>"
                    + "<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>"
                    + "Sorry! Unable to process your request."
                    + "</div>";
            }
        },
        error: function( jqXHR, textStatus, errorThrown) {
            console.log(errorThrown);
        }
    });
});