<?php

/**
 * class AccessTrade - api get data from accesstrade.vn
 *
 * @package Lib
 * @created 2018-11-09
 * @version 1.0
 * @author AnhMH
 * @copyright ChoTreo INC
 */

namespace Lib;

class AccessTrade {
    
    public static $_url_get_offer = 'https://api.accesstrade.vn/v1/offers_informations';
    public static $_url_get_top_product = 'https://api.accesstrade.vn/v1/top_products';


    /**
    * Call api request 
    *
    * @author AnhMH
    * @param string $url Request url.
    * @param array $param Input data.
    * @param string $method Method GET|POST
    * @return array|bool Response data or false if error
    */
    public static function call($url, $param = array(), $method = 'GET', $headers = array()) {
        try {
            $config = \Config::get('accesstrade');
            
            $headers[] = 'Authorization: Token ' . $config['access_key'];
            $headers[] = 'Content-Type: Application/json';
            $ch = curl_init();
            $options = array(
                CURLOPT_HEADER => false,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => false,
//                CURLOPT_SAFE_UPLOAD => false,
                CURLOPT_TIMEOUT => \Config::get('gmap.timeout', 30),
                CURLOPT_HTTPHEADER => $headers,
            );
            if ($method == 'GET') {
                $url .= '?' . http_build_query($param);
            } elseif ($method == 'POST') {
                $options[CURLOPT_POST] = true;                 
                $options[CURLOPT_POSTFIELDS] = json_encode($param);
                $options[CURLOPT_FOLLOWLOCATION] = true;
                $options[CURLOPT_VERBOSE] = true;
            }      
            $options[CURLOPT_URL] = $url; 
            curl_setopt_array($ch, $options); 
            $jsonResponse = curl_exec($ch);
            $response = json_decode($jsonResponse, true);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $errno = curl_errno($ch);
            curl_close($ch);
            if (empty($errno)) {
                return $response;
            }
            if (isset($response['error_message'])) {
                $message = $response['error_message'];
            } elseif (isset($response['status'])) {
                $message = $response['status'];
            } else {
                $message = 'System error';
            }
            throw new \Exception($message, 500);           
        } catch (\Exception $e) {
             \LogLib::error(sprintf("MirairoID Exception\n"
                            . " - Message : %s\n"
                            . " - Code : %s\n"
                            . " - File : %s\n"
                            . " - Line : %d\n"
                            . " - Stack trace : \n"
                            . "%s",
                            $e->getMessage(), 
                            $e->getCode(), 
                            $e->getFile(), 
                            $e->getLine(), 
                            $e->getTraceAsString()), 
            __METHOD__, $param);
            return false;
        }
    }
    
    /**
    * Call api request 
    *
    * @author AnhMH
    * @param string $url Request url.
    * @param array $param Input data.
    * @param string $method Method GET|POST
    * @return array|bool Response data or false if error
    */
    public static function getOffers() {
        $param = array(
            //'scope' => '',//Truyền value cho tham số này là “expiring” để get các khuyến mại sắp hết hạn. Lấy tất cả nếu không truyền hoặc truyền sai value.
            //'merchant' => '',//Tên owner của khuyến mại. vd: lazada
            //'categories' => '',//category_name của khuyến mại. Ex: voucher-dich-vu. Có thể truyền nhiều category, phân cách nhau bằng dấu phẩy ","
            //'domain' => '',//Domain của khuyến mại. vd: lazada.vn
           // 'coupon' => 0,//Truyền value là 1 để get khuyến mại có mã giảm giá, 0 để get khuyến mại không có mã giảm giá. Mặc định lấy tất cả.
            'status' => 1,//Truyền value 1 để lấy thông tin các offers còn hoạt động, value 0 để lấy thông tin các offers hết hạn, và không truyền key này để lấy tất cả.
        );
        $url = self::$_url_get_offer;
        $data = self::call($url, $param);
        return $data;
    }
    
    /**
    * Call api request 
    *
    * @author AnhMH
    * @param string $url Request url.
    * @param array $param Input data.
    * @param string $method Method GET|POST
    * @return array|bool Response data or false if error
    */
    public static function getTopProducts() {
        $param = array(
            'date_from' => date('d-m-Y', time() - 30*3600),
            'date_to' => date('d-m-Y', time()),
        );
        $url = self::$_url_get_top_product;
        $data = self::call($url, $param);
        return $data;
    }
}

