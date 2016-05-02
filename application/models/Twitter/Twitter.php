<?php

require_once APPLICATION_PATH . '/../library/Universe/twitter-api-php-master/TwitterAPIExchange.php';
require_once APPLICATION_PATH . '/../library/Universe/twitteroauth-0.6.2/autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;

/**
 * @class       Application_Model_Twitter_Twitter
 * @path        application/models/Twitter/Twitter.php
 * @description This class is used to authorize a user to Twitter
 *              and get his/her home-timeline.
 */
class Application_Model_Twitter_Twitter
{
    private $_consumerKey, $_consumerSecret;
    public function __construct()
    {
        $this->_consumerKey = 'dvQk9AWXYaXZHieW5PaP0tDaL';
        $this->_consumerSecret = 'jC4q1eCmAzAhEF7WLpMvDXaM22z9sPrJPwjJSVIqGq6JKa72NT';
    }

    /**
     * @function    getOAuthToken()
     * @description This function gets the OAuthToken after authenticating a user to Twitter
     *              The OAuthToken is exchanged with Twitter to get AccessToken, Which on the
     *              other hand is required by the app to get users home-timeline.
     *
     * @return      void
     */
    public function getOAuthToken()
    {

        // Create a connection and ask for request token
        $connection = new TwitterOAuth(
            $this->_consumerKey,
            $this->_consumerSecret
        );

        $requestToken = $connection->oauth(
            'oauth/request_token',
            array('oauth_callback' => 'http://universe.com/index')
        );

        // Save token_secret and oauth_token in the cookies to be used later
        $oauthToken = $requestToken['oauth_token'];
        $tokenSecret = $requestToken['oauth_token_secret'];
        setcookie('token_secret', ' ', time() - 3600);
        setcookie('token_secret', $tokenSecret, time() + 60 * 10);
        setcookie('oauth_token', ' ', time() - 3600);
        setcookie('oauth_token', $oauthToken, time() + 60 * 10);

        // Get the url where the authorization is to be done
        $url = $connection->url('oauth/authorize', array('oauth_token' => $oauthToken));

        // Set session for the controller to know that it has been through getting OAuthToken from Twitter
        // and doesn't need to call this function again.
        $_SESSION['isOAuthTokenPresent'] = '1';

        // Redirect to the url
        header('Location: ' . $url);
        exit;
    }

    /**
     * @function    getAccessToken()
     * @description This function exchanges the OAuthToken with Twitter to get AccessToken,
     *              and uses it to get users home-timeline.
     *
     * @return      string $twitterData string containing the contents of users home-timeline.
     */
    public function getAccessToken()
    {
        $oauthVerifier = $_GET['oauth_verifier'];
        $tokenSecret = $_COOKIE['token_secret'];
        $oauthToken = $_COOKIE['oauth_token'];

        // Exchange the tokens for access token and access token secret
        $connection = new TwitterOAuth(
            $this->_consumerKey,
            $this->_consumerSecret,
            $oauthToken,
            $tokenSecret
        );

        // Verify the OAuthToken and get AccessToken
        $accessTokenData = $connection->oauth(
            'oauth/access_token',
            array('oauth_verifier' => $oauthVerifier)
        );

        $accessToken = $accessTokenData['oauth_token'];
        $secretToken = $accessTokenData['oauth_token_secret'];

        // Use the access tokens to get feeds from twitter
        $settings = array(
            'oauth_access_token' => $accessToken,
            'oauth_access_token_secret' => $secretToken,
            'consumer_key' => $this->_consumerKey,
            'consumer_secret' => $this->_consumerSecret
        );

        $url = 'https://api.twitter.com/1.1/statuses/home_timeline.json';

        $requestMethod = "GET";

        $twitter = new TwitterAPIExchange($settings);

        $string = json_decode($twitter->buildOauth($url, $requestMethod)
            ->performRequest(),$assoc = TRUE);

        $twitterData = '';
        if ($string['errors'][0]['message'] != '') {
            $twitterData .= '<h3>Sorry, there was a problem.</h3>'
                . '<p>Twitter returned the following error message:</p>'
                . '<p><em>' . $string['errors'][0]['message'] . '</em></p>';
        } else {
            // Set session for the controller to know that it has been through getting the
            // access token and required user data from Twitter and doesn't need to call this
            // function again.
            $_SESSION['isOAuthTokenPresent'] = '0';

            foreach($string as $items)
            {
                $twitterData .= '<h3>' . $items['user']['name'] . '</h3>'
                    . '<p>' . $items['created_at'] . '</p>'
                    . '<p class="tweet">' . $items['text'] . '</p>'
                    . '<p><a href="' . $items['entities']['urls']['0']['url'] . '">'
                    . $items['entities']['urls']['0']['url'] . '</a></p>'
                    . '<p>Screen name: ' . $items['user']['screen_name'] . '<br>'
                    . 'Followers: ' . $items['user']['followers_count'] . '<br>'
                    . 'Friends: ' . $items['user']['friends_count'] . '<br>'
                    . 'Listed: ' . $items['user']['listed_count'] . '</p><hr>';
            }
        }
        return $twitterData;
    }
}
