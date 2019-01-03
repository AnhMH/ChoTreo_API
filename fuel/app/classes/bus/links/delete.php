<?php

namespace Bus;

/**
 * Delete Link
 *
 * @package Bus
 * @created 2018-10-01
 * @version 1.0
 * @author AnhMH
 */
class Links_Delete extends BusAbstract
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
     * Call function get_detail() from model Link
     *
     * @author AnhMH
     * @param array $data Input data
     * @return bool Success or otherwise
     */
    public function operateDB($data)
    {
        try {
            $this->_response = \Model_Link::del($data);
            return $this->result(\Model_Link::error());
        } catch (\Exception $e) {
            $this->_exception = $e;
        }
        return false;
    }
}
