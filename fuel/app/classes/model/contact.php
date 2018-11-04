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
class Model_Contact extends Model_Abstract {
    
    /** @var array $_properties field of table */
    protected static $_properties = array(
        'id',
        'name',
        'email',
        'message',
        'created',
        'updated'
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
    protected static $_table_name = 'contact';
    
    /**
     * Add update info
     *
     * @author AnhMH
     * @param array $param Input data
     * @return int|bool User ID or false if error
     */
    public static function add_update($param)
    {
        // Init
        $id = !empty($param['id']) ? $param['id'] : 0;
        $self = array();
        $new = false;
        $time = time();
        
        // Check if exist
        if (!empty($id)) {
            $self = self::find($id);
            if (empty($self)) {
                self::errorNotExist('contact_id');
                return false;
            }
        } else {
            $self = new self;
            $new = true;
        }
        
        // Set data
        if (!empty($param['name'])) {
            $self->set('name', $param['name']);
        }
        if (!empty($param['email'])) {
            $self->set('email', $param['email']);
        }
        if (!empty($param['message'])) {
            $self->set('message', $param['message']);
        }
        if ($new) {
            $self->set('created', $time);
        }
        $self->set('updated', $time);
        
        // Save data
        if ($self->save()) {
            if (empty($self->id)) {
                $self->id = self::cached_object($self)->_original['id'];
            }
            return $self->id;
        }
        
        return false;
    }
}
