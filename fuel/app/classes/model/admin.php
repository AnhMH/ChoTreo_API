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
class Model_Admin extends Model_Abstract {
    
    /** @var array $_properties field of table */
    protected static $_properties = array(
        'id',
        'type',
        'account',
        'password',
        'name',
        'email',
        'address',
        'tel',
        'avatar',
        'website',
        'facebook',
        'description',
        'created',
        'updated',
        'disable',
        'url'
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
    protected static $_table_name = 'admins';

    /**
     * Login Admin
     *
     * @author AnhMH
     * @param array $param Input data
     * @return array|bool Detail Admin or false if error
     */
    public static function get_login($param)
    {
        $login = array();
        $login = self::get_profile(array(
            'email' => $param['email'],
            'password' => \Lib\Util::encodePassword($param['password'], $param['email'])
        ));
        
        if (!empty($login)) {
            if (empty($login['disable'])) {
                $login['token'] = Model_Authenticate::addupdate(array(
                    'user_id' => $login['id'],
                    'regist_type' => 'admin'
                ));
                return $login;
            }
            static::errorOther(static::ERROR_CODE_OTHER_1, 'User is disabled');
            return false;
        }
        static::errorOther(static::ERROR_CODE_AUTH_ERROR, 'Email/Password');
        return false;
    }
    
    /**
     * Get profile
     *
     * @author AnhMH
     * @param array $param Input data
     * @return array|bool Detail Admin or false if error
     */
    public static function get_profile($param)
    {
        // Query
        $query = DB::select(
                self::$_table_name.'.*'
            )
            ->from(self::$_table_name)
        ;
        
        // Filter
        if (!empty($param['admin_id'])) {
            $query->where(self::$_table_name.'.id', $param['admin_id']);
        }
        if (!empty($param['email'])) {
            $query->where(self::$_table_name.'.email', $param['email']);
        }
        if (!empty($param['password'])) {
            $query->where(self::$_table_name.'.password', $param['password']);
        }        
        
        // Get data
        $data = $query->execute()->offsetGet(0);
        
        if (empty($data)) {
            static::errorNotExist('user_id');
            return false;
        }
        
        return $data;
    }
    
    /**
     * Update profile
     *
     * @author AnhMH
     * @param array $param Input data
     * @return array|bool Detail Admin or false if error
     */
    public static function update_profile($param)
    {
        $adminId = !empty($param['admin_id']) ? $param['admin_id'] : '';
        $admin = self::find($adminId);
        $time = time();
        if (empty($admin)) {
            self::errorNotExist('admin_id', $adminId);
            return false;
        }
        
        // Upload image
        if (!empty($_FILES)) {
            $uploadResult = \Lib\Util::uploadImage(); 
            if ($uploadResult['status'] != 200) {
                self::setError($uploadResult['error']);
                return false;
            }
            $param['avatar'] = !empty($uploadResult['body']['avatar']) ? $uploadResult['body']['avatar'] : '';
        }
        
        // Set data
        if (!empty($param['email'])) {
            $admin->set('email', $param['email']);
        }
        if (!empty($param['address'])) {
            $admin->set('address', $param['address']);
        }
        if (!empty($param['tel'])) {
            $admin->set('tel', $param['tel']);
        }
        if (!empty($param['avatar'])) {
            $admin->set('avatar', $param['avatar']);
        }
        if (!empty($param['website'])) {
            $admin->set('website', $param['website']);
        }
        if (!empty($param['facebook'])) {
            $admin->set('facebook', $param['facebook']);
        }
        if (!empty($param['description'])) {
            $admin->set('description', $param['description']);
        }
        if (!empty($param['url'])) {
            $admin->set('url', $param['url']);
        }
        $admin->set('updated', $time);
        
        // Save data
        if ($admin->save()) {
            $admin['token'] = Model_Authenticate::addupdate(array(
                'user_id' => $adminId,
                'regist_type' => 'admin'
            ));
            return $admin;
        }
        return false;
    }
    
    /**
     * Register Admin
     *
     * @author AnhMH
     * @param array $param Input data
     * @return array|bool Detail Admin or false if error
     */
    public static function register($param)
    {
        $self = array();
        $time = time();
        
        $check = self::find('first', array(
            'where' => array(
                'email' => $param['register_email']
            )
        ));
        if (!empty($check)) {
            self::errorDuplicate('email', "Email {$param['register_email']} đã được đăng ký.");
            return false;
        }
        
        $self = new self;
        $self->set('name', $param['register_name']);
        $self->set('email', $param['register_email']);
        $self->set('password', \Lib\Util::encodePassword($param['register_password'], $param['register_email']));
        $self->set('type', 0);
        $self->set('account', '');
        $self->set('created', $time);
        $self->set('updated', $time);
        
        if ($self->save()) {
            if (empty($self->id)) {
                $self->id = self::cached_object($self)->_original['id'];
            }
            $self['token'] = Model_Authenticate::addupdate(array(
                'user_id' => $self->id,
                'regist_type' => 'admin'
            ));
            return $self;
        }
        
        return false;
    }
    
    /**
     * List Order
     *
     * @author AnhMH
     * @param array $param Input data
     * @return array|bool Detail Order or false if error
     */
    public static function get_all($param) {
        // Query
        $query = DB::select(
                        self::$_table_name . '.*'
                )
                ->from(self::$_table_name)
        ;

        // Filter
        $query->where(self::$_table_name.'.url', 'IS NOT', NULL);
        $query->where(self::$_table_name.'.url', '!=', '');

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
            $query->order_by(self::$_table_name . '.type', 'DESC');
        }

        // Get data
        $data = $query->execute()->as_array();

        return $data;
    }
    
    /**
     * Get detail for front
     *
     * @author AnhMH
     * @param array $param Input data
     * @return array|bool Detail Admin or false if error
     */
    public static function get_detail_for_front($param)
    {
        // Init
        $data = array();
        
        // Get admin data
        $admin = self::find('first', array(
            'where' => array(
                'url' => $param['url']
            )
        ));
        
        if (!empty($admin)) {
            $data['shop'] = $admin;
            $param['admin_id'] = $admin['id'];
            $data['products'] = Model_Product::get_list($param);
        }
        
        return $data;
    }
}
