<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Controller_Products extends \Controller_App {
    /**
     * Product login
     */
    public function action_list() {
        return \Bus\Products_List::getInstance()->execute();
    }
    
    /**
     * Product add/update
     */
    public function action_addupdate() {
        return \Bus\Products_AddUpdate::getInstance()->execute();
    }
    
    /**
     * Product detail
     */
    public function action_detail() {
        return \Bus\Products_Detail::getInstance()->execute();
    }
    
    /**
     * Product delete
     */
    public function action_delete() {
        return \Bus\Products_Delete::getInstance()->execute();
    }
}