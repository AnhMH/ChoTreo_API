<?php

use Fuel\Core\DB;

/**
 * Any query in Model Version
 *
 * @package Model
 * @created 2017-10-22
 * @version 1.0
 * @author AnhMH
 */
class Model_Fb_Auto_Setting extends Model_Abstract {
    
    /** @var array $_properties field of table */
    protected static $_properties = array(
        'id',
        'fb_account_id',
        'type',
        'expired_date',
        'disable',
        'created',
        'updated',
        'reaction_type'
    );

    protected static $_observers = array(
        'Orm\Observer_CreatedAt' => array(
            'events'          => array('before_insert'),
            'mysql_timestamp' => false,
        ),
        'Orm\Observer_UpdatedAt' => array(
            'events'          => array('before_update'),
            'mysql_timestamp' => false,
        ),
    );

    /** @var array $_table_name name of table */
    protected static $_table_name = 'fb_auto_settings';
    
    /**
     * Get all
     *
     * @author AnhMH
     * @param array $param Input data
     * @return int|bool User ID or false if error
     */
    public static function get_all($param)
    {
       // Query
        $query = DB::select(
                self::$_table_name.'.*',
                'fb_accounts.token',
                'fb_accounts.fb_user_id'
            )
            ->from(self::$_table_name)
            ->join('fb_accounts')
            ->on('fb_accounts.id', '=', self::$_table_name.'.fb_account_id')
            ->where(self::$_table_name.'.expired_date', '>=', time())
        ;
        
        // Filter
        if (!empty($param['type'])) {
            $query->where(self::$_table_name.'.type', $param['type']);
        }
        
        // Get data
        $data = $query->execute()->as_array();
        
        return $data;
    }
}
