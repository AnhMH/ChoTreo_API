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
class Model_Fb_Auto_Like_Feed extends Model_Abstract {
    
    /** @var array $_properties field of table */
    protected static $_properties = array(
        'id',
        'fb_account_id',
        'post_id',
        'message',
        'picture',
        'name',
        'is_like',
        'created',
        'type',
        'token'
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
    protected static $_table_name = 'fb_auto_like_feed';
    
    /**
     * Get posts
     *
     * @author AnhMH
     * @param array $param Input data
     * @return int|bool User ID or false if error
     */
    public static function get_posts()
    {
        $data = array();
        $fbAccounts = Model_Fb_Auto_Setting::get_all(array(
            'type' => 1
        ));
        if (!empty($fbAccounts)) {
            foreach ($fbAccounts as $acc) {
                $token = $acc['token'];
                $posts = Lib\AutoFB::getHomePosts($token);
                if (!empty($posts)) {
                    foreach ($posts as $p) {
                        $tmp = array(
                            'post_id' => !empty($p['id']) ? $p['id'] : '',
                            'message' => !empty($p['message']) ? $p['message'] : '',
                            'picture' => !empty($p['picture']) ? $p['picture'] : '',
                            'name' => !empty($p['name']) ? $p['name'] : '',
                            'token' => $token,
                            'fb_account_id' => $acc['id'],
                            'is_like' => 0,
                            'type' => !empty($acc['reaction_type']) ? $acc['reaction_type'] : 'LIKE',
                            'created' => time()
                        );
                        $data[] = $tmp;
                    } 
                }
            }
            if (!empty($data)) {
                self::batchInsert('fb_auto_like_feed', $data, array(
                    'post_id' => DB::expr('VALUES(post_id)'),
                    'message' => DB::expr('VALUES(message)'),
                    'picture' => DB::expr('VALUES(picture)'),
                    'name' => DB::expr('VALUES(name)'),
                    'token' => DB::expr('VALUES(token)'),
                    'fb_account_id' => DB::expr('VALUES(fb_account_id)'),
                ));
            }
        }
    }
    
    /**
     * Auto like posts
     *
     * @author AnhMH
     * @param array $param Input data
     * @return int|bool User ID or false if error
     */
    public static function auto_like()
    {
        $addUpdateData = array();
        // Query
        $query = DB::select(
                self::$_table_name.'.*'
            )
            ->from(self::$_table_name)
            ->where(self::$_table_name.'.is_like', 0)
            ->group_by('fb_account_id')
        ;
        
        // Get data
        $data = $query->execute()->as_array();
        if (!empty($data)) {
            foreach ($data as $val) {
                $postId = $val['post_id'];
                $token = $val['token'];
                $type = $val['type'];
                $reaction = Lib\AutoFB::autoReaction($postId, $token, $type);
                if (!empty($reaction['success'])) {
                    $tmp = array(
                        'is_like' => 1,
                        'post_id' => $postId,
                        'fb_account_id' => $val['fb_account_id']
                    );
                    $addUpdateData[] = $tmp;
                }
            }
            if (!empty($addUpdateData)) {
                self::batchInsert('fb_auto_like_feed', $addUpdateData, array(
                    'post_id' => DB::expr('VALUES(post_id)'),
                    'fb_account_id' => DB::expr('VALUES(fb_account_id)'),
                    'is_like' => DB::expr('VALUES(is_like)'),
                ));
            }
        }
        return $data;
    }
}
