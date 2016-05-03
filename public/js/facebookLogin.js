window.fbAsyncInit = function() {
    FB.init({
        appId      : '615561941932173',
        cookie     : true,  // enable cookies to allow the server to access
                            // the session
        xfbml      : true,  // parse social plugins on this page
        version    : 'v2.5' // use graph api version 2.5
    });

    // Now that we've initialized the JavaScript SDK, we call
    // FB.getLoginStatus().  This function gets the state of the
    // person visiting this page and can return one of three states to
    // the callback you provide.  They can be:
    //
    // 1. Logged into your app ('connected')
    // 2. Logged into Facebook, but not your app ('not_authorized')
    // 3. Not logged into Facebook and can't tell if they are logged into
    //    your app or not.
    //
    // These three cases are handled in the callback function.

    FB.getLoginStatus(function(response) {
        statusChangeCallback(response);
    });
};

// This is called with the results from FB.getLoginStatus().
function statusChangeCallback(response) {
    console.log('statusChangeCallback');
    console.log(response);
    // The response object is returned with a status field that lets our
    // app know the current login status of the person.

    if (response.status === 'connected') {
        // Logged into our app and Facebook.
        testAPI();
    } else if (response.status === 'not_authorized') {
        // The person is logged into Facebook, but not our app.
    } else {
        // The person is not logged into Facebook, so we're not sure if
        // they are logged into this app or not.
    }
}

// This function is called when someone finishes with the Login
// process.
function checkLoginState() {
    FB.getLoginStatus(function(response) {
        statusChangeCallback(response);
    });
}

// Load the SDK asynchronously
(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

// Here we run a very simple test of the Graph API after login is
// successful.  See statusChangeCallback() for when this call is made.
function testAPI() {
    console.log('Facebook login successful');
    FB.api('/me', function(response) {
        console.log('Successful login for: ' + response.name);
    });

    FB.api("/me", function (response) {
            if (response && !response.error) {
                console.log(response);
            } else {
                console.log(response.error);
            }
        }
    );
}

function fb_login(){
    FB.login(function(response) {
        if (response.authResponse) {
            console.log('Facebook login successful');
            access_token = response.authResponse.accessToken;
            user_id = response.authResponse.userID;
            var a;
            FB.api('/me?fields=id,name,email,permissions', function(response) {
                user_email = response.email;
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: "http://universe.com/index/set-google-session",
                    data: "name=" + response.name + "&sender=facebook&email=" + response.email,
                    success: function(data) {
                        if ('ok' == data.status) {
                            document.getElementById('authLi').innerHTML
                                = "<a href='http://universe.com/index/sign-out' onclick='fb_Logout();'>"
                                + "<span class='fa fa-sign-out'></span></a>";
                            location.reload();
                        }
                        else if ('sessionAlreadySet' == data.status) {
                            document.getElementById('authLi').innerHTML
                                = "<a href='http://universe.com/index/sign-out' onclick='fb_Logout();'>"
                                + "<span class='fa fa-sign-out'></span></a>";
                        }
                        else if ('duplicate' == data.status) {
                            document.getElementById('message').innerHTML
                                = "<div class='notification alert alert-danger card-shadow'>"
                                + "<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>"
                                + "Seems like you have already registered with the email associated with Facebook by signing in with Google."
                                + "</div>";
                            fb_Logout();
                        } else {
                            document.getElementById('message').innerHTML
                                = "<div class='notification alert alert-danger card-shadow'>"
                                + "<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>"
                                + "Sorry! Unable to process your request."
                                + "</div>";
                            fb_Logout();
                        }
                    },
                    error: function( jqXHR, textStatus, errorThrown) {
                        console.log(errorThrown);
                    }
                });
            });
        } else {
            //user hit cancel buttons
            console.log('User cancelled login or did not fully authorize.');
        }
    }, {
        scope: 'email'
    });
}

function fb_Logout() {
    FB.logout(function (response){});
}
