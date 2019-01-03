<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Controller_Links extends \Controller_App {
    /**
     * Link list
     */
    public function action_list() {
        return \Bus\Links_List::getInstance()->execute();
    }
    
    /**
     * Link all
     */
    public function action_all() {
        return \Bus\Links_All::getInstance()->execute();
    }
    
    /**
     * Link add/update
     */
    public function action_addupdate() {
        return \Bus\Links_AddUpdate::getInstance()->execute();
    }
    
    /**
     * Link detail
     */
    public function action_detail() {
        return \Bus\Links_Detail::getInstance()->execute();
    }
    
    /**
     * Link delete
     */
    public function action_delete() {
        return \Bus\Links_Delete::getInstance()->execute();
    }
    
    /**
     * Link delete
     */
    public function action_disable() {
        return \Bus\Links_Disable::getInstance()->execute();
    }
}