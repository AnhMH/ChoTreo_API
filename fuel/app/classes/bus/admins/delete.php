<?php

namespace Bus;

/**
 * Delete Admin
 *
 * @package Bus
 * @created 2018-10-01
 * @version 1.0
 * @author AnhMH
 */
class Admins_Delete extends BusAbstract
{
    /** @var array $_required field require */
    protected $_required = array(
        'id'
    );

    /** @var array $_length Length of fields */
    protected $_length = array();

    /** @var array $_email_format field email */
    protected $_email_format = array(
        
    );

    /**
     * Call function get_detail() from model Admin
     *
     * @author AnhMH
     * @param array $data Input data
     * @return bool Success or otherwise
     */
    public function operateDB($data)
    {
        try {
            $this->_response = \Model_Admin::del($data);
            return $this->result(\Model_Admin::error());
        } catch (\Exception $e) {
            $this->_exception = $e;
        }
        return false;
    }
}
