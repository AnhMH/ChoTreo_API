<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Controller_Coupons extends \Controller_App {
    /**
     * Coupon login
     */
    public function action_list() {
        return \Bus\Coupons_List::getInstance()->execute();
    }
    
    /**
     * Coupon add/update
     */
    public function action_addupdate() {
        return \Bus\Coupons_AddUpdate::getInstance()->execute();
    }
    
    /**
     * Coupon detail
     */
    public function action_detail() {
        return \Bus\Coupons_Detail::getInstance()->execute();
    }
    
    /**
     * Coupon delete
     */
    public function action_delete() {
        return \Bus\Coupons_Delete::getInstance()->execute();
    }
    
    /**
     * Coupon all
     */
    public function action_autocomplete() {
        return \Bus\Coupons_AutoComplete::getInstance()->execute();
    }
    
    /**
     * Coupon all
     */
    public function action_all() {
        return \Bus\Coupons_All::getInstance()->execute();
    }
    
    /**
     * Coupon all
     */
    public function action_getdetailforfront() {
        return \Bus\Coupons_GetDetailForFront::getInstance()->execute();
    }
}