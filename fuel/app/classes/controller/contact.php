<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Controller_Contact extends \Controller_App {
    /**
     * Customer login
     */
    public function action_list() {
        return \Bus\Contact_List::getInstance()->execute();
    }
    
    /**
     * Customer add/update
     */
    public function action_addupdate() {
        return \Bus\Contact_AddUpdate::getInstance()->execute();
    }
    
    /**
     * Customer detail
     */
    public function action_detail() {
        return \Bus\Contact_Detail::getInstance()->execute();
    }
    
    /**
     * Customer delete
     */
    public function action_delete() {
        return \Bus\Contact_Delete::getInstance()->execute();
    }
}