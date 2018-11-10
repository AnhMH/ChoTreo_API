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
class Model_Atvn_Coupon extends Model_Abstract {
    
    /** @var array $_properties field of table */
    protected static $_properties = array(
        'id',
        'aff_link',
        'banners',
        'categories',
        'content',
        'coupons',
        'domain',
        'end_time',
        'image',
        'link',
        'merchant',
        'name',
        'start_time',
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
    protected static $_table_name = 'atvn_coupons';
    
    /**
     * Add update data
     *
     * @author AnhMH
     * @param array $param Input data
     * @return array|bool Detail Admin or false if error
     */
    public static function add_update($param)
    {
        $id = !empty($param['id']) ? $param['id'] : '';
        $self = array();
        $new = false;
        
        if (!empty($id)) {
            $self = self::find($id);
            if (empty($self)) {
                self::errorNotExist('atvn_id');
                return false;
            }
        } else {
            $self = new self;
            $new = true;
        }
        
        $self->set('id', $id);
        if (!empty($param['aff_link'])) {
            $self->set('aff_link', $param['aff_link']);
        }
        if (!empty($param['banners'])) {
            $self->set('banners', json_encode($param['banners']));
        }
        if (!empty($param['categories'])) {
            $self->set('categories', json_encode($param['categories']));
        }
        if (!empty($param['content'])) {
            $self->set('content', $param['content']);
        }
        if (!empty($param['coupons'])) {
            $self->set('coupons', json_encode($param['coupons']));
        }
        if (!empty($param['domain'])) {
            $self->set('domain', $param['domain']);
        }
        if (!empty($param['end_time'])) {
            $self->set('end_time', self::time_to_val($param['end_time']));
        }
        if (!empty($param['image'])) {
            $self->set('image', $param['image']);
        }
        if (!empty($param['link'])) {
            $self->set('link', $param['link']);
        }
        if (!empty($param['merchant'])) {
            $self->set('merchant', $param['merchant']);
        }
        if (!empty($param['name'])) {
            $self->set('name', $param['name']);
        }
        if (!empty($param['start_time'])) {
            $self->set('start_time', self::time_to_val($param['start_time']));
        }
        
        if ($self->save()) {
            if (empty($self->id)) {
                $self->id = self::cached_object($self)->_original['id'];
            }
            return $self->id;
        }
        return false;
    }
    
    /**
     * import data from batch
     *
     * @author AnhMH
     * @param array $param Input data
     * @return array|bool Detail Admin or false if error
     */
    public static function import()
    {
        $data = \Lib\AccessTrade::getOffers();
        $updateField = array();
        $addUpdateData = array();
        $time = time();
        foreach (self::$_properties as $val) {
            $updateField[$val] = DB::expr("VALUES({$val})");
        }
        if (!empty($data['data'])) {
            foreach ($data['data'] as $val) {
                $tmp = array(
                    'id' => $val['id'],
                    'aff_link' => $val['aff_link'],
                    'banners' => !empty($val['banners']) ? json_encode($val['banners']) : '',
                    'categories' => !empty($val['categories']) ? json_encode($val['categories']) : '',
                    'content' => $val['content'],
                    'coupons' => !empty($val['coupons']) ? json_encode($val['coupons']) : '',
                    'domain' => $val['domain'],
                    'end_time' => self::date_to_val($val['end_time']),
                    'image' => $val['image'],
                    'link' => $val['link'],
                    'merchant' => $val['merchant'],
                    'name' => $val['name'],
                    'start_time' => self::time_to_val($val['start_time']),
                    'created' => $time
                );
                $addUpdateData[] = $tmp;
            }
            self::batchInsert(self::$_table_name, $addUpdateData, $updateField);
        }
        return true;
    }
    
    /**
     * Get list
     * @author AnhMH
     * @param array $param Input data
     * @return array|bool Detail Product or false if error
     */
    public static function get_list($param)
    {
        // Query
        $query = DB::select(
                self::$_table_name.'.*'
            )
            ->from(self::$_table_name)
        ;
        
        // Filter
        if (!empty($param['from_front'])) {
            $query->where(self::$_table_name . '.disable', 0);
            $query->where(self::$_table_name . '.end_time', '>', time());
            $query->where(self::$_table_name . '.start_time', '<=', time());
        }
        
        if (!empty($param['merchant'])) {
            $query->where(self::$_table_name . '.merchant', 'LIKE', "%{$param['merchant']}%");
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
            $query->order_by(self::$_table_name . '.end_time', 'ASC');
        }
        
        // Get data
        $data = $query->execute()->as_array();
        $total = !empty($data) ? DB::count_last_query(self::$slave_db) : 0;
        
        return array(
            'total' => $total,
            'data' => $data
        );
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
            $query->where(self::$_table_name . '.disable', 0);
            $query->where(self::$_table_name . '.end_time', '>', time());
            $query->where(self::$_table_name . '.start_time', '<=', time());
        }
        if (!empty($param['merchant'])) {
            $query->where(self::$_table_name . '.merchant', 'LIKE', "%{$param['merchant']}%");
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
            $query->order_by(self::$_table_name . '.end_time', 'ASC');
        }
        
        // Get data
        $data = $query->execute()->as_array();
        
        return $data;
    }
    
    /**
     * Get detail
     *
     * @author AnhMH
     * @param array $param Input data
     * @return array
     */
    public static function get_detail_for_front($param)
    {
        $data = array();
        $query = DB::select(
                self::$_table_name.'.*'
            )
            ->from(self::$_table_name)
            ->where(self::$_table_name.'.id', $param['id'])
        ;
        $data['coupon'] = $query->execute()->offsetGet(0);
        
        $data['relate_coupons'] = self::get_all(array(
            'limit' => 8,
            'page' => 1,
            'not_id' => !empty($data['coupon']['id']) ? $data['coupon']['id'] : '',
            'merchant' => !empty($data['coupon']['merchant']) ? $data['coupon']['merchant'] : ''
        ));
        
        return $data;
    }
}
