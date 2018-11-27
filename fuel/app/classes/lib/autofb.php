<?php

/**
 * class AutoFB - Support functions for String
 *
 * @package Lib
 * @created 2018-11-15
 * @version 1.0
 * @author AnhMH
 * @copyright Oceanize INC
 */

namespace Lib;

class AutoFB {

    public static $_url_get_post_by_user_id = 'https://graph.fb.me/{USER_ID}/posts?fields={FIELDS}&limit={LIMIT}&access_token={ACCESS_TOKEN}';
    public static $_url_get_home_post = 'https://graph.facebook.com/me/home?limit={LIMIT}&fields={FIELDS}&access_token={ACCESS_TOKEN}&method=GET';
    public static $_url_auto_comment = 'https://graph.fb.me/{POST_ID}/comments?message={MESSAGE}&attachment_url={AU}&method=POST&access_token={ACCESS_TOKEN}';
    public static $_url_auto_reaction = 'https://graph.facebook.com/v3.2/{POST_ID}/reactions?type={TYPE}&method=POST&access_token={ACCESS_TOKEN}';
    public static $_url_auto_post = 'https://graph.facebook.com/v3.2/me/feed?message={MESSAGE}&method=POST&access_token={ACCESS_TOKEN}';
    public static $_url_get_group_member = 'https://graph.facebook.com/{GROUP_ID}/members?limit={LIMIT}&fields={FIELDS}&access_token={ACCESS_TOKEN}';
    public static $_url_auto_add_friend = 'https://graph.facebook.com/me/friends?uid={USER_ID}&access_token={ACCESS_TOKEN}';
    public static $_url_get_profile = 'https://graph.facebook.com/v2.3/me?access_token={ACCESS_TOKEN}&format=json&method=get';

    /**
     * Get post by user id
     *
     * @author AnhMH
     * @return array|bool Response data or false if error
     */
    public static function getPostByUserId($userId, $token, $limit = '10', $fields = 'id,message,picture,name') {
        $url = self::$_url_get_post_by_user_id;
        $url = str_replace('{USER_ID}', $userId, $url);
        $url = str_replace('{ACCESS_TOKEN}', $token, $url);
        $url = str_replace('{LIMIT}', $limit, $url);
        $url = str_replace('{FIELDS}', $fields, $url);

        $data = json_decode(self::call($url), true);
        if (!empty($data['data'])) {
            return $data['data'];
        }
        return false;
    }

    /**
     * Get home posts
     *
     * @author AnhMH
     * @return array|bool Response data or false if error
     */
    public static function getHomePosts($token, $limit = '10', $fields = 'id,message,picture,name,from') {
        $url = self::$_url_get_home_post;
        $url = str_replace('{ACCESS_TOKEN}', $token, $url);
        $url = str_replace('{LIMIT}', $limit, $url);
        $url = str_replace('{FIELDS}', $fields, $url);

        $data = json_decode(self::call($url), true);
        if (!empty($data['data'])) {
            return $data['data'];
        }
        return false;
    }

    /**
     * Get post by user id
     *
     * @author AnhMH
     * @return array|bool Response data or false if error
     */
    public static function getGroupMembers($groupId, $token, $limit = '10', $fields = 'id,message,picture,name') {
        $url = self::$_url_get_group_member;
        $url = str_replace('{GROUP_ID}', $groupId, $url);
        $url = str_replace('{ACCESS_TOKEN}', $token, $url);
        $url = str_replace('{LIMIT}', $limit, $url);
        $url = str_replace('{FIELDS}', $fields, $url);

        $data = json_decode(self::call($url), true);
        if (!empty($data['data'])) {
            return $data['data'];
        }
        return false;
    }
    
    /**
     * Get post by user id
     *
     * @author AnhMH
     * @return array|bool Response data or false if error
     */
    public static function getProfile($token) {
        $url = self::$_url_get_profile;
        $url = str_replace('{ACCESS_TOKEN}', $token, $url);

        $data = json_decode(self::call($url), true);
        if (!empty($data)) {
            return $data;
        }
        return false;
    }

