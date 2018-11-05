<?php

namespace Bus;

/**
 * Login Admin
 *
 * @package Bus
 * @created 2017-10-22
 * @version 1.0
 * @author AnhMH
 */
class Customers_Login extends BusAbstract
{
    /** @var array $_required field require */
    protected $_required = array(
        'email',
        'password'
    );

    /** @var array $_length Length of fields */
    protected $_length = array(
        'email'    => array(1, 255),
    );

    /** @var array $_email_format field email */
    protected $_email_format = array(
        'email'
    );

    /**
     * Call function get_login() from model Admin
     *
     * @author AnhMH
     * @param array $data Input data
     * @return bool Success or otherwise
     */
    public function operateDB($data)
    {
        try {
            $this->_response = \Model_Customer::get_login($data);
            return $this->result(\Model_Customer::error());
        } catch (\Exception $e) {
            $this->_exception = $e;
        }
        return false;
    }
}
