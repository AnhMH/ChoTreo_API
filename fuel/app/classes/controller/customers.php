<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Controller_Customers extends \Controller_App {
    /**
     * Customer login
     */
    public function action_list() {
        return \Bus\Customers_List::getInstance()->execute();
    }
}