    /**
     * Auto add friend
     *
     * @author AnhMH
     * @return array|bool Response data or false if error
     */
    public static function autoAddFriend($userId, $token) {
        $url = self::$_url_auto_add_friend;
        $url = str_replace('{USER_ID}', $userId, $url);
        $url = str_replace('{ACCESS_TOKEN}', $token, $url);

        $data = json_decode(self::call($url), true);
        return $data;
    }

    /**
     * Auto comment
     *
     * @author AnhMH
     * @return array|bool Response data or false if error
     */
    public static function autoComment($postId, $token, $message, $au) {
        $url = self::$_url_auto_comment;
        $url = str_replace('{POST_ID}', $postId, $url);
        $url = str_replace('{ACCESS_TOKEN}', $token, $url);
        $url = str_replace('{MESSAGE}', urlencode($message), $url);
        $url = str_replace('{AU}', urlencode($au), $url);

        $data = json_decode(self::call($url), true);
        return $data;
    }

    /**
     * Auto comment
     *
     * @author AnhMH
     * @return array|bool Response data or false if error
     */
    public static function autoPost($token, $message) {
        $url = self::$_url_auto_post;
        $url = str_replace('{ACCESS_TOKEN}', $token, $url);
        $url = str_replace('{MESSAGE}', urlencode($message), $url);

        $data = json_decode(self::call($url), true);
        return $data;
    }

    /**
     * Auto reaction
     *
     * @author AnhMH
     * @return array|bool Response data or false if error
     */
    public static function autoReaction($postId, $token, $type = 'LIKE') {
        $url = self::$_url_auto_reaction;
        $url = str_replace('{POST_ID}', $postId, $url);
        $url = str_replace('{ACCESS_TOKEN}', $token, $url);
        $url = str_replace('{TYPE}', $type, $url);

        $data = json_decode(self::call($url), true);
        return $data;
    }

