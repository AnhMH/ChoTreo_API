<?php

namespace Bus;

/**
 * GetDetailForFront Product
 *
 * @package Bus
 * @created 2018-10-01
 * @version 1.0
 * @author AnhMH
 */
class Cates_GetDetailForFront extends BusAbstract
{
    /** @var array $_required field require */
    protected $_required = array(
        'url'
    );

    /** @var array $_length Length of fields */
    protected $_length = array();

    /** @var array $_email_format field email */
    protected $_email_format = array(
        
    );

    /**
     * Call function get_detail() from model Product
     *
     * @author AnhMH
     * @param array $data Input data
     * @return bool Success or otherwise
     */
    public function operateDB($data)
    {
        try {
            $this->_response = \Model_Cate::get_detail_for_front($data);
            return $this->result(\Model_Cate::error());
        } catch (\Exception $e) {
            $this->_exception = $e;
        }
        return false;
    }
}
