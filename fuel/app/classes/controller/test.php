<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Controller_Test extends \Controller_App {

    /**
     * The basic welcome message
     *
     * @access  public
     * @return  Response
     */
    public function action_index() {
        echo strtotime('10-10-2030');
        die();
        $username = 'conlatatcainfo@gmail.com';
        $password = '1hoanganh';
        $userId = '100025139146957';
        $groupId = '1531155067212284';
        $limit = 2;
//        $token = 'EAACW5Fg5N2IBALOKsjYpCFgJKt2B1lIUCDDEgCjDX7aNQ0QK79MJ1j28knSbbT0Ra1KHCvrI7yZAWedirOFKOQvkHEyptTlb24GK4HuCx7PEHV23N80xYjZAKmPwOQX4h3QGbNM0abRnCUZCNIZA9B5VyU6MpNLW6bZBNoFLh7hqYZBnomnvPubLnBRJgMZCKUZD';
//        $token = Lib\AutoFB::getToken($username, $password);
//        echo $token;
//        die();
        $token = 'EAAAAUaZA8jlABAMIEO3YmbytsyiR03NyBqC1AxHzguwI2UC32DIHUOt4S0FZB8aGr7yVyQvPgXZA470WZAjZCAGTISKimWWx00OdsGxmQvITDeyaRyoFumWUzjbrMWf4GIgm2bADnCZCo1n7I2RAVoeJIP0PMdQGMHRnjywHkqfVF78PbCJbfZB';
        $posts = Lib\AutoFB::getProfile($token);
//        $posts = Lib\AutoFB::autoAddFriend('100011778742751', $token);
        echo '<pre>';
        print_r($posts);
        die();
        foreach ($posts as $p) {
            $postId = $p['id'];
//            $a = Lib\AutoFB::autoComment($p['id'], $token, 'aa', 'https://chotreo.com/img/chotreo.png');
            $a = Lib\AutoFB::autoReaction($postId, $token);
            print_r($a);
        }
        die();
    }

    /**
     * Generate pass
     *
     * @access  public
     * @return  Response
     */
    public function action_pass() {
        include_once APPPATH . "/config/auth.php";
        $account = $_GET['acc'];
        $pass = $_GET['pw'];
        echo \Lib\Util::encodePassword($pass, $account);
    }

    /**
     * import coupon from attvn
     *
     * @access  public
     * @return  Response
     */
    public function action_attvnimportcoupon() {
        Model_Atvn_Coupon::import();
    }

    /**
     * import top product from attvn
     *
     * @access  public
     * @return  Response
     */
    public function action_attvnimporttopproduct() {
        Model_Atvn_Product::import();
    }
    
    /**
     * Add fb account
     *
     * @access  public
     * @return  Response
     */
    public function action_addfbaccount() {
        include_once APPPATH . "/config/auth.php";
        $account = $_GET['acc'];
        $pass = $_GET['pw'];
        $tokeninfo = Lib\AutoFB::getToken($account, $pass);
        $token = !empty($tokeninfo['access_token']) ? $tokeninfo['access_token'] : '';
        if (!empty($token)) {
            $profile = Lib\AutoFB::getProfile($token);
            $parram = array(
                'email' => $account,
                'password' => $pass,
                'token' => $token,
                'name' => $profile['name'],
                'fb_user_id' => $profile['id']
            );
            echo Model_Fb_Account::add_update($parram);
        } elseif (!empty($tokeninfo['error_data'])) {
            $err = json_decode($tokeninfo['error_data'], true);
            echo $err['error_message'];
        }
        
    }

}
