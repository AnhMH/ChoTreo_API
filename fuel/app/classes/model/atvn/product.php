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
class Model_Atvn_Product extends Model_Abstract {
    
    /** @var array $_properties field of table */
    protected static $_properties = array(
        'id',
        'aff_link',
        'brand',
        'category_id',
        'category_name',
        'description',
        'discount',
        'image',
        'link',
        'name',
        'price',
        'product_category',
        'short_desc',
        'disable',
        'created',
        'is_hot'
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
    protected static $_table_name = 'atvn_products';
    
    /**
     * import data from batch
     *
     * @author AnhMH
     * @param array $param Input data
     * @return array|bool Detail Admin or false if error
     */
    public static function import()
    {
        $data = \Lib\AccessTrade::getTopProducts();
        $updateField = array();
        $addUpdateData = array();
        $time = time();
        foreach (self::$_properties as $val) {
            $updateField[$val] = DB::expr("VALUES({$val})");
        }
        if (!empty($data['data'])) {
            foreach ($data['data'] as $val) {
                $tmp = array(
                    'id' => $val['product_id'],
                    'aff_link' => $val['aff_link'],
                    'brand' => !empty($val['brand']) ? $val['brand'] : '',
                    'category_id' => !empty($val['category_id']) ? $val['category_id'] : '',
                    'category_name' => !empty($val['category_name']) ? $val['category_name'] : '',
                    'description' => !empty($val['desc']) ? $val['desc'] : '',
                    'discount' => !empty($val['discount']) ? $val['discount'] : '',
                    'image' => !empty($val['image']) ? $val['image'] : '',
                    'link' => !empty($val['link']) ? $val['link'] : '',
                    'name' => !empty($val['name']) ? $val['name'] : '',
                    'price' => !empty($val['price']) ? $val['price'] : '',
                    'product_category' => !empty($val['product_category']) ? $val['product_category'] : '',
                    'short_desc' => !empty($val['short_desc']) ? $val['short_desc'] : '',
                    'created' => $time
                );
                $addUpdateData[] = $tmp;
            }
            self::batchInsert(self::$_table_name, $addUpdateData, $updateField);
        }
        return true;
    }
    
    /**
     * Get all
     * @author AnhMH
     * @param array $param Input data
     * @return array|bool Detail Product or false if error
     */
    public static function get_all($param)
    {
        // Query
        $query = DB::select(
                self::$_table_name.'.*'
            )
            ->from(self::$_table_name)
        ;
        
        // Filter
        if (!empty($param['from_front'])) {
            $query->where(self::$_table_name . '.price', '>', 0);
            $query->where(self::$_table_name . '.disable', 0);
        }
        
        // Pagination
        if (!empty($param['page']) && $param['limit']) {
            $offset = ($param['page'] - 1) * $param['limit'];
            $query->limit($param['limit'])->offset($offset);
        }
        
        // Sort
        if (!empty($param['sort'])) {
            if (!self::checkSort($param['sort'])) {
                self::errorParamInvalid('sort');
                return false;
            }

            $sortExplode = explode('-', $param['sort']);
            if ($sortExplode[0] == 'created') {
                $sortExplode[0] = self::$_table_name . '.created';
            }
            $query->order_by($sortExplode[0], $sortExplode[1]);
        } else {
            $query->order_by(self::$_table_name . '.is_hot', 'DESC');
            $query->order_by(self::$_table_name . '.created', 'DESC');
        }
        
        // Get data
        $data = $query->execute()->as_array();
        
        return $data;
    }
}
