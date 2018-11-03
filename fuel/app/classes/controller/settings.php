<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Controller_Settings extends \Controller_App {
    /**
     * Setting get top data
     */
    public function action_gettopdata() {
        return \Bus\Settings_GetTopData::getInstance()->execute();
    }
    
    /**
     * Setting get data for front
     */
    public function action_getfrontdata() {
        return \Bus\Settings_GetFrontData::getInstance()->execute();
    }
    
    /**
     * Setting get home data
     */
    public function action_gethomedata() {
        return \Bus\Settings_GetHomeData::getInstance()->execute();
    }
}