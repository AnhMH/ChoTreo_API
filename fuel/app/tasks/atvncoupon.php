<?php

namespace Fuel\Tasks;

use Fuel\Core\Cli;

/**
 * Import coupon from accesstrade
 *
 * @package             Tasks
 * @create              2018-11-09
 * @version             1.0
 * @author              AnhMH
 * @run                 php oil refine atvncoupon
 * @run                 FUEL_ENV=test php oil refine atvncoupon
 * @run                 FUEL_ENV=production php oil refine atvncoupon
 * @copyright           Oceanize INC
 */
class Atvncoupon {
    
    public function __construct(){
        date_default_timezone_set('Asia/Ho_Chi_Minh');
    }
    
    public static function run() {
        ini_set('memory_limit', -1);
        
        \LogLib::info('BEGIN [Import coupon from accesstrade.vn] ' . date('Y-m-d H:i:s'), __METHOD__, array());
        Cli::write('BEGIN [Import coupon from accesstrade.vn] ' . date('Y-m-d H:i:s') . "\n\nPROCESSING . . . . ! \n");
        
        \Model_Atvn_Coupon::import();
        
        \LogLib::info('END [Import coupon from accesstrade.vn] ' . date('Y-m-d H:i:s'), __METHOD__, array());
        Cli::write('END [Import coupon from accesstrade.vn] ' . date('Y-m-d H:i:s') . "\n");
    }

}
