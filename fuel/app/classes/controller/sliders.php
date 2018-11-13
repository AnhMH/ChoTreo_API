<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Controller_Sliders extends \Controller_App {
    /**
     * Slider list
     */
    public function action_list() {
        return \Bus\Sliders_List::getInstance()->execute();
    }
    
    /**
     * Slider add/update
     */
    public function action_addupdate() {
        return \Bus\Sliders_AddUpdate::getInstance()->execute();
    }
    
    /**
     * Slider detail
     */
    public function action_detail() {
        return \Bus\Sliders_Detail::getInstance()->execute();
    }
    
    /**
     * Slider delete
     */
    public function action_delete() {
        return \Bus\Sliders_Delete::getInstance()->execute();
    }
    
    /**
     * Slider delete
     */
    public function action_disable() {
        return \Bus\Sliders_Disable::getInstance()->execute();
    }
}