<?php

/**
 * @class       Application_Model_Facebook_Facebook
 * @path        application/models/Facebook/Facebook.php
 * @description Model to fetch Facebook feeds.
 */
class Application_Model_Facebook_Facebook
{

    /**
    * @function    getFacebookFeed()
    * @description This function is used to fetch facebook feed for the given facebook url
    * @param       string $url facebook feed url
    *
    * @return      boolean
    */
    public function getFacebookFeed($url)
    {
        try
        {
            $url = str_replace("rss20", "json", $url);
            $url = str_replace("-aMp-", "&", $url);

            $agent= 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_USERAGENT, $agent);
            $result=curl_exec($ch);
            curl_close($ch);

            $data = json_decode($result, true);

            $feed = '<h3>' . $data['title'] . '</h3>';

            foreach ($data['entries'] as $key => $value) {
                $feed .= '<p>' . $value['content']
                    .' <a href="' . $value['alternate'] . '">'
                    . '<span class="fa fa-external-link-square"></span></a></p<hr>';
            }

            return $feed;
        } catch (Exception $exception) {
            error_log($exception->getMessage());
            return false;
        }

    }

}