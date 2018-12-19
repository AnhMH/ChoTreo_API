<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Controller_Admins extends \Controller_App {
    /**
     * Admin login
     */
    public function action_login() {
        return \Bus\Admins_Login::getInstance()->execute();
    }
    
    /**
     * Admin list
     */
    public function action_list() {
        return \Bus\Admins_List::getInstance()->execute();
    }
    
    /**
     * Admin update profile
     */
    public function action_updateprofile() {
        return \Bus\Admins_UpdateProfile::getInstance()->execute();
    }
    
    /**
     * Admin register
     */
    public function action_register() {
        return \Bus\Admins_Register::getInstance()->execute();
    }
    
    /**
     * Get detail for front
     */
    public function action_getdetailforfront() {
        return \Bus\Admins_GetDetailForFront::getInstance()->execute();
    }
    
    /**
     * Admin disable
     */
    public function action_disable() {
        return \Bus\Admins_Disable::getInstance()->execute();
    }
    
    /**
     * Admin delete
     */
    public function action_delete() {
        return \Bus\Admins_Delete::getInstance()->execute();
    }
    
    /**
     * Admin confirm
     */
    public function action_confirm() {
        return \Bus\Admins_Confirm::getInstance()->execute();
    }
    
    /**
     * Admin trust
     */
    public function action_trust() {
        return \Bus\Admins_Trust::getInstance()->execute();
    }
}