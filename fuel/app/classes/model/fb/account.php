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
class Model_Fb_Account extends Model_Abstract {
    
    /** @var array $_properties field of table */
    protected static $_properties = array(
        'id',
        'email',
        'password',
        'token',
        'name',
        'avatar',
        'disable',
        'created',
        'updated',
        'admin_id',
        'fb_user_id'
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
    protected static $_table_name = 'fb_accounts';
    
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
        $adminId = !empty($param['admin_id']) ? $param['admin_id'] : 0;
        $id = !empty($param['id']) ? $param['id'] : 0;
        $time = time();
        $self = array();
        $new = false;
        
        // Check if exist User
        if (!empty($id)) {
            $self = self::find($id);
            if (empty($self)) {
                self::errorNotExist('fb_account_id');
                return false;
            }
        } else {
            $self = self::find('first', array(
                'where' => array(
                    'token' => $param['token']
                )
            ));
            if (empty($self)) {
                $self = new self;
                $new = true;
            }
        }
        
        // Set data
        $self->set('admin_id', $adminId);
        if (!empty($param['email'])) {
            $self->set('email', $param['email']);
        }
        if (!empty($param['password'])) {
            $self->set('password', $param['password']);
        }
        if (!empty($param['token'])) {
            $self->set('token', $param['token']);
        }
        if (!empty($param['name'])) {
            $self->set('name', $param['name']);
        }
        if (!empty($param['avatar'])) {
            $self->set('avatar', $param['avatar']);
        }
        if (!empty($param['fb_user_id'])) {
            $self->set('fb_user_id', $param['fb_user_id']);
        }
        if (isset($param['disable'])) {
            $self->set('disable', $param['disable']);
        }
        if ($new) {
            $self->set('created', $time);
            $self->set('updated', $time);
        } else {
            $self->set('updated', $time);
        }
        
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
