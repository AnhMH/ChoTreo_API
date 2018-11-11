<?php

namespace Bus;

/**
 * Order Change Status
 *
 * @package Bus
 * @created 2018-11-11
 * @version 1.0
 * @author AnhMH
 */
class Orders_ChangeStatus extends BusAbstract
{
    /** @var array $_required field require */
    protected $_required = array(
        'order_id',
        'status'
    );

    /** @var array $_length Length of fields */
    protected $_length = array();

    /** @var array $_email_format field email */
    protected $_email_format = array(
        
    );

    /**
     * Call function change_status() from model Order
     *
     * @author AnhMH
     * @param array $data Input data
     * @return bool Success or otherwise
     */
    public function operateDB($data)
    {
        try {
            $this->_response = \Model_Order::change_status($data);
            return $this->result(\Model_Order::error());
        } catch (\Exception $e) {
            $this->_exception = $e;
        }
        return false;
    }
}