    /**
     * Call api request 
     *
     * @author AnhMH
     * @param string $url Request url.
     * @return array|bool Response data or false if error
     */
    public static function call($url) {
        $cookies = 'liker.txt';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
        curl_setopt($ch, CURLOPT_TCP_NODELAY, true);
        curl_setopt($ch, CURLOPT_REFERER, 'https://graph.fb.me/');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, self::getRandomUserAgent());
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookies);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookies);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        return curl_exec($ch);
        curl_close($ch);
    }

    /**
     * Get random user agent
     *
     * @author AnhMH
     * @return array|bool Response data or false if error
     */
    public static function getRandomUserAgent() {
        $userAgents = array(
            'Mozilla/5.0 (Macintosh; U; PPC Mac OS X; en) AppleWebKit/48 (like Gecko) Safari/48',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.113 Safari/537.36',
            'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.90 Safari/537.36',
            'Mozilla/5.0 (Windows NT 5.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.90 Safari/537.36',
            'Mozilla/5.0 (Windows NT 6.2; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.90 Safari/537.36',
            'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.157 Safari/537.36',
            'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.113 Safari/537.36',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36',
            'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36',
            'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36',
            'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2490.80 Safari/537.36',
            'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.106 Safari/537.36',
            'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.109 Safari/537.36',
            'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.63 Safari/537.36',
            'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.116 Safari/537.36',
            'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.65 Safari/537.36',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.99 Safari/537.36',
            'Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36',
            'Mozilla/5.0 (Windows NT 6.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36',
            'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:54.0) Gecko/20100101 Firefox/54.0',
            'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:40.0) Gecko/20100101 Firefox/40.1',
            'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:50.0) Gecko/20100101 Firefox/50.0',
            'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:52.0) Gecko/20100101 Firefox/52.0',
            'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:50.0) Gecko/20100101 Firefox/50.0',
            'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:52.0) Gecko/20100101 Firefox/52.0',
            'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:41.0) Gecko/20100101 Firefox/41.0',
            'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:46.0) Gecko/20100101 Firefox/46.0',
            'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:44.0) Gecko/20100101 Firefox/44.0',
            'Mozilla/5.0 (Windows NT 10.0; WOW64; rv:54.0) Gecko/20100101 Firefox/54.0',
            'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:47.0) Gecko/20100101 Firefox/47.0',
            'Mozilla/5.0 (Windows NT 6.3; WOW64; rv:52.0) Gecko/20100101 Firefox/52.0',
            'Mozilla/5.0 (Windows NT 6.0; WOW64; rv:52.0) Gecko/20100101 Firefox/52.0',
            'Mozilla/5.0 (iPad; CPU OS 9_3_2 like Mac OS X) AppleWebKit/601.1.46 (KHTML, like Gecko) Version/9.0 Mobile/13F69 Safari/601.1',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/601.7.7 (KHTML, like Gecko) Version/9.1.2 Safari/601.7.7',
            'Mozilla/5.0 (iPhone; CPU iPhone OS 9_1 like Mac OS X) AppleWebKit/601.1.46 (KHTML, like Gecko) Version/9.0 Mobile/13B143 Safari/601.1',
            'Mozilla/5.0 (iPad; CPU OS 10_2_1 like Mac OS X) AppleWebKit/602.4.6 (KHTML, like Gecko) Version/10.0 Mobile/14D27 Safari/602.1',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/603.3.8 (KHTML, like Gecko) Version/10.1.2 Safari/603.3.8',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_3) AppleWebKit/601.4.4 (KHTML, like Gecko) Version/9.0.3 Safari/601.4.4',
            'Mozilla/5.0 (iPhone; CPU iPhone OS 10_2_1 like Mac OS X) AppleWebKit/602.4.6 (KHTML, like Gecko) Version/10.0 Mobile/14D27 Safari/602.1',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_3) AppleWebKit/602.4.8 (KHTML, like Gecko) Version/10.0.3 Safari/602.4.8',
            'Mozilla/5.0 (iPhone; CPU iPhone OS 9_3 like Mac OS X) AppleWebKit/601.1.46 (KHTML, like Gecko) Version/9.0 Mobile/13E188a Safari/601.1',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_4) AppleWebKit/603.1.30 (KHTML, like Gecko) Version/10.1 Safari/603.1.30',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_4) AppleWebKit/601.5.17 (KHTML, like Gecko) Version/9.1 Safari/601.5.17',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_5) AppleWebKit/603.2.4 (KHTML, like Gecko) Version/10.1.1 Safari/603.2.4',
            'Mozilla/5.0 (iPad; CPU OS 9_3_5 like Mac OS X) AppleWebKit/601.1.46 (KHTML, like Gecko) Version/9.0 Mobile/13G36 Safari/601.1',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_5) AppleWebKit/601.6.17 (KHTML, like Gecko) Version/9.1.1 Safari/601.6.17'
        );
        return $userAgents[array_rand($userAgents)];
    }

    /**
     * Get token full quyen
     *
     * @author AnhMH
     * @return array|bool Response data or false if error
     */
    public static function getToken($username, $password, $type = 'android') {
        $linklist = 'https://api.facebook.com/restserver.php';
        $apiKey = ($type == 'android') ? '882a8490361da98702bf97a021ddc14d' : '3e7c78e35a76a9299309885393b02d97';
        $sigKey = ($type == 'android') ? '62f8ce9f74b12f84c123cc23437a4a32' : 'c1e620fa708a1d5696fb991c1bde5662';
        $userAgent = ($type == 'android') ? "Mozilla/5.0 (Linux; Android 4.4.2; SMART 3.5'' Touch+ Build/KOT49H) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/30.0.0.0 Mobile Safari/537.36" : "Mozilla/5.0 (iPhone; CPU iPhone OS 11_0 like Mac OS X) AppleWebKit/604.1.38 (KHTML, like Gecko) Version/11.0 Mobile/15A372 Safari/604.1";
        
        $data = array(
            'api_key' => $apiKey,
            'email' => $username,
            'format' => 'JSON',
            //'generate_machine_id' => '1',
            //'generate_session_cookies' => '1',
            'locale' => 'vi_vn',
            'method' => 'auth.login',
            'password' => $password,
            'return_ssl_resources' => '0',
            'v' => '1.0'
        );
        $sig = '';
        foreach ($data as $key => $value) {
            $sig .= "$key=$value";
        }
        $sig .= $sigKey;
        $data['sig'] = md5($sig);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $linklist);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        $page = curl_exec($ch);
        curl_close($ch);
        $infotoken = json_decode($page, true);
        return $infotoken;
    }

}